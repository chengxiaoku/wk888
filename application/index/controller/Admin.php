<?php
/**
 * Created by PhpStorm.
 * User: 程龙飞
 * Date: 2018/1/26
 * Time: 14:40
 */
namespace app\index\controller;

use think\View;
use think\Controller;
use think\Request;
use think\Config;
class Admin extends Base{
    function index(){
        $list = $this->getDb('admin')->order('lasttime','desc')->select();
        $view = new View();
        $view->assign('title', '管理员管理');
        $view->assign('list',$list);
        $view->assign('controller',$this->getcontroller());
        $view->assign("menulist",$this->getmenulist());
        $menu = Config::get("MENU");
        $menuname = Config::get("MENUNAME");


        $arr = [];
        $num = 0;
        if(is_array($menu)){
            foreach ($menu as $key => $val ){
                if(!is_array($val)){
                    $arr[$num][0] = $menuname[$key];
                    $arr[$num][1] = $key;
                }else{
                    foreach ($val['content'] as $key1 => $value){
                        $arr[$num][0] = $menuname[$key1];
                        $arr[$num][1] = $key1;
                        $num ++;
                    }
                }
                $num ++;
            }
        }
     
        $view->assign("menulistchbox",$arr);
        return $view->fetch();
    }

    function add(){
        $data = input('post.');
        $data['createtime'] = time();
        $this->isVal('admin',$data);
        $data['password'] = md5(123456);
        $add = $data['add'];
        $data['qx'] = json_encode($add);
        unset($data['add']);
        $tr = $this->getDb('admin')->insert($data);
        $tr ? $this->ajaxSuccess() : $this->ajaxError();
    }
    function update(){
        if(Request::instance()->isGet()){
            $id = input('id');
            $data = $this->getDb('admin')->field("username,nickname,id,qx")->where('id',$id)->select();
            //$data[0]['qx'] = unserialize(base64_decode($data[0]['qx']));
            echo json_encode($data);
        }else{
            $data = input('post.');
            $id = $data['id'];
            unset($data['id']);
            $this->isVal('admin',$data);
            $add = $data['update'];
            unset($data['update']);
            $data['qx'] = json_encode($add);
            $tr = $this->getDb('admin')->where('id',$id)->update($data);
            $tr ? $this->ajaxSuccess() : $this->ajaxError();
        }
    }
    function del(){
        $id = input('id');
        $tr = $this->getDb('admin')->where('id',$id)->delete();
        $tr ? $this->ajaxSuccess() : $this->ajaxError();
    }
    function isVal($table,$data){
        $validate =  $this->validate($table);
        if(!$validate->check($data)){
            $this->ajaxError($validate->getError());
        }
    }
}