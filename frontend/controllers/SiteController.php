<?php
namespace frontend\controllers;
use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Member;
use Yii;
use yii\base\InvalidParamException;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\SignatureHelper;
use yii\web\Cookie;
use yii\web\Response;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>4,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    //关闭验证
    public $enableCsrfValidation = false;
    //用户注册
    public function actionRegister(){
        $model = new Member();
        if(\Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post(),'');
//            var_dump($model);die;
            $model->create_at = time();
            if($model->validate()){
                //密码hash
                $model->password_hash=Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->save(false);//之前因为写了compare两个密码做比较的,就验证不了!切记,表单模型里面的验证规则要好好写!>>>>加上了验证码要写false
                //注册成功,提示信息跳转到登录页面
                $this->jump('注册','/site/user-login.html','成功!');
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('register');
    }
    //用户名唯一性验证
    public function actionCheckUser($username){
        //接收到username之后查询数据表里面的字段
        $count = Member::find()->where(['username'=>$username])->count();
        if($count){
            echo 'false';
        }else{
            echo 'true';
        }
    }
    //短信发送
    public function actionSms($phone){
        //前端获取用户的手机号码,在这里进行验证>>验证成功之后,才发送短信!
        //手机号码正则验证通过之后,再发送短信!
        $check = preg_match("/^1[3|4|5|7|8][0-9]\d{4,8}$/",$phone);
        if($check){
            $code = rand(100000,999999);
            $request = Yii::$app->sms->send($phone,['code'=>$code]);
            if($request->Code=="OK"){
                //短信发送成功之后>>这里可以将它保存到redis里面!
                $redis = new \Redis();
                $redis->connect('127.0.0.1');
                $redis->set('code_'.$phone,$code,5*60);//设置为五分钟
                return "true";
            }else{
                return "验证码发送失败!";
            }
        }else{
            return "手机号码格式不正确!";
        }

    }
    //短信验证
    public function actionChecksms($tel,$captcha){
        //开启redis取的存到redis里面的短信code
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $redcode = $redis->get('code_'.$tel);
        if($redcode){
            //如果取到值了就进行验证码的对比
            if($redcode==$captcha){
                return 'true';
            }else{
                return 'false';
            }
        }else{
            //没有取到就返回false;
            return 'false';
        }

    }
    //用户登录
    public function actionUserLogin(){
        $model =new \frontend\models\LoginForm();
        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post(),'');
//            var_dump($model);die;
            if($model->validate()){
                //根据用户名去找信息,然后对比秘密
                $userinfo = Member::findOne(['username'=>$model->username]);
//                var_dump($userinfo->password_hash);die;
                $dbpassword = $userinfo['password_hash'];
                if($userinfo){
                    //用户名存在.比对密码
                    if(Yii::$app->security->validatePassword($model->password_hash,$dbpassword)){//对比成功,就去到想要显示的页面上
                        //登陆成功要报存session里面去!!
                        if($model->remmber){
                            Yii::$app->user->login($userinfo,7*24*3600);
                        }else{
                            Yii::$app->user->login($userinfo);
                        }
                        //得到用户的id
                        $id = Yii::$app->user->id;
                        //更新用户的信息
                        Member::updateAll(['last_login_time'=>time(),'last_login_ip'=>$_SERVER['REMOTE_ADDR']],['id'=>$id]);
                        //将cookie里面的数据持久化到数据表里面去!
                        $this->Transfer();
                       $this->jump('登录','/site/index.html','成功!');
                    }else{//对比失败//提示错误信息:
                        var_dump($model->addError('password_hash','密码不正确'));
                    }
                }else{
                    //用户名不存在,提示错误信息
                    var_dump($model->addError('username','用户名不存在!'));
                }
            }
        }
        return $this->render('userlogin',['model'=>$model]);
    }
    //用户注销
    public function actionUserLogout(){
        Yii::$app->user->logout();
        //退出后跳转到网站页面
       return $this->redirect(['site/index']);
    }
    //跳转jump
    public function jump($jump,$url,$stu="成功"){
        require '../views/site/jump.php';//根据相对路径来找!可能布置到linux上面有路径的问题!
        header('Refresh:2;url='.$url);die;
    }
    //收货地址添加
    public function actionAddressAdd(){
        $model = new Address();
        $adinfo = Address::find()->where(['member_id'=>Yii::$app->user->id])->all();
        if(Yii::$app->request->isPost){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->load(Yii::$app->request->post(),'');
            if($model->validate()){
                $model->create_at = time();
                $model->member_id = Yii::$app->user->id; //按照自己的会员id保存,页面查询按照自己的id来!
                if($model->status==1){//根据这个用户的id去找该用户所有的地址并将其它所有修改为0
                    Address::updateAll(['status'=>0],['member_id'=>Yii::$app->user->identity->getId()]);
                }
                $model->save();
                //返回json数据
//                $id = Yii::$app->db->getLastInsertID();
//                return implode('',Yii::$app->request->post());
//                return ['address'=>Yii::$app->request->post()];
                return [
                    'status' => 1,
                    'msg' => '添加成功'
                ];
            }else{
                return [
                    'status' => -1,
                    'msg' => '添加失败,原因:'.current($model->getFirstErrors())
                ];
                //打印错误信息
            }
        }
        return $this->render('address',['adinfo'=>$adinfo]);
    }
    //收货地址删除
    public function actionAddressDelete($id){
        Address::deleteAll(['id'=>$id]);
    }
    //收货地址修改
    public function actionAddressUpdate($id){
        $model = Address::findOne(['id'=>$id]);
        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post(),'');
            if($model->validate()){
                if($model['status']==1){
                    Address::updateAll(['status'=>0],['member_id'=>Yii::$app->user->identity->getId()]);
                }
                $model->save();
               //修改成功->>跳转
             return $this->redirect(['site/address-add']);
            }
        }
        return $this->render('address-edit',['address'=>$model]);
    }
    //默认地址的修改
    public function actionAddressEdit($id){
        //修改默认地址的时候要将其它的全部状态设置为0 ;返回json数据
        Yii::$app->response->format = Response::FORMAT_JSON;
        Address::updateAll(['status'=>0],['member_id'=>Yii::$app->user->id]);
        Address::updateAll(['status'=>1],['id'=>$id]);
        return ['msg' => '修改成功'];
    }
    //添加购车车成功页面
    public function actionCartAdd($goods_id,$amount){
        //用户未登录就存放到cookie里面
        if(Yii::$app->user->isGuest){
            //在保存到cookie之前,,先看看cookie里面是有商品;
            $cookies = Yii::$app->request->cookies;
            //如果cookie里面有"cart"就得到里面的值,并且反序列化,得到数据
            //这段代码的意思就是说:cookie里面可以存放多个商品,
           if($cookies->has('cart')){
               $values = $cookies->getValue('cart');
               $cart = unserialize($values);
           }else{
               $cart=[];
           }
           //判断是否有同样的商品,有的话就在原来的基础上+新的数据,没有则添加一条商品数据到cookie里面
            if(array_key_exists($goods_id,$cart)){
               $cart[$goods_id]+=$amount;
            }else{
                $cart[$goods_id] = $amount;
            }
            //保存到cookie里面去
            $cookies = Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name ='cart';
            $cookie->value = serialize($cart);
            $cookies->add($cookie);
        }else{
        //用户登录之后就直接存到数据库里面
            //>>根据用户传过来的商品id去查找购物车表里面当前用户的商品信息
            //>>如果在购物车表查询到相同的商品那么数量上面就加上提交过来的商品数量!没有就新建一条数据
            $count =Cart::findOne(['goods_id'=>$goods_id,'member_id'=>Yii::$app->user->id]);
            if($count){
               $num = $count['amount']+$amount;
                Cart::updateAll(['amount'=>$num],['goods_id'=>$goods_id]);
            }else{
                //如果没有找到本次添加的商品信息>>新建一个模型对象,然后save!
                $model = new Cart();
                $model->goods_id = $goods_id;
                $model->amount = $amount;
                $model->member_id = Yii::$app->user->id;
                $model->save();
            }
        }
        //加载添加到购物车信息的页面,模板没有就直接跳转到购物车页面去!
        return $this->redirect(['site/cart']);
    }
    //购物车页面
    public function actionCart()
    {
        //判断用户是否登录::未登录就显示cookie里面的数据
        if(Yii::$app->user->isGuest){
            //从cookie里面获取数据
            $cookies = Yii::$app->request->cookies;
            //得到$cart里面的goods_id然后根据goods表的id去查询goods数据表里的商品信息;
            if($cookies->has('cart')){
                $values = $cookies->getValue('cart');
                $cart = unserialize($values);
            }else{
                $cart=[];
            }
            $ids = array_keys($cart);
        }else{
        //用户登录之后根据用户的id去查询数据库
            //>>找到购物车表里面所有当前用户的商品条目
            $users=Cart::find()->where(['member_id'=>Yii::$app->user->id])->all();
            //得到所有的购物车数据表里面的id,然后去goods表里面找到这里面的所有的goods信息
            $ids = ArrayHelper::map($users,'goods_id','goods_id');
            $cart = ArrayHelper::map($users,'goods_id','amount');
        }
        //加载视图
        $model = Goods::find()->where(['in','id',$ids])->all();
        return $this->render('cart',['model'=>$model,'cart'=>$cart]);
    }
    //购物车物品的删除
    public function actionCartdelete($id){
        if(Yii::$app->user->isGuest){
            //在cookie里面保存的商品数据>>先去找到该商品的的信息,然后删除
            $cookies = Yii::$app->request->cookies;
            $values = $cookies->getValue('cart');
            $cart = unserialize($values);//一维数组
            unset($cart[$id]); //删除cookie里面的数据
            //将里面的商品删除后,再重新设置cookie
            $cookies = Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name = 'cart';
            $cookie->value = serialize($cart);
            $cookies->add($cookie);
        }else{
            //用户是登陆的状态则操作数据表里面的操作
//            Cart::deleteAll(['goods_id'=>$id],['member_id'=>Yii::$app->user->id]);
            Cart::deleteAll(['goods_id'=>$id,'member_id'=>Yii::$app->user->id]);
        }
    }
    //商品数量的改变
    public function actionCartChange(){
        $goods_id = Yii::$app->request->post('goods_id');
        $amount = Yii::$app->request->post('amount');
        if(Yii::$app->user->isGuest){
            //如果是游客
            $cookies = Yii::$app->request->cookies;
            if($cookies->has('cart')){
                $values = $cookies->getValue('cart');
                $cart = unserialize($values);
            }else{
                $cart=[];
            }
            //提交过来的商品id和商品数量
            $cart[$goods_id]=$amount;
            //保存到cookie里面去
            $cookies = Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name ='cart';
            $cookie->value = serialize($cart);
            $cookies->add($cookie);
        }else{
            //如果是用户
           Cart::updateAll(['amount'=>$amount],['goods_id'=>$goods_id,'member_id'=>Yii::$app->user->id]);
        }
    }
    //将cookie里面的数据持久化到数据表
    public static function Transfer(){
        $cookies = Yii::$app->request->cookies;
        if($cookies->has('cart')){
            $values = $cookies->getValue('cart');
            $cart = unserialize($values);
            foreach ($cart as $k => $v) {
                $goodsinfo = Cart::findOne(['goods_id'=>$k,'member_id'=>Yii::$app->user->id]);
                if($goodsinfo){
                    $num = $goodsinfo->amount+$v;
                    Cart::updateAll(['amount'=>$num],['goods_id'=>$k,'member_id'=>Yii::$app->user->id]);
                }else{
                    $model = new Cart();
                    $model->goods_id = $k;
                    $model->amount = $v;
                    $model->member_id = Yii::$app->user->id;
                    $model->save();
                }
            }
        }
        //同步到数据表之后删除cookie里面的数据
        $cookies = Yii::$app->response->cookies;
        $cookies->remove('cart');
//        var_dump(unserialize(Yii::$app->request->cookies->getValue('cart')));die('登录后...');
    }
    //全局搜索
    public function actionSearch($keywords){
        //查询的是商品表,显示商品,使用模糊搜索
        $query = \frontend\models\Goods::find()->andWhere(['like','name',$keywords])->count();
        $pager = new Pagination([
            'totalCount'=>$query,
            'defaultPageSize'=>10,
        ]);
        $model = \frontend\models\Goods::find()->limit($pager->limit)->offset($pager->offset)->andWhere(['like','name',$keywords])->all();
        return $this->render('search',['model'=>$model,'pager'=>$pager]);
    }
}
