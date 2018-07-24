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

class ScartajaxAction extends BaseAction {

    public function index() {
		//登录
		$uidinfo=$this->checklogincs();
		//dump($uidinfo);exit;
		$uid=$uidinfo['id'];
		$sid=$_GET['sid']+0;
		if(!$uid || !$sid) exit;
		
		//判断是否已经有
		$a['uid']=$uid;
		$a['sid']=$sid;
		
		$a['yanse']=$_GET['yanse'];
		$a['chicun']=$_GET['chicun'];
		
			$get_gkc_src=M('product')->where(array('id'=>$sid))->find();
			$get_gkc_src=$get_gkc_src['kc'];
			$gkc_number=$this->thisku($sid,$_GET['chicun'],$_GET['yanse']);
			$this->kubd($_GET['yanse'],$_GET['chicun'],$get_gkc_src,$gkc_number,1,$sid,$get_type=2);
			
		
		$ass['uid']=$uid;
		$ass['sid']=$sid;
		
		$ass['yanse']=$_GET['yanse'];
		$ass['chicun']=$_GET['chicun'];
		$ass['opid']=array('eq','0');
		$f=M('scart')->where($ass)->limit(1)->select();
		$num=$_GET['num']+0;
		if($f['0']['id']){
			if(!$num) $num=1;
			$s['num']=array('exp','num+'.$num);
			$ss=M('scart')->where($ass)->limit(1)->save($s);
			exit('ok');
		}
		
		$a['num']=1;
		$a['ctime']=time();
		$r=M('scart')->add($a);
		if($r) exit('ok');
		exit;
	}
}

?>