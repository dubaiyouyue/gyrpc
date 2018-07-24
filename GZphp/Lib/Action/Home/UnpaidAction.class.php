<?php

/**
 * 
 * AreaAction.class.php (ajax 获取地址)
 *
 * @package      	GZPHP
 * @author          wen QQ:52009619 <admin@resonance.com.cn>
 * @copyright     	Copyright (c) 2008-2011  (http://www.resonance.com.cn)
 * @license         http://www.resonance.com.cn/license.txt
 * @version        	GzPHP企业网站管理系统 v2.1 2011-03-01 resonance.com.cn $
 */
if (!defined("GZPHP"))
    exit("Access Denied");

class UnpaidAction extends BaseAction {

    public function index() {
        $uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		$this->assign('userinfoall',$uinfo);
		
		$uid=$uinfo['id'];
		$w['userid']=$uid;
		$w['fk']=0;
		$w['tstatus']=0;
		$w['sh']=0;
		
		
		
		
		$psffd=$_GET['psffd']+0;
		if(!$psffd || $psffd<1) $psffd=1;
		$m=5;
		$lm=($psffd-1)*$m;
		
		$list=M('dingdan')->where($w)->limit($lm,$m)->order('id desc')->select();
		
		
		
		
		
		
		//$list=M('dingdan')->where($w)->order('id desc')->select();
		$this->assign('list',$list);
		//dump($list);
		//获取所有商品
		foreach($list as $k=>$v){
			if(!$pallid) $pallid=$v['sid'];
			else $pallid.=','.$v['sid'];
		}
		//echo $pallid;exit;
		$pallid_arr=explode(',',$pallid);
		$pallid_arr=array_unique($pallid_arr);
		foreach($pallid_arr as $pk=>$pv){
			if(!$pk) $pallid=$pv;
			else $pallid.=','.$pv;
		}
		//echo $pallid;exit;
		$pw['id']=array('in',$pallid);
		$allnewlist=M('product')->field('title,id,thumb,price,cprice,kc')->where($pw)->select();
		foreach($allnewlist as $nk=>$nv){
			$allnewlistnew[$nv['id']]=$nv;
		}
		$this->assign('allnewlistnew',$allnewlistnew);
		//dump($allnewlistnew);exit;
		//$this->display();
		
		if($psffd>1){
			if(empty($list)) exit;
			$this->display('./GZphp/Tpl/Home/Default/Unpaid_ajax.html');
		}
		else $this->display();
		
	}
	public function qx(){
        $uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		$uid=$uinfo['id'];
		$w['userid']=$uid;
		$id=$_GET['id']+0;
		$w['id']=$id;
		if($id){
			$sa['tstatus']=2;
			$s=M('dingdan')->where($w)->save($sa);
			//if($s){
				header('Location:/index.php/Home/PersonalProfile/?url=Okoid');
				exit;
			//}
		}
		
	}
}

?>