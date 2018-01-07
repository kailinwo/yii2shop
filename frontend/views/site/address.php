<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>收货地址</title>
    <link rel="stylesheet" href="/style/base.css" type="text/css">
    <link rel="stylesheet" href="/style/global.css" type="text/css">
    <link rel="stylesheet" href="/style/header.css" type="text/css">
    <link rel="stylesheet" href="/style/home.css" type="text/css">
    <link rel="stylesheet" href="/style/address.css" type="text/css">
    <link rel="stylesheet" href="/style/bottomnav.css" type="text/css">
    <link rel="stylesheet" href="/style/footer.css" type="text/css">

    <script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/js/header.js"></script>
    <script type="text/javascript" src="/js/home.js"></script>
    <script type="text/javascript" src="/js/jsAddress.js"></script>
    <script src="/layer/layer.js"></script>
</head>
<body>
<!-- 顶部导航 start -->
<?php require "../views/site/topnav.php";?>
<!-- 顶部导航 end -->

<!-- 页面主体 start -->
<div class="main w1210 bc mt10">
    <div class="crumb w1210">
        <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
    </div>

    <!-- 左侧导航菜单 start -->
    <div class="menu fl">
        <h3>我的XX</h3>
        <div class="menu_wrap">
            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                <dd><b>.</b><a href="">账户余额</a></dd>
                <dd><b>.</b><a href="">消费记录</a></dd>
                <dd><b>.</b><a href="">我的积分</a></dd>
                <dd><b>.</b><a href="">收货地址</a></dd>
            </dl>

            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">返修/退换货</a></dd>
                <dd><b>.</b><a href="">取消订单记录</a></dd>
                <dd><b>.</b><a href="">我的投诉</a></dd>
            </dl>
        </div>
    </div>
    <!-- 左侧导航菜单 end -->

    <!-- 右侧内容区域 start -->
    <div class="content fl ml10">
        <div class="address_hd">
            <h3>收货地址薄</h3>
            <?php foreach($adinfo as $row):?>
            <dl id="<?=$row->id?>">
                <dt><?=$row->name.' '.$row->cmbProvince.' '.$row->cmbCity.' '.$row->cmbArea.' '.$row->address.' '.$row->tel?></dt>
                <dd>
                    <a href="<?=\yii\helpers\Url::to(['site/address-update','id'=>$row->id])?>" id="adupdate">修改</a>
                    <a href="" id="addelete">删除</a>
                    <?php if($row->status == 0):?>
                        <a href="javascript:void(0)" class="adedit" data-id="<?=$row->id?>">设为默认地址</a>
                    <?php else:?>
                       默认地址
                    <?php endif;?>
                </dd>
            </dl>
            <?php endforeach;?>
        </div>

        <div class="address_bd mt10">
            <h4>新增收货地址</h4>
            <form  id='form' action="" name="address_form" method="post">
                <ul>
                    <li>
                        <label for=""><span>*</span>收 货 人：</label>
                        <input type="text" name="name" class="txt" />
                    </li>
                    <li>
                        <label for=""><span>*</span>所在地区：</label>
                        <select id="cmbProvince" name="cmbProvince">
                        </select>

                        <select id="cmbCity" name="cmbCity">
                        </select>

                        <select id="cmbArea" name="cmbArea">
                        </select>
                    </li>
                    <li>
                        <label for=""><span>*</span>详细地址：</label>
                        <input type="text" name="address" class="txt address"  />
                    </li>
                    <li>
                        <label for=""><span>*</span>手机号码：</label>
                        <input type="text" name="tel" class="txt" />
                    </li>
                    <li>
                        <label for="">&nbsp;</label>
                        <input type="checkbox" name="status" class="check" value="1"/>设为默认地址
                    </li>
                    <li>
                        <label for="">&nbsp;</label>
                        <input type="button" id="submit" class="btn" value="保存" />
                    </li>
                </ul>
            </form>
        </div>

    </div>
    <!-- 右侧内容区域 end -->
</div>
<!-- 页面主体 end-->

<div style="clear:both;"></div>

