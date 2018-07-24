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

class TuihuoAction extends BaseAction {
	public function index() {
        $uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		$this->assign('userinfoall',$uinfo);
		
		$uid=$uinfo['id'];
		/*$w['uid']=$uid;
		$w['fk']=1;
		$w['sh']=1;
		$w['tstatus']=2;*/
		
		
		$list=M('dingdan')->where('userid='.$uid.' and (fk=1 and tuihuo>0)')->order('id desc')->select();
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
		$this->display();
	}
    public function sth() {
		//登录
		$uidinfo=$this->checklogincs();
		//dump($uidinfo);exit;
		$uid=$uidinfo['id'];
		$did=$_GET['nowid']+0;
		if(!$uid || !$did) exit;
		
		$tuihuoliyou=urldecode($_GET['tuihuoliyou']);
		//echo $tuihuoliyou;
		//echo $did;
		//echo $uid;
		
		$stw['userid']=$uid;
		$stw['id']=$did;
		$sts['tuihuo']=1;
		$sts['tuihuoliyou']=$tuihuoliyou;
		
		$sd=M('dingdan')->where($stw)->save($sts);
		header('Location:/index.php/Home/PersonalProfile/?url=Tuihuo');
		exit;
	}
}

?>