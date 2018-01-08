<!-- 顶部导航 start -->
<style type="text/css">
    .background-color: {
    }
</style>
<div class="topnav">
    <div class="topnav_bd w1210 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li><big><?=isset(Yii::$app->user->identity->username)?Yii::$app->user->identity->username:''?></big>您好，欢迎来到京西！<?php if(isset(Yii::$app->user->identity->username)){
                        echo '[<a href="'.\yii\helpers\Url::to(['site/user-logout']).'">注销</a>]';
                    }else{
                        echo '[<a href="'.\yii\helpers\Url::to(['site/user-login']).'">登录</a>]';
                        echo '[<a href="'.\yii\helpers\Url::to(['site/register']).'">免费注册</a>]';
                    }?></li>
<!--                <li class="line">|</li>-->
<!--                <li>我的订单</li>-->
<!--                <li class="line">|</li>-->
<!--                <li>客户服务</li>-->

            </ul>
        </div>
    </div>
</div>
<!-- 顶部导航 end -->

<div style="clear:both;"></div>

<!-- 头部 start -->
<div class="header w1210 bc mt15">
    <!-- 头部上半部分 start 包括 logo、搜索、用户中心和购物车结算 -->
    <div class="logo w1210">
        <h1 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h1>
        <!-- 头部搜索 start -->
        <div class="search fl">
            <div class="search_form">
                <div class="form_left fl"></div>
                <form action="<?=\yii\helpers\Url::to(['site/search'])?>"  method="get" class="fl">
                    <input type="text" name="keywords" class="txt" value="请输入商品关键字" /><input type="submit" class="btn" value="搜索" />
                </form>
                <div class="form_right fl"></div>
            </div>

            <div style="clear:both;"></div>

            <div class="hot_search">
                <strong>热门搜索:</strong>
                <a href="">D-Link无线路由</a>
                <a href="">休闲男鞋</a>
                <a href="">TCL空调</a>
                <a href="">耐克篮球鞋</a>
            </div>
        </div>
        <!-- 头部搜索 end -->

        <!-- 用户中心 start-->
        <div class="user fl">
            <dl>
                <dt>
                    <em></em>
                    <a href="">用户中心</a>
                    <b></b>
                </dt>
                <dd>
                    <div class="uclist mt10">
                        <ul class="list1 fl">
                            <li><a href="">用户信息></a></li>
                            <li><a href="<?=\yii\helpers\Url::to(['order/orderlist'])?>">我的订单></a></li>
                            <li><a href="<?=\yii\helpers\Url::to(['site/address-add'])?>">收货地址></a></li>
                            <li><a href="">我的收藏></a></li>
                        </ul>

                        <ul class="fl">
                            <li><a href="">我的留言></a></li>
                            <li><a href="">我的红包></a></li>
                            <li><a href="">我的评论></a></li>
                            <li><a href="">资金管理></a></li>
                        </ul>

                    </div>
                    <div style="clear:both;"></div>
                    <div class="viewlist mt10">
                        <h3>最近浏览的商品：</h3>
                        <ul>
                            <li><a href=""><img src="/images/view_list1.jpg" alt="" /></a></li>
                            <li><a href=""><img src="/images/view_list2.jpg" alt="" /></a></li>
                            <li><a href=""><img src="/images/view_list3.jpg" alt="" /></a></li>
                        </ul>
                    </div>
                </dd>
            </dl>
        </div>
        <!-- 用户中心 end-->

        <!-- 购物车 start -->
        <div class="cart fl">
            <dl>
                <dt>
                    <a href="<?=\yii\helpers\Url::to(['site/cart'])?>">去购物车结算</a>
                    <b></b>
                </dt>
                <dd class="background-color:#b3eef5;">
                    <div class="prompt">
                        <?php $carts = \frontend\models\Cart::find()->where(['member_id'=>Yii::$app->user->id])->all();
                        if(isset($carts)){
                            foreach($carts as $cart){
                                $goods = \frontend\models\Goods::findOne(['id'=>$cart->goods_id]);
                                echo '<tr><td><img src="'.$goods['logo'].'" alt="goods_logo" width="100px"></td><td>'.$goods['name'].'</td></tr>';
                            }
                        }else{
                            echo "购物车中还没有商品,快去选购吧!";
                        }?>

                    </div>
                </dd>
            </dl>
        </div>
        <!-- 购物车 end -->
    </div>
    <!-- 头部上半部分 end -->

    <div style="clear:both;"></div>

    <!-- 导航条部分 start -->
    <div class="nav w1210 bc mt10">
        <!--  商品分类部分 start-->
        <div class="category fl cat1">
            <div class="cat_hd off">  <!-- 注意，首页在此div上只需要添加cat_hd类，非首页，默认收缩分类时添加上off类，并将cat_bd设置为不显示(加上类none即可)，鼠标滑过时展开菜单则将off类换成on类 -->
                <h2>全部商品分类</h2>
                <em></em>
            </div>
            <div class="cat_bd none"><?=\backend\models\GoodsCategory::getCategoryies();?></div>
        </div>
        <!--  商品分类部分 end-->

        <div class="navitems fl">
            <ul class="fl">
                <li class="current"><a href="">首页</a></li>
                <li><a href="">电脑频道</a></li>
                <li><a href="">家用电器</a></li>
                <li><a href="">品牌大全</a></li>
                <li><a href="">团购</a></li>
                <li><a href="">积分商城</a></li>
                <li><a href="">夺宝奇兵</a></li>
            </ul>
            <div class="right_corner fl"></div>
        </div>
    </div>
    <!-- 导航条部分 end -->
</div>
<!-- 头部 end-->

<div style="clear:both;"></div>