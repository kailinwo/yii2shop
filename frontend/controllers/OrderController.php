<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 18/1/6
 * Time: 13:12
 */

namespace frontend\controllers;


use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Goods;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\web\Controller;
use yii\helpers\ArrayHelper;

class OrderController extends Controller
{
    //关闭csrf验证
    public $enableCsrfValidation=false;
    //跳转方法
    public function jump($jump,$url,$stu="成功"){
        require '../views/site/jump.php';//根据相对路径来找!可能布置到linux上面有路径的问题!
        header('Refresh:2;url='.$url);die;
    }
    //订单页面显示
    public function actionOrder(){
        //判断用户的登录状态
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['site/user-login']);
        }else{
            //将用户的地址查询到到页面显示
            $address=Address::find()->where(['member_id'=>\Yii::$app->user->id])->all();
            //将用户的购物车表里面的信息展示到订单页面
            $users = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
            $ids = ArrayHelper::map($users,'goods_id','goods_id');
            $amount = ArrayHelper::map($users,'goods_id','amount');
            $goods = Goods::find()->where(['in','id',$ids])->all();
            $order = new Order();
            if(\Yii::$app->request->isPost){
                $order->load(\Yii::$app->request->post(),'');
//                var_dump($model->delivery_id);die('查看订单表的数据提交');
                //根据用户的地址来找到地址信息
                $address = Address::findOne(['id'=>$order->address_id]);
                $order->member_id = \Yii::$app->user->id;
                $order->name = $address['name'];
                $order->province = $address['cmbProvince'];
                $order->city = $address['cmbCity'];
                $order->area = $address['cmbArea'];
                $order->address = $address['address'];
                $order->tel = $address['tel'];
                //配送方式的数据绑定>>由于是自定义在模型的数组,要使用这个id得到数组里面的数据
                $order->delivery_name = Order::$deliveries[$order->delivery_id][0];
                $order->delivery_price = Order::$deliveries[$order->delivery_id][1];
                //支付方式的数据绑定>>同上
                $order->payment_name = Order::$payments[$order->payment_id][0];
                $order->status = 1;
                $order->total = 0;
                $order->create_time = time();
                //>>开启事务
                $trans = \Yii::$app->db->beginTransaction();
                try {
                    if($order->validate()){
                        $order->save();
                    }
                    //>>先通过用户的id来找到该用户购物车里面的所有的商品:
                    $carts = Cart::find()->where(['member_id' => \Yii::$app->user->id])->all();
                    //遍历得到每个商品的数量
                    foreach ($carts as $cart) {
                        //根据用户的购物车中的goods_id得到goods数据表里面对应的商品信息
                        $goods = Goods::find()->where(['id' => $cart->goods_id])->one();
                        //判断是否商品数据表中的每条库存是否足够用来下单
                        if ($goods->stock >= $cart->amount) {
                            //商品的库存足够的情况>>>往Oder_goods关系表里面写入数据
                            $orderGoods = new OrderGoods();
                            $orderGoods->order_id =$order->id;
                            $orderGoods->goods_id = $goods->id;
                            $orderGoods->goods_name = $goods->name;
                            $orderGoods->logo = $goods->logo;
                            $orderGoods->price = $goods->shop_price;
                            $orderGoods->amount = $cart->amount;
                            $orderGoods->total = $goods->shop_price * $cart->amount;
                            $orderGoods->member_id = \Yii::$app->user->id;
                            //order_goods关系表的保存
                            if($orderGoods->validate()){
                                $orderGoods->save();
                            }
                            //扣减库存>>处理之后将数据写回去
                            $goods->stock -= $cart->amount;
                            $goods->save(false);
                            //保存购物车的商品总价
                            $order->total += $orderGoods->total;
                        } else {
                            //商品的库存不足以本次的订单数量 >>抛出异常
                            throw new Exception('商品的库存不足');
                        }
                    }
                    //处理运费
                    $order->total += $order->delivery_price;
                    $order->save();
                    //下单成功之后要将当前用户的购物车中的商品删除
                    Cart::deleteAll(['member_id'=>\Yii::$app->user->id]);
                    //提交事务>>>
                    $trans->commit();
                    //order表和order_goods中间表两个数据都写入成功了,即提示信息跳转!
                   return $this->redirect(['order/success']);
                }catch(Exception $e){
                    $trans->rollBack();
                    $this->jump('提交订单','/site/cart.html','失败,'.$goods->name.'库存不足!');
                }
            }
            //加载表单视图
            return $this->render('order',['address'=>$address,'goods'=>$goods,'amount'=>$amount]);

        }
    }
    //提交订单成功
    public function actionSuccess(){
        return $this->render('success');
    }
    //自己的订单列表
    public function actionOrderlist(){
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
        }else{
            //根据用户的id去order_goods表中查找用户的商品信息!
            $models = OrderGoods::find()->where(['member_id' => \Yii::$app->user->id])->all();
            $a = 0;
            $b = 0;
            $c = 0;
            $d = 0;
            //得到order_goods表中所有的用户的商品信息,遍历得到order_id,再根据order_id查找order表里面的status!
            foreach ($models as $model){
                $time = Order::findOne(['id'=>$model->order_id]);
                if ($time['status']===1){$a++;};if($time['status']===4){$b++;}if ($time['status']===3){$c++;}if ($time['status']===2){$d++;}
            }
            return $this->render('order_list', ['models' => $models,'a'=>$a,'b'=>$b,'c'=>$c,'d'=>$d]);
        }
    }
}