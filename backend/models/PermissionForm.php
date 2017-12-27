<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 17/12/26
 * Time: 21:25
 */
namespace backend\models;
use yii\base\Model;
use yii\rbac\Permission;

class PermissionForm extends Model
{
    public $name;
    public $description;
    //场景
    const SCENARIO_PERMISSION_ADD = 'permission_add';
    const SCENARIO_PERMISSION_UPDATE= 'permission_update';
    //验证规则
    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['name','weiyi','on'=>self::SCENARIO_PERMISSION_ADD],//只有在使用了这个场景之后才使用
            //修改时候验证权限名称
            ['name','validatename','on'=>self::SCENARIO_PERMISSION_UPDATE],
        ];
    }
    //添加权限的唯一性验证
    public function weiyi(){
        $authManager = \Yii::$app->authManager;
        $dbname = $authManager->getPermission($this->name);
        if($dbname){
            $this->addError('name','该权限已存在');
        }
    }
    //验证修改时候名字
    public function validatename(){
        $authManager = \Yii::$app->authManager;
        //名称是否修改
        $oldName = \Yii::$app->request->get('name');
        //如果名称修改,且修改后的名字之前已有的话,就提示错误
        if($oldName !=$this->name){
            $permission = $authManager->getPermission($this->name);
            if($permission){
                $this->addError('name','该权限已经存在!');
            }
        }
    }
    //字段名字中文
    public function attributeLabels(){
        return [
            'name'=>'权限名(路由)',
            'description'=>'权限描述',
        ];
    }
    //权限新增
    public function insert(){
        $authManager = \Yii::$app->authManager; //创建一个rbac的对象;
        $Permission = new Permission();//新建一个权限
        $Permission->name = $this->name; //赋值name
        $Permission->description = $this->description;//赋值description
        $authManager->add($Permission);//保存
    }
    //权限更新
    public function update($name){
        $authManager = \Yii::$app->authManager;//创建新的管理对象
        $permission = new Permission(); //创建权限对象
        $permission->name = $this->name;// 赋新值>>名称
        $permission->description=$this->description;//赋新的描述值
        $authManager->update($name,$permission); //要旧的名字,跟新的权限对象
    }
}