<?php

namespace backend\controllers;
use backend\filters\RbacFilter;
use backend\models\Brands;
use yii\web\UploadedFile;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class BrandsController extends \yii\web\Controller
{
    public $enableCsrfValidation=false;
    //品牌的首页
    public function actionIndex()
    {
        $model =Brands::find()->where(['>=','status',0])->all() ;
        return $this->render('index',['model'=>$model]);
    }
    //品牌的添加
    public function actionAdd()
    {
        //创建提交对象
        $request = \Yii::$app->request;
        //创建model对象
        $model= new Brands();
        if($request->isPost){
            //加载表单的数据
            $model->load($request->post());
            //创建图片的上传对象
//            $model->img= UploadedFile::getInstance($model,'img');
            //验证数据
            if($model->validate()){
//                //图片上传的路径,
//                $dirname = "Uploads/".\Yii::$app->controller->id.'/'.date('Ymd')."/";
//                if(!is_dir($dirname)){
//                    //如果目录不存在,就创建目录
//                    mkdir($dirname,0777,true);
//                }
//                //处理文件名
//                $filename = uniqid().'.'.$model->img->extension;
//                $files='/'.$dirname.$filename;
                //移动文件到指定的目录里面去
//                if($model->img->saveAs(\Yii::getAlias('@webroot').$files)){
//                    $model->logo = $files;
//                }
//                var_dump($model->logo);die;
                $model->save(false);
                //提示信息
                \Yii::$app->session->setFlash('success','添加品牌成功!');
                //跳转值品牌表首页
                return $this->redirect(['brands/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //处理ajax图片上传
    public function actionUploader(){
        $img=UploadedFile::getInstanceByName('file');//使用新的创建图片对象的方法Byname()
        $dirname = "Uploads/".\Yii::$app->controller->id.'/'.date('Ymd')."/";
                if(!is_dir($dirname)){
                    //如果目录不存在,就创建目录
                    mkdir($dirname,0777,true);
                }
                //处理文件名
                $filename = uniqid().'.'.$img->extension;
                $files='/'.$dirname.$filename;
//        移动文件到指定的目录里面去
                if($img->saveAs(\Yii::getAlias('@webroot').$files,0)){
                    //上传成功
                    //=====上传文件至七牛云=====
                    $accessKey ="kBZLU93-piQoJo9bgv38ProxOPDm5RS99s9CsaCd";
                    $secretKey = "B0sz2q1Wn-1RAfqb5UD1r0n-qx1aE_80SdCTCS5v";
                    $bucket = "kailin";
                    $domain = "p1aysf3e3.bkt.clouddn.com";
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
                        //上传成功,
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
    //测试七牛云
    public function actionQiniu(){

        // 需要填写你的 Access Key 和 Secret Key
                $accessKey ="kBZLU93-piQoJo9bgv38ProxOPDm5RS99s9CsaCd";
                $secretKey = "B0sz2q1Wn-1RAfqb5UD1r0n-qx1aE_80SdCTCS5v";
                $bucket = "kailin";
                // 构建鉴权对象
                $auth = new Auth($accessKey, $secretKey);

                // 生成上传 Token
                $token = $auth->uploadToken($bucket);
                $filename ='/Uploads/brands/20171221/1.jpg';
                // 要上传文件的本地路径backend/web/Uploads/brands/20171221/1.jpg
                $filePath = \Yii::getAlias('@webroot').$filename;

                // 上传到七牛后保存的文件名
                $key = $filename;

                // 初始化 UploadManager 对象并进行文件的上传。
                $uploadMgr = new UploadManager();

                // 调用 UploadManager 的 putFile 方法进行文件的上传。
                list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
                echo "\n====> putFile result: \n";
                if ($err !== null) {
                    //错误的
                    var_dump($err);
                } else {
                    //上传成功,
                    var_dump($ret);
                }

    }
    //品牌的修改
    public function actionUpdate($id)
    {
        //创建提交对象
        $request = \Yii::$app->request;
        //根据id显示查询数据表
        $model = Brands::findOne(['id'=>$id]);
        if($request->isPost){
            //加载表单数据
            $model->load($request->post());
            //创建图片的上传对象
//            $model->img= UploadedFile::getInstance($model,'img');
            if($model->validate()){
                //图片上传的路径,
//                $dirname = "Uploads/".\Yii::$app->controller->id.'/'.date('Ymd')."/";
//                if(!is_dir($dirname)){
//                    //如果目录不存在,就创建目录
//                    mkdir($dirname,0777,true);
//                }
                //处理文件名
//                $filename = uniqid().'.'.$model->img->extension;
//                $files='/'.$dirname.$filename;
                //移动文件到指定的目录里面去
//                if($model->img->saveAs(\Yii::getAlias('@webroot').$files)){
//                    $model->logo = $files;
//                }
                $model->save();
                //提示信息
                \Yii::$app->session->setFlash('success','修改品牌成功!');
                //跳转值品牌表首页
                return $this->redirect(['brands/index']);
            }
        }
        //显示页面
        return $this->render('add',['model'=>$model]);
    }
    //品牌的删除
    public function actionDelete($id)
    {
        //这是一个假的删除>>更改该条数据的状态值
        Brands::updateAll(['status'=>-1],['id'=>$id]);

    }
    //权限控制
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'except'=>['uploader'],
            ]
        ];
    }
}
