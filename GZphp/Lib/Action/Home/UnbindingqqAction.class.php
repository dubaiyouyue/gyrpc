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

class UnbindingqqAction extends BaseAction {

    public function index() {
		$uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		$w['id']=$uinfo['id'];
		$s['qquid']='';
		$s['qqtx']='';
		$s['qqname']='';
		$c=M('huiyuan')->where($w)->save($s);
		header('Location:/index.php/Home/PersonalProfile/?url=Dsfdl');
		exit;
	}
	
}

?>