<?php
/**
 * Created by PhpStorm.
 * User: 王凯林
 * Date: 18/1/7
 * Time: 18:21
 */

namespace frontend\controllers;


use yii\db\ActiveRecord;

class TestController extends ActiveRecord
{

    public function actionOrder()
    {
        //判断用户是否登录
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['member/login']);
        } else {
            //登录成功  判断是不是post提交
            $request = new Request();
            if ($request->isPost) {
                $model = new Order();
                $model->load($request->post(), '');
//                var_dump($model->address_id);die;
//                $model->address_id = //地址id
//                $model->delivery_id = //配送发送id
                $address = Address::findOne(['id' => $model->address_id]);
                if (!$address){
                    return $this->jump(3,'/address/index.html','收获地址不存在,请填写有效的收货地址');
                }
//                $carts = Cart::find()->where(['member_id' => \Yii::$app->user->id])->all();
                $transaction = \Yii::$app->db->beginTransaction();//开启事务
//                var_dump($carts);die;
                try {
                    $carts = Cart::find()->where(['member_id' => \Yii::$app->user->id])->all();
                    foreach ($carts as $cart) {//遍历购物车
                        $num = Goods::find()->where(['id' => $cart->goods_id])->one();
                        if ($num->stock >= $cart->amount) {
                            $model->member_id = \Yii::$app->user->id;
                            $model->name = $address->username;
                            $model->province = $address->province;
                            $model->city = $address->city;
                            $model->area = $address->county;
                            $model->address = $address->address;
                            $model->tel = $address->tel;
                            $model->delivery_name = Order::$delivery[$model->delivery_id][0];
                            $model->delivery_price = Order::$delivery[$model->delivery_id][1];
//                var_dump($model->payment_id);die;
                            $model->payment_name = Order::$pay[$model->payment_id][0];
                            $aa = 0;
                            $aa += $num->shop_price * $cart->amount;
                            $model->total = $aa;
                            $model->status = 1;
                            $model->create_time = time();
                            if ($model->validate()) {
                                $model->save();
                                $id = \Yii::$app->db->getLastInsertID();
                                Goods::updateAll(['stock'=>$num->stock-$cart->amount],['id'=>$cart->goods_id]);
                                Cart::deleteAll(['id'=>$cart->id]);
                                $ordergoods = new OrderGoods();
                                $ordergoods->order_id = $id;
                                $ordergoods->goods_id = $num->id;
                                $ordergoods->goods_name = $num->name;
                                $ordergoods->logo = $num->logo;
                                $ordergoods->price = $num->shop_price;
                                $ordergoods->amount = $cart->amount;
                                $ordergoods->total = $num->shop_price * $cart->amount;
                                $ordergoods->save();
                                $transaction->commit();
                            }
                        }else{
                            throw new Exception('商品的库存不足');//抛出异常
                        }
                    }
                    $this->tishi(4,'/goods/cart.html');
                } catch (Exception $e) {//捕获异常
                    $transaction->rollBack();//事务回滚
                    $this->jump(5,'/goods/cart.html',$num->name.'商品的库存不足');
                }
            }else{
                $address = Address::find()->where(['session_id' => \Yii::$app->user->id])->all();//地址的处理
                $carts = Cart::find()->where(['member_id' => \Yii::$app->user->id])->all();
                $arr = ArrayHelper::map($carts, 'goods_id', 'goods_id');
                $cart = ArrayHelper::map($carts, 'goods_id', 'amount');
                $goods = Goods::find()->where(['id' => $arr])->all();
                $num = Goods::find()->where(['id' => $arr])->count();//商品的件数
                return $this->render('order', ['address' => $address, 'goods' => $goods, 'cart' => $cart, 'num' => $num]);
            }
        }
    }
}