<?php
/**
 * Created by PhpStorm.
 * User: 程龙飞
 * Date: 2018/1/22
 * Time: 16:22
 */
namespace app\index\controller;
use think\Controller;
use think\View;
use think\Request;
use think\Config;
class User extends Base
{
    function index(){
        $pagenum = Config::get('pag');
        if (Request::instance()->isGet()) {
            $list = $this->getDb('user')->order("createtime", 'desc')->paginate($pagenum);
            $find = '';
        }else{
            $find = input("post.find");
            $list = $this->getDb('user')->where("name like '%".$find."%' OR iphone like '%".$find."%'")->order("createtime",'desc')->paginate($pagenum);
            
        }
        $view = new View();
        //查看结果集
        $arr = [];
        $data = '';
        $data = $this->getDb('level')->order('discount','desc')->select();
        if($list->items()){
            if($data){
                $arr = $list->items();
                foreach ($arr as $key => $val){
                    $sum_money = $this->getusersum_money($val['id']);
                    $arr[$key]['type0money'] = $sum_money[0]['summney'];
                    $arr[$key]['type1money'] = $sum_money[1]['summney'];
                    $arr[$key]['discount']=100;
                    $arr[$key]['typeid']='非会员';
                    foreach ($data as $key1 => $val1){
                        if($val1['id'] == $val['typeid']){
                            $arr[$key]['typeid']=$val1['name'];
                            $arr[$key]['discount']=$val1['discount'];
                            continue;
                        }
                    }
                }
            }
        }

        //dump($arr);

        $view->assign('data',$data);
        // 获取分页显示
        $page = $list->render();
        // 模板变量赋值
        $view->assign('title', '会员管理');
        $view->assign('list', $arr);
        $view->assign('page', $page);
        $view->assign('find', $find);
        $view->assign('controller',$this->getcontroller());
        $view->assign("menulist",$this->getmenulist());
        // 渲染模板输出
        return $view->fetch();

        
    }

    function add(){

            $data = input('post.');
            //验证
            $this->isVal('user',$data);
            $data['createtime'] = time();
            $tr = model('User')->save($data);
            if($tr){
                $this->ajaxSuccess();
            }else{
                $this->ajaxError();
            }



    }
    function update(){
        if (Request::instance()->isGet()){
            $id = input("id");
            $data = model('User')->select($id);
            echo json_encode($data);
        }else{
            $data = input('post.');
            $id = $data['id'];
            unset($data['id']);

            $this->isVal('user',$data);
            $tr = model('User')->save($data,['id' => $id]);
            if($tr){
                $this->ajaxSuccess();
            }else{
                $this->ajaxError();
            }
        }

    }
    function del(){
        $id = input("id");
      
        if(is_numeric($id)){
            $tr = db('user')->delete($id);
            $this->getDb("funds")->where("userid=$id")->delete();
        }else{
            $arrid = explode(',',$id);
            $this->getDb("funds")->where('userid','in',$arrid)->delete();
            $tr = db('user')->where('id','in',$arrid)->delete();
        }
        if(!$tr){
            $this->ajaxError();
        }else{
            $this->ajaxSuccess();
        };
    }
    function isVal($table,$data){
        $validate =  $this->validate($table);
        if(!$validate->check($data)){
            $this->ajaxError($validate->getError());
        }
    }
    function getusersum_money($uid){
        $arr = $this->getDb("funds")->field("sum(money) as summney,type")->where("userid = $uid")->group("type")->select();
        $startarr =  [
            'summney' => 0,
            'type' =>0,
        ];
        if($arr){
            if(count($arr) == 1){ // 0
                $arr[1] = $startarr;
            }else{
                return $arr;
            }
        }else{
            $arr[0] =$startarr;
            $arr[1] =$startarr;
        }
        return $arr;
    }

}