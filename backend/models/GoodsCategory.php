<?php

namespace backend\models;
use creocoder\nestedsets\NestedSetsBehavior;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "goods_category".
 *
 * @property integer $id
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property integer $parent_id
 * @property string $intro
 */
class GoodsCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_category';
    }
    const GOODS_CATEGORY_UPDATE ='goods_category_update';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['name','parent_id'],'required'],
//            [['tree', 'lft', 'rgt', 'depth','parent_id'], 'integer'],
//            [['intro'], 'string'],
//            [['name'], 'string', 'max' => 50],
//            ['parent_id','validatePid'],
            [['name','parent_id'],'required'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 50],
            ['parent_id','validatePid','on'=>self::GOODS_CATEGORY_UPDATE],
        ];
    }
    public function validatePid(){
        //不能修改为自己的子孙节点的子节点;
        $parent = GoodsCategory::findOne(['id'=>$this->parent_id]);
        //处理验证不通过的情况
        if($parent->isChildOf($this)){
            //给模型添加错误信息
            $this->addError('parent_id','不能修改为子孙节点的子节点!');
        }
    }

    //查询数据显示到那个页面上!
    public static function getNodes(){
        $nodes = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        array_unshift($nodes,['id'=>'0','parent_id'=>'0','name'=>'顶级分类']);//解决想传高级分类的尴尬境地,给数据源添加一个id为0,parent_id为0;
        return Json::encode($nodes);
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tree' => 'Tree',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'name' => '分类名',
            'parent_id' => '上级分类',
            'intro' => '简介',
        ];
    }

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                 'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }

}
