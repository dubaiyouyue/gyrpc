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

class ShoppingCartAction extends BaseAction {
	///index.php/Home/Sousuo/index/key/123
    public function index() {
		$uidinfo=$this->checklogincs();
		//dump($uidinfo);exit;
		$uid=$uidinfo['id'];
		if(!$uid){
			header('Location:/index.php/login/');
			exit;
		}	
		$a['uid']=$uid;
		$a['opid']=array('eq','0');
		$scartlist=M('scart')->where($a)->select();
		$anum=count($scartlist)+0; //购物车商品数量
		
		//获取商品id array_unique
		foreach($scartlist as $v){
			if($spid) $spid.=','.$v['sid'];
			else $spid=$v['sid'];
		}
		//echo $spid;
		$sw['id']=array('in',$spid);
		$slist=M('product')->field('id,title,url,price,cprice,thumb')->where($sw)->select();
		//dump($slist);exit;
		
		foreach($scartlist as &$v){
			foreach($slist as $sv){
				if($sv['id']==$v['sid']){
					$v['price']=$sv['price'];
					$v['cprice']=$sv['cprice'];
					$v['title']=$sv['title'];
					$v['url']=$sv['url'];
					$v['thumb']=$sv['thumb'];
				}
			}
		}
		//dump($scartlist);exit;
		$this->assign('anum',$anum);
		$this->assign('scartlist',$scartlist);
		$this->display();
	}
	public function ups(){
		$uidinfo=$this->checklogincs();
		//dump($uidinfo);exit;
		$uid=$uidinfo['id'];
		
		$sm=$_GET['sm'];
		$scid=$_GET['scid'];
		if(!$scid || !$sm || !$uid) exit;
		$uw['uid']=$uid;
		$uw['id']=array('in',$scid);
		//$uw['yanse']=$yanse;
		//$uw['chicun']=$chicun;
		
		//删除
		if($sm=='del'){
			$d=M('scart')->where($uw)->delete();//->limit(1)
			if($d) exit('ok');
			else exit;
		}
		
		//当前数量
		$nnums=M('scart')->where($uw)->limit(1)->select();
		$nnum=$nnums['0']['num'];
	
	
		//减少
		if($sm=='min'){
			if($nnum<2){
				echo 1;//$nnum;
				exit;
			}else{
				$newnum=$nnum-1;				
			}
			$up['num']=array('exp','num-1');
		}
		//增加
		if($sm=='add'){
			//当前库存
			$nkc=$this->thisku($nnums['0']['sid'],$nnums['0']['yanse'],$nnums['0']['chicun']);
			//echo $nkc;exit;
			if($nnum==$nkc){
				echo $nnum;
				exit;
			}else{
				$newnum=$nnum+1;				
			}
			$up['num']=array('exp','num+1');
		}
		$u=M('scart')->where($uw)->limit(1)->save($up);
		echo $newnum;
		exit;
	}
}

?>