<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:71:"/var/www/html/redpack/public/../application/index/view/index/index.html";i:1511355610;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
    <title>新年红包</title>
    <link href="https://cdn.bootcss.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="__STATIC__/css/csshake.min.css">
    <link rel="stylesheet" href="__STATIC__/css/style.css">

    <style>
        .open_bef{background-image: url(__STATIC__/img/red-w.png);}
        .open_aft{background-image: url(__STATIC__/img/red-y.png);}
    </style>
    <script src="__STATIC__/js/zepto.min.js"></script>
    <script src="__STATIC__/js/angular.min.js"></script>
</head>
<body ng-app="redApp" ng-controller="redCtrl">
<!-- 红包 -->
<div class="red" ng-class="{true:'shake shake-slow',false:''}[appearClass]"><!-- shake-chunk -->
    <span ng-class="{open_bef:isbefore,open_aft:isafter}"></span>
    <button class="redbutton" ng-show="mask" ng-click="open_btn()">拆红包</button>
    <!--判断-->
    <div class="red-jg" ng-hide="mask">
        <h1>恭喜您！</h1>
        <h5>手气不错，获得现金<span style="font-size: 30px;color: #FDC339;"><?php echo $getmoney; ?>元</span></h5>
    </div>

</div>
<!-- End 红包 -->
<!-- 按钮 -->
<div class="t-btn" ng-hide="mask">
    <button id="getMoney_btn">立即领取</button>
</div>
<!-- End 按钮 -->
<div class="event_detail">
    <h4 style="margin-bottom: 10px;color:dimgrey">活动详情</h4>
    <hr style="height:1px;border:none;border-top:1px dashed gray;" />
    <ol>
        <li>活动时间：2017年1月1日10:00至2017年1月1日14:00；</li>
        <li>活动对象：使用微信公众号进行验证操作的所有用户；</li>
        <li>红包规则：用户在微信公众号端完成验证操作后，可获得领红包机会；红包领取为四个整点11:00,12:00,13:00,14:00，在红包领取的5分钟内可点击拆红包，同一个微信号每个整点仅限一次拆红包机会；</li>
        <li>红包金额：分为5元，10元，20元，50元和100元五档，概率随机，全凭运气哦，新年新气象！</li>
        <li>红包领取流程：用户拆到红包后，红包页面会弹出拆到的红包金额，点击立即领取后，微信公众号首页会推送红包消息，拆开红包即可获得相应金额；若用户未及时拆开推送的红包，红包将不翼而飞。</li>
        <li>本活动最终解释权归我公司所有。</li>
    </ol>
</div>

<!--隐藏表单-->
<div class="userForm">
    <form action="<?php echo url('Index/saveUserToDb'); ?>" method="post">
        <div class="form-group">
            <h3 align="center">请填写领奖人信息</h3>
            <input name="name" type="text" class="form-control"  placeholder="领奖人姓名">

        </div>
        <div class="form-group">
            <input name="tel" type="number" class="form-control"  placeholder="领奖人电话">
        </div>
        <input type="hidden" name="openid" value="<?php echo \think\Session::get('curUser'); ?>">
        <input type="hidden" name="luck_money" value="<?php echo $getmoney; ?>">

        <button type="submit" name="submit" class="btn btn-large btn-success">确认领奖人信息</button>
    </form>
</div>


<embed src="__STATIC__/asset/music.mp3" autostart=true loop=true starttime="00:20" width="0" height="0"></embed>
<script src="__STATIC__/js/redApp.js"></script>
<script src="__STATIC__/js/redCtrl.js"></script>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script>
    $('.userForm').css('display','none');
    $('#getMoney_btn').click(function () {
        $('.userForm').css('display','block');
    });

    //TODO:表单验证
</script>
</body>
</html>