<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:75:"D:\phpStudy\WWW\luckMoney\public/../application/admin\view\index\index.html";i:1513237807;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>红包抽奖程序后台数据中心-盛腾集团</title>
    <link rel="stylesheet" href="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="http://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        tr,th{
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-bordered table-striped table-hover">
                <caption><h1 align="center">新年家装铁锤行动微信红包抽奖数据</h1></caption>
                <thead>
                <tr>
                    <th>姓名</th>
                    <th>电话</th>
                    <th>中奖金额</th>
                    <th>中奖时间</th>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                    <td><?php echo $vo['name']; ?></td>
                    <td><?php echo $vo['tel']; ?></td>
                    <td><?php echo $vo['luck_money']; ?></td>
                    <td><?php echo $vo['time']; ?></td>
                </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>

                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <p align="center">总共数据：<?php echo $count; ?> 条</p>
            <?php echo $page; ?>

        </div>
    </div>
</div>


</body>
</html>