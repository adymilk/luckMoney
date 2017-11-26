<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"D:\phpStudy\WWW\luckMoney\public/../application/index\view\index\activityisover.html";i:1511596157;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
    <title>活动结束啦</title>
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
    <span class="open_aft"></span>
    <!--判断-->
    <div class="red-jg" ng-show="mask">
        <h1>来晚啦！</h1>
        <h5>活动已经结束！</h5>
    </div>

</div>
<!-- End 红包 -->
<div class="event_detail">
    <h4 style="margin-bottom: 10px;color:dimgrey"> &nbsp;活动详情</h4>
    <hr style="height:1px;border:none;border-top:1px dashed gray;" />
    <ol>
        <li>红包规则：同一个微信用户只能领取一个</li>
        <li>红包金额：最低1元，最高不超过100，概率随机！</li>
        <li>红包领取流程：用户拆到红包后，红包页面会弹出拆到的红包金额，点击立即领取后，微信公众号首页会推送红包消息，拆开红包即可获得相应金额；若用户未及时拆开推送的红包，红包将不翼而飞。</li>
        <li>本活动最终解释权归我公司所有。</li>
    </ol>
</div>

<script src="__STATIC__/js/redApp.js"></script>
<script src="__STATIC__/js/redCtrl.js"></script>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script>
    $('.userForm').css('display','none');
    function showForm() {
        $('.userForm').css('display','block');
    }

    //TODO:表单验证
    function checkForm() {
        if ($('#name').val() === ''){
            alert('名字不能为空！');
        }else if ($('#tel').val() === ''){
            alert('电话不能为空！');
        }else $.ajax({
            type: 'POST',
            url: "<?php echo url('Index/saveUserToDb'); ?>",
            dataType: 'json',
            data: {
                name: $('#name').val(),
                tel: $('#tel').val(),
                luck_money: $('#luck_money').val(),
                openid: $('#openid').val()
            },
            beforeSend:function()
            { //触发ajax请求开始时执行
                $('#sendAjaxBtn').text('审核中...');
                $('#sendAjaxBtn').attr('onclick','javascript:void();');//改变提交按钮上的文字并将按钮设置为不可点击
            },
            success: function (data) {
                $('#getMoney_btn').html(data.msg);
                $('.userForm').css('display','none');
                $('#getMoney_btn').removeAttr('onclick');
                console.log(data.msg);
            },
            error: function (data) {
                //TODO: 因成功返回数据，依然执行error。这里暂时直接写死
                $('#getMoney_btn').html('红包已发送！请到公众号内领取！');
                $('.userForm').css('display','none');
                $('#getMoney_btn').removeAttr('onclick');
                console.log(data);
            },
        });
    }

</script>
</body>
</html>