<!-- 底部导航 start -->
<div class="bottomnav w1210 bc mt10">
    <div class="bnav1">
        <h3><b></b> <em>购物指南</em></h3>
        <ul>
            <li><a href="">购物流程</a></li>
            <li><a href="">会员介绍</a></li>
            <li><a href="">团购/机票/充值/点卡</a></li>
            <li><a href="">常见问题</a></li>
            <li><a href="">大家电</a></li>
            <li><a href="">联系客服</a></li>
        </ul>
    </div>

    <div class="bnav2">
        <h3><b></b> <em>配送方式</em></h3>
        <ul>
            <li><a href="">上门自提</a></li>
            <li><a href="">快速运输</a></li>
            <li><a href="">特快专递（EMS）</a></li>
            <li><a href="">如何送礼</a></li>
            <li><a href="">海外购物</a></li>
        </ul>
    </div>


    <div class="bnav3">
        <h3><b></b> <em>支付方式</em></h3>
        <ul>
            <li><a href="">货到付款</a></li>
            <li><a href="">在线支付</a></li>
            <li><a href="">分期付款</a></li>
            <li><a href="">邮局汇款</a></li>
            <li><a href="">公司转账</a></li>
        </ul>
    </div>

    <div class="bnav4">
        <h3><b></b> <em>售后服务</em></h3>
        <ul>
            <li><a href="">退换货政策</a></li>
            <li><a href="">退换货流程</a></li>
            <li><a href="">价格保护</a></li>
            <li><a href="">退款说明</a></li>
            <li><a href="">返修/退换货</a></li>
            <li><a href="">退款申请</a></li>
        </ul>
    </div>

    <div class="bnav5">
        <h3><b></b> <em>特色服务</em></h3>
        <ul>
            <li><a href="">夺宝岛</a></li>
            <li><a href="">DIY装机</a></li>
            <li><a href="">延保服务</a></li>
            <li><a href="">家电下乡</a></li>
            <li><a href="">京东礼品卡</a></li>
            <li><a href="">能效补贴</a></li>
        </ul>
    </div>
</div>
<!-- 底部导航 end -->

<div style="clear:both;"></div>
<!-- 底部版权 start -->
<div class="footer w1210 bc mt10">
    <p class="links">
        <a href="">关于我们</a> |
        <a href="">联系我们</a> |
        <a href="">人才招聘</a> |
        <a href="">商家入驻</a> |
        <a href="">千寻网</a> |
        <a href="">奢侈品网</a> |
        <a href="">广告服务</a> |
        <a href="">移动终端</a> |
        <a href="">友情链接</a> |
        <a href="">销售联盟</a> |
        <a href="">京西论坛</a>
    </p>
    <p class="copyright">
        © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
    </p>
    <p class="auth">
        <a href=""><img src="/images/xin.png" alt="" /></a>
        <a href=""><img src="/images/kexin.jpg" alt="" /></a>
        <a href=""><img src="/images/police.jpg" alt="" /></a>
        <a href=""><img src="/images/beian.gif" alt="" /></a>
    </p>
</div>
<!-- 底部版权 end -->

<script type="text/javascript">
    addressInit('cmbProvince', 'cmbCity', 'cmbArea');
    //地址添加
    $('#form').on('click','#submit',function () {
        var info = $('#form').serializeArray();//序列化为数组发送
        var index = layer.load(1, {shade: [0.5,'#999']}); //0代表加载的风格，支持0-2
        $.post("<?=\yii\helpers\Url::to(['site/address-add'])?>",info,function (data) {
            if(data.status == 1){
//                alert(data.msg);
                layer.msg(data.msg,{time:1500},function () {
                    layer.close(index);
                    window.location.reload();
                })
            }else{
                layer.msg(data.msg)
            }
        }
            //返回来的数据要拼装成HTML代码格式,追加到地址簿里面去
//            console.debug($.parseJSON(data));
//            var addressinfo = eval("("+data+")");
//            console.debug(data);
//            if(data){
//                var str = data.address.status === 1 ? '默认地址' : '设为默认地址';
//                var html= '';
//                html +='<dl>';
//                html +='<dt>'+data.address.name+' '+data.address.cmbProvince+' '+data.address.cmbCity+' '+data.address.cmbArea+' '+data.address.address+' '+data.address.tel+'</dt>';
//                html +='<dd>';
//                html +='<a href="" id="adupdate">修改</a> ';
//                html +=' <a href="" id="addelete">删除</a> ';
//                html +=' <a href="javascript:void(0)" class ="adedit">'+str+'</a>';
//                html +='</dd>';
//                html +='</dl>';
//                $('.address_hd').append(html);
//            }
//        },'json');

        );
    });
    //地址删除
    $('.address_hd').on('click','#addelete',function () {
        var dl = $(this).closest('dl');
        $.get("<?=\yii\helpers\Url::to(['site/address-delete'])?>",{id:dl.attr('id')},function () {
            dl.fadeOut();
        })
    });

    //修改默认地址
    $('.adedit').on('click',function(){
//        var dl = $(this).closest('dl');
        var id = $(this).attr('data-id');
        var index = layer.load(0, {shade: [0.5,'#999']}); //0代表加载的风格，支持0-2
        $.get("<?=\yii\helpers\Url::to(['site/address-edit'])?>",{id:id},function (res) {
            //后端更改成功之后,就更改前端的"设置默认地址"的按钮
//                $(this).text('默认地址');
           layer.msg(res.msg,{time:1500},function () {
               layer.close(index);
               window.location.reload();
           });
        });
    })

</script>

</body>
</html>