<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
    public function index()
    {
        $list = Db::table('stjz_user')->paginate(20);
        $count = Db::table('stjz_user')->count();
        // 获取分页显示
        $page = $list->render();
        // 模板变量赋值
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('count', $count);
        // 渲染模板输出
        return $this->fetch();
    }
}
