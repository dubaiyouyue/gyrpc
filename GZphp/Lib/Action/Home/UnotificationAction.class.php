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

class UnotificationAction extends BaseAction {

    public function index() {
		//登录
		$uidinfo=$this->checklogincs();
		//dump($uidinfo);exit;
		$uid=$uidinfo['id'];
		$sid=$_GET['sid']+0;
		//$yanse=urldecode($_GET['yanse']);
		$yanse=$_GET['yanse'];
		$chicun=$_GET['chicun'];
		if(!$uid || !$sid || !$yanse || !$chicun) exit;
		
		
		//$a['str']=$yanse.$chicun; //白色lizhenqiuMlizhenqiulizhenqiu998
		$a['yanse']=$yanse;
		$a['chicun']=$chicun;
		$a['uid']=$uid;
		$a['sid']=$sid;
		
		//是否已经存在
		$h=M('unotification')->where($a)->find();
		if($h['id']) exit('ok');
		
		
		$a['ntime']=time();
		$r=M('unotification')->add($a);
		if($r) exit('ok');
		exit;
	}
}

?>