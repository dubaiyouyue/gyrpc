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

class SubmitOrderajaxAction extends BaseAction {

    public function index() {
        $uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			//header('Location:/index.php/login/');
			exit;
		}
		//$this->assign('userinfoall',$uinfo);
		
		
		
		//获取收货地址
		$d['uid']=$uinfo['id'];
		$dlist=M('dizhi')->where($d)->order('moren desc,id desc')->select();
		$this->assign('dlist',$dlist);
		
		//dump($uinfo);exit;
		$this->display();
	}
	
}

?>