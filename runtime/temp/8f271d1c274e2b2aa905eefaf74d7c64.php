<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:75:"D:\phpStudy\WWW\luckMoney\public/../application/index\view\index\index.html";i:1511665952;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
    <title>盛腾家装送红包啦</title>
    <link rel="stylesheet" href="__STATIC__/css/bootstrap.min.css">
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

<!--隐藏表单-->
<div class="userForm" style="display: none">
    <form>
        <h3 align="center">请填写领奖人信息</h3>
        <div class="form-group">
            <input name="name" id="name" type="text" class="form-control"  placeholder="领奖人姓名">
        </div>
        <div class="form-group">
            <input name="tel" id="tel" type="number" class="form-control"  placeholder="领奖人电话">
        </div>
        <input type="hidden" name="openid" id="openid" value="<?php echo \think\Session::get('curUser'); ?>">
        <input type="hidden" name="luck_money" id="luck_money" value="<?php echo $luck_money; ?>">

        <button id="sendAjaxBtn" type="submit" name="submit" onclick="checkForm()" class="btn btn-large btn-success">确认领奖人信息</button>
    </form>
</div>

<!-- 红包 -->
<div class="red" ng-class="{true:'shake shake-slow',false:''}[appearClass]"><!-- shake-chunk -->
    <span ng-class="{open_bef:isbefore,open_aft:isafter}"></span>
    <button class="redbutton" ng-show="mask" ng-click="open_btn()" onclick="showLuckMoney()">拆红包</button>
    <!--判断-->
    <div class="red-jg" ng-hide="mask">
        <h1>恭喜您！</h1>
        <h5>手气不错，获得现金 <span id="LuckMoney" style="font-size: 30px;color: #FDC339;"></span></h5>
    </div>

</div>
<!-- End 红包 -->
<!-- 按钮 -->
<div class="t-btn" ng-hide="mask">
    <button id="getMoney_btn" onclick="showForm()">立即领取</button>
</div>
<!-- End 按钮 -->
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
    //显示表单
 function showForm() {
     $('.userForm').css('display','block');
 }
 //显示金额
    function showLuckMoney() {
        $('#LuckMoney').html('<?php echo $luck_money; ?>元');
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
            dataType: 'text',
            data: {
                name: $('#name').val(),
                tel: $('#tel').val(),
                luck_money: $('#luck_money').val(),
                openid: $('#openid').val()
            },
            beforeSend:function()
            { //触发ajax请求开始时执行
                $('#sendAjaxBtn').text('正在把钱塞进红包中，请稍等...');
                $('#sendAjaxBtn').attr('onclick','javascript:void();');//改变提交按钮上的文字并将按钮设置为不可点击
            },
            success: function (data) {
                $('#getMoney_btn').html(data);
                $('.userForm').css('display','none');
                $('#getMoney_btn').removeAttr('onclick');
                console.log(data);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
                console.log(XMLHttpRequest.readyState);
                console.log(textStatus);
                $('#getMoney_btn').html(data);
            },
        });
    }

</script>
</body>
</html>