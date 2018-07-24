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

class SubmitOrderAction extends BaseAction {

    public function index() {
        $uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		$this->assign('userinfoall',$uinfo);
		
		//提交表单
		if($_POST['xx']){
			$sf=$_POST['sf'];
			$shi=$_POST['shi'];
			$qy=$_POST['qy'];
			$xx=$_POST['xx'];
			$yb=$_POST['yb']+0;
			//if(!$yb) $yb='000000';
			$xm=$_POST['xm'];
			$tel=$_POST['tel'];
			if($sf && $shi && $qy && $xx && $xm && $tel){
				//dump($_POST);exit;
				$a['uid']=$uinfo['id'];
				$a['sf']=$sf;
				$a['shi']=$shi;
				$a['qy']=$qy;
				$a['xx']=$xx;
				$a['yb']=$yb;
				$a['xm']=$xm;
				$a['tel']=$tel;
				$a['moren']=1;
				$r=M('dizhi')->add($a);
				if($r) echo 'ok';
				exit;
			}
		}
		
		//获取收货地址
		$d['uid']=$uinfo['id'];
		$dlist=M('dizhi')->where($d)->order('moren desc,id desc')->select();
		$this->assign('dlist',$dlist);
		
		//购物车商品列表
		$uid=$uinfo['id'];
		$a['uid']=$uid;
		
		//立即购买
		$gmid=$_GET['gmid'];
		if($gmid){
				$carid_arrgmid=explode(',',$gmid);
				$carid_arr_countgmid=count($carid_arrgmid);
				if($carid_arr_countgmid>1) $a['id']=array('in',$gmid);
				else $a['id']=$gmid;
		}else{
			$a['opid']=array('eq',0);
		}
		
		$scartlist=M('scart')->where($a)->select();
		
		//获取商品id array_unique
		foreach($scartlist as $v){
			if($spid) $spid.=','.$v['sid'];
			else $spid=$v['sid'];
		}
		//echo $spid;
		$sw['id']=array('in',$spid);
		$slist=M('product')->field('id,title,url,price,cprice,thumb,jifen')->where($sw)->select();
		//dump($slist);exit;
		
		foreach($scartlist as &$v){
			foreach($slist as $sv){
				if($sv['id']==$v['sid']){
					$v['price']=$sv['price'];
					$v['cprice']=$sv['cprice'];
					$v['title']=$sv['title'];
					$v['url']=$sv['url'];
					$v['thumb']=$sv['thumb'];
					$jifen=$jifen+$sv['jifen'];
					$zje=$zje+$sv['price']*$v['num'];
					$znum=$znum+$v['num'];
				}
			}
		}
		
		//获取订单信息 立即付款页面
		if($_GET['ddfkok']=='ok'){
			$ddadingddddlist=M('dingdan')->where(array('id'=>$scartlist['0']['opid']))->find();
			//dump($ddadingddddlist);exit;
			$this->assign('ddadingddddlist',$ddadingddddlist);
		}
		$allconfig=($this->Config);
		$zje=$zje+$allconfig['youfei'];
		$this->assign('jifen',$jifen);
		$this->assign('zje',$zje);
		$this->assign('znum',$znum);
		//dump($scartlist);exit;
		$this->assign('scartlist',$scartlist);
		
		//dump($uinfo);exit;
		$this->display();
	}
	
}

?>