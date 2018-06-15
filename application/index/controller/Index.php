<?php
namespace app\index\controller;
use think\Request;
use think\View;
use think\Config;
use think\Session;
class Index extends Base
{
    public function index()
    {

        $view = new View();
        $user = $this->getDb('user');
        $usercount = $user->count();
        $projectcount = $this->getDb('project')->count();

        //获取消费项目list
        $data = $this->getDb('project')->select();
        $view->assign('data',$data);

        //获取 充值与消费总额
        $typemoney = $this->get_funds_money();
        $view->assign("typemoney",$typemoney);
        //获取 最近交易记录
        $fundsdata = $this->get_funds_msg();
        $view->assign("fundsdata",$fundsdata);
        //获取交易次数
        $view->assign("fundscount",$this->getDb("funds")->count());
        //获取用户手机号列表
        $dataiphone = $user->field('id,iphone,name')->order('createtime desc')->select();
        $view->assign('dataiphone',$dataiphone);
        $view->assign('projectcount',$projectcount);
        $view->assign('useradd',$usercount);
        $view->assign('title', '控制台');
        $view->assign('controller',$this->getcontroller());
        $view->assign("menulist",$this->getmenulist());
        $usertype = $this->getusertype();
        if(!$usertype){
            $usertype = '';
        }
        $view->assign("usertype",$usertype);
        $getmoney = $this->getmoney();
        $view->assign("getmoney",$getmoney);
        return $view->fetch();
    }
  
    private function get_funds_money(){
        $arr = $this->getDb('funds')->field("SUM(money) as type")->group("type")->select();
        $newarr = ['type'=>0];
        if($arr){
            if (count($arr) == 1){
                $arr[1] = $newarr;
            }else{
                return $arr;
            }
        }else{
            $arr[0] = $arr[1] = $newarr;
        }
        return $arr;
    }
    public function consume($iphone){
        if (!is_numeric($iphone)){
            echo 2;
            exit;
        }
        $data = $this->getDb('user')->alias('a')->join("wk_level b",'a.typeid = b.id')->field('a.*,b.name as level,b.discount')->where("a.iphone=$iphone")->select();
        if($data){
            echo json_encode($data);
        }else{
            echo 2;
        }
    }
    public function priceshow(){
        if(Request::instance()->isGet()){
            $strid = input('get.ustr');
            if(!$strid){
                echo 2;
            }else{
                $data = $this->getDb('project')->query("SELECT SUM(money) as money FROM wk_project WHERE id in ($strid)");
                echo $data[0]['money'];
            }
        }else{
            //扣费
            $uid = input('post.uid');
            /*$str = input('post.str');
            if(!$uid||!$str){
                $this->ajaxError("扣费失败");
            }*/

            $money = input('post.money');
            if(!$uid||!$money){
                $this->ajaxError("扣费失败");
            }

            $user = $this->getDb('user');
           $data = $user->alias("a")->join("wk_level b","a.typeid = b.id")->field("a.money,b.discount")->where("a.id=$uid")->find();

             /*$summoney = $this->getDb('project')->query("SELECT SUM(money) as money FROM wk_project WHERE id in ($str)");
            $mo = $summoney[0]['money'] * ($data['discount']/100);*/
            if($money >  $data['money'] ){
                $this->ajaxError("扣款失败,账户余额不足");
            }else{
                //扣款
                $bool = $user->where('id',$uid)->setDec("money",$money);
                if($bool){
                    $this->set_funds($uid, $money,1);
                    $this->ajaxSuccess([],"扣款成功");
                }else{
                    $this->ajaxError();
                }
            }
        }
    }

    /**
     * 
     */
    function addmoney(){
        if(Request::instance()->isPost()){
            $uid = input('post.uid');
            $money = input('post.money');
            if($money < 0){
                $this->ajaxError("充值金额在0~10000之间");
            }
            $bool = $this->getDb("user")->where("id",$uid)->setInc("money",$money);
            if($bool){
                $this->set_funds($uid, $money,0);
                $this->ajaxSuccess([],"充值成功");
            }else{
                $this->ajaxError("充值失败");
            }
        }
    }

    /**
     * @param $uid  用户ID
     * @param $money  收入(消费)金额
     * @param $type 操作类型
     */
    private function set_funds($uid,$money,$type){
        $data['userid'] = $uid;
        $data['money'] = $money ;
        $data['type'] = $type;
        $data['createtime'] = time();
        $this->getDb("funds")->insert($data);
    }

    /**
     * 获取交易记录
     */
    private function get_funds_msg($num = 5){
        return $this->getDb("funds")->alias("a")->field("a.money,a.type,a.createtime,b.name,b.iphone")->join("wk_user b","a.userid = b.id")->limit($num)->order("createtime desc")->select();
    }

    /**
     * echarts
     * 获取会员类型分析
     */
    private function getusertype(){
        $sql = "select b.`name`,count(a.id) as value  from wk_user as a,wk_level as b WHERE a.typeid = b.id GROUP BY b.id";
        $data = $this->getDb("user")->query($sql);
        return json_encode($data);
    }

    /**
     * 获取折线图数据
     */
    private function getmoney(){
        $sql = "select sum(money) money,FROM_UNIXTIME(createtime,'%Y-%m') time from wk_funds WHERE type=0 GROUP BY time ORDER BY time ASC limit 0,5";
        $data = $this->getDb("user")->query($sql);
        return json_encode($data);
    }

}
