<?php
/**
 * Created by PhpStorm.
 * User: 程龙飞
 * Date: 2018/1/25
 * Time: 17:55
 */
namespace app\index\controller;
use think\Controller;
use think\View;
use think\Request;
use think\Config;
class Funds extends Base
{
    function index(){
        $pagenum = Config::get('pag');
        if (Request::instance()->isGet()) {
            $data = $this->getDb("funds")->alias("a")->field("a.id,b.iphone,a.money,a.type,a.createtime,b.name")->join("wk_user b","a.userid = b.id")->order("a.createtime desc")->paginate($pagenum);
            $find='';
        }else{
            $find = input("post.find");
            $data = $this->getDb("funds")->alias("a")->field("a.id,b.iphone,a.money,a.type,a.createtime,b.name")->where("b.name like '%".$find."%' OR b.iphone like '%".$find."%'")->join("wk_user b","a.userid = b.id")->order("a.createtime desc")->paginate(10000);
        }

        $view = new View();
        $page = $data->render();
        $view->assign("find",$find);
        $view->assign("list",$data);
        $view->assign("page",$page);
        $view->assign("title","交易记录");
        $view->assign('controller',$this->getcontroller());
        $view->assign("menulist",$this->getmenulist());
        return $view->fetch();
    }
}