<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 18/1/6
 * Time: 13:58
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    //创建配送方式的数组
    public static $deliveries =[
        1=>['顺丰',25,'速度最快,服务最快,满意度最高'],
        2=>['EMS',22,'速度一般,服务一般,满意度一般'],
        3=>['圆通',12,'速度一般,服务一般,满意度一般'],
        4=>['其它',10,'速度一般,服务一般,满意度一般'],
    ];
    //创建支付方式的数组
    public static $payments =[
        1=>['支付宝','欢迎使用支付宝支付,最快最安全'],
        2=>['银行卡','欢迎使用银联的云闪付'],
        3=>['微信','欢迎使用微信支付'],
    ];
    //配置数据表里面没有的数据
    public $address_id;
    //配置验证规则
    public function rules()
    {
        return [
            [['address_id','member_id','name','province','city','area','address','tel','delivery_id','delivery_name','delivery_price','payment_id','payment_name','total','create_time'],'required'],
        ];
    }
}