<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/31
 * Time: 10:00
 */
namespace backend\filters;
use yii\base\ActionFilter;
use yii\web\HttpException;

class RbacFilter extends ActionFilter{
    public function beforeAction($action)
    {
//        return \Yii::$app->user->can($action->uniqueId);
        //判断用户是否有操作的权限
        if(!\Yii::$app->user->can($action->uniqueId)){
            //如果用户没有登录,就引导用户去登陆
            if(\Yii::$app->user->isGuest){
                //跳转到用户的登录界面
                return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();
            }
            throw new HttpException(403,'对不起,您没有足够的权限执行该操作!');
        }
        return true;
    }
}