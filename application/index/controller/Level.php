<?php
/**
 * Created by PhpStorm.
 * User: 程龙飞
 * Date: 2018/1/23
 * Time: 19:30
 */
namespace app\index\controller;
use think\Controller;
use think\View;
use think\Request;
use think\Config;
class Level extends Base{
    function index(){
        $list = $this->getDb('level')->order('createtime','desc')->paginate(Config::get('page'));
        $page = $list->render();
        $view = new View();
        $view->assign('title', '会员等级设置');
        $view->assign('list',$list);
        $view->assign('page',$page);
        $view->assign('controller',$this->getcontroller());
        $view->assign("menulist",$this->getmenulist());
        return $view->fetch();
    }

    function add(){
        $data = input('post.');
        $data['createtime'] = time();
        $this->isVal('Level', $data);
        $tr = $this->getDb('Level')->insert($data);
        $tr ? $this->ajaxSuccess() : $this->ajaxError();
    }
    function update(){
        if(Request::instance()->isGet()){
            $id = input('id');
            $data = $this->getDb('Level')->where('id',$id)->select();
            echo json_encode($data);
        }else{
            $data = input('post.');
            $id = $data['id'];
            unset($data['id']);
            $data['createtime'] = time();
            $this->isVal('Level',$data);
            $tr = $this->getDb('Level')->where('id',$id)->update($data);
            $tr ? $this->ajaxSuccess() : $this->ajaxError();
        }
    }
    function del(){
        $id = input('id');
        $tr = $this->getDb('Level')->where('id',$id)->delete();
        $tr ? $this->ajaxSuccess() : $this->ajaxError();
    }
    function isVal($table,$data){
        $validate =  $this->validate($table);
        if(!$validate->check($data)){
            $this->ajaxError($validate->getError());
        }
    }
}