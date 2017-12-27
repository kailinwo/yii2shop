<?php

namespace backend\controllers;

use backend\models\Brands;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use common\widgets\ueditor\UeditorAction;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Request;
use yii\web\UploadedFile;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;
class GoodsController extends \yii\web\Controller
{
    //文件上传的验证需要开启
    public $enableCsrfValidation=false;
    //商品首页
    public function actionIndex()
    {
        //先做分页
        $query = Goods::find();
        //接受参数
        $name = \Yii::$app->request->get('name');
        $sn = \Yii::$app->request->get('sn');
        if($name){
            $query->andWhere(['like','name',$name]);
        }
        if($sn){
            $query->andWhere(['like','sn',$sn]);
        }
        $pager = new Pagination([
            'totalCount'=>$query->andWhere(['status'=>1])->count(),
            'defaultPageSize'=>2,
        ]);
        $model= $query->limit($pager->limit)->offset($pager->offset)->andWhere(['status'=>1])->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    //商品的添加
    public function actionAdd(){
        //表单对象
        $model = new Goods();
        //表单详情的对象
        $goodsintro = new GoodsIntro();
        //创建表单提交对象
        $request = new Request();
        //创建每日添加商品的数量对象
        $daycount = GoodsDayCount::find()->where(['day'=>date('Ymd')])->one();
//        var_dump($daycount->count);die;
        if($request->isPost){
            //加载表单数据
            $model->load($request->post());
            $goodsintro->load($request->post());
//            var_dump($daycount);die;
            if($model->validate()){
                //处理数据>>goods
                $model->create_time = time();
                $model->save();
                //处理数据goodsintro的数据
                //得到goods的最后插入id
                $goods_id=\Yii::$app->db->getLastInsertID();
                $goodsintro->goods_id=$goods_id;
                $goodsintro->save();
                //得到daycount所需要的两个参数
                //先查询数据表里面的count数量,
                if($daycount){
                    $daycount->count +=1;
                }else{
                    //如果没有相应时间的数量,就创建一条新的数据,从1开始!
                    $daycount = new GoodsDayCount();
                    $daycount->day = date('Ymd',time());
                    $daycount->count= 1;
                }
                $daycount->save();//保存到数据库里面
//                var_dump($model->sn);die;
                //提示信息
                \Yii::$app->session->setFlash('success','添加商品成功!');
                //跳转
                return $this ->redirect(['goods/index']);
            }
        }
        //查询商品分类表
        $goodsCategory = ArrayHelper::map(GoodsCategory::find()->all(),'id','name');
        //自动生成sn根据年月日
//        $model->sn=date("Ymd",time()).'00000';
        $model->sn +=1;//给sn一个初始值从1开始
        //如果daycount存在,就在后面追加6个零
        $model->sn=str_pad($model->sn,5,0,0);
        if($daycount){
//            var_dump($daycount->count);die;
            //今天的时间加上上面的sn+从数据表里面查询出来的数据
            $model->sn = date('Ymd').$model->sn+$daycount->count;
        }else{
            $model->sn=date('Ymd',time()).$model->sn;
        }
//        var_dump($model->sn);
        //查询品牌分类表
        $brandsCategory = ArrayHelper::map(Brands::find()->all(),'id','name');
        return $this->render('add',['model'=>$model,'brandsCategory'=>$brandsCategory,'goodsintro'=>$goodsintro]);
    }
    //处理ajax>>logo图片上传
    public function actionUploader(){
        $logo=UploadedFile::getInstanceByName('file');//使用新的创建图片对象的方法Byname()
        $dirname = "Uploads/".\Yii::$app->controller->id.'/'.date('Ymd')."/";
        if(!is_dir($dirname)){
            //如果目录不存在,就创建目录
            mkdir($dirname,0777,true);
        }
        //处理文件名
        $filename = uniqid().'.'.$logo->extension;
        $files='/'.$dirname.$filename;
//        移动文件到指定的目录里面去
        if($logo->saveAs(\Yii::getAlias('@webroot').$files,0)){
            //上传成功
            //=====上传文件至七牛云=====xGTHiewSZCLZJOlKTA3o9NaBooI-WlI2PR05h-aX
            $accessKey ="xGTHiewSZCLZJOlKTA3o9NaBooI-WlI2PR05h-aX";
            $secretKey = "2azjoHQ5W1j98bgLhhVRRp4e-BIusyl1JsaKxQtg";
            $bucket = "yiishop";
            $domain = "p1cn3k26g.bkt.clouddn.com";
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            $filename =$files;
            // 要上传文件的本地路径backend/web/Uploads/brands/20171221/1.jpg
            $filePath = \Yii::getAlias('@webroot').$files;
            // 上传到七牛后保存的文件名
            $key = $filename;
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
//                    echo "\n====> putFile result: \n";
            if ($err !== null) {
                //错误的
//                        var_dump($err);
                return json_encode(['error'=>1]);
            } else {
                //上传成功,给那边的隐藏输入框返回一个地址,用来保存到数据库
                //图片访问http://<domain>/<$files>;
//                        var_dump($ret);
                $url="http://{$domain}/{$key}";
                return json_encode(['url'=>$url]);
            }
            //=======================
//                    return json_encode(['url'=>$files]);
        }else{
            return json_encode(['error'=>1]);
        }
    }
    //富文本编辑器
    public function actions(){

        return [
            'ueditor'=>[
                'class' =>UeditorAction::className(),
                'config'=>[
                    //上传图片配置
                    'imageUrlPrefix' => "", /* 图片访问路径前缀 */
                    'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
                ]
            ]
        ];
    }
    //商品修改
    public function actionEdit($id){
        $request = new Request();
        $model = Goods::findOne(['id'=>$id]);
        $goodsIntro = GoodsIntro::find()->where(['goods_id'=>$id])->one();
        if($request->isPost){
            //加载表单数据
            $model->load($request->post());
            $goodsIntro->load($request->post());
            if($model->validate()){
                $model->save();
                $goodsIntro->save();
                //修改成功>>提示信息
                \Yii::$app->session->setFlash('success','修改商品信息成功!');
                return $this->redirect(['goods/index']);
            }

        }
        $brandsCategory = ArrayHelper::map(Brands::find()->all(),'id','name');
        return $this->render('add',['model'=>$model,'brandsCategory'=>$brandsCategory,'goodsintro'=>$goodsIntro]);
    }
    //商品删除
    public function actionDelete($id){
        //根据id查询数据
        Goods::updateAll(['status'=>0],['id'=>$id]);
    }
    //商品图片列表
    public function actionPhoto($id){
        //根据商品的id查询出所有该id的图片;
        $model =GoodsGallery::find()->where(['goods_id'=>$id])->all();
        //传id过去添加商品图片给相应的goods_id上面
        return $this->render('photolist',['model'=>$model,'id'=>$id]);
    }
    //商品图片添加
    public function actionPhotoadd($id){
        $photo = new GoodsGallery();
        $logo=UploadedFile::getInstanceByName('file');//使用新的创建图片对象的方法Byname()
        $dirname = "Uploads/".\Yii::$app->controller->id.'/'.date('Ymd')."/";
        if(!is_dir($dirname)){
            //如果目录不存在,就创建目录
            mkdir($dirname,0777,true);
        }
        //处理文件名
        $filename = uniqid().'.'.$logo->extension;
        $files='/'.$dirname.$filename;
//        移动文件到指定的目录里面去
        if($logo->saveAs(\Yii::getAlias('@webroot').$files,0)){
            //上传成功
            //=====上传文件至七牛云=====xGTHiewSZCLZJOlKTA3o9NaBooI-WlI2PR05h-aX
            $accessKey ="xGTHiewSZCLZJOlKTA3o9NaBooI-WlI2PR05h-aX";
            $secretKey = "2azjoHQ5W1j98bgLhhVRRp4e-BIusyl1JsaKxQtg";
            $bucket = "yiishop";
            $domain = "p1cn3k26g.bkt.clouddn.com";
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            $filename =$files;
            // 要上传文件的本地路径backend/web/Uploads/brands/20171221/1.jpg
            $filePath = \Yii::getAlias('@webroot').$files;
            // 上传到七牛后保存的文件名
            $key = $filename;
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
//                    echo "\n====> putFile result: \n";
            if ($err !== null) {
                //错误的
//                        var_dump($err);
                return json_encode(['error'=>1]);
            } else {
                //上传成功,给那边的隐藏输入框返回一个地址,用来保存到数据库
                //图片访问http://<domain>/<$files>;
//                        var_dump($ret);
                $url="http://{$domain}/{$key}";
                //给接受id和路径参数>>写数据表
                $photo->goods_id=$id;
                $photo->path =$url;
                $photo->save();
                return json_encode(['url'=>$url]);
            }
            //=======================
//                    return json_encode(['url'=>$files]);
        }else{
            return json_encode(['error'=>1]);
        }
    }
    //商品图片的删除
    public function actionPhotodelete($id){
            GoodsGallery::deleteAll(['id'=>$id]);
    }
}
