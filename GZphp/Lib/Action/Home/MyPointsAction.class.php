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

class MyPointsAction extends BaseAction {

    public function index() {
        $uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		$uid=$uinfo['id']+0;
		
		$w['uid']=$uid;
		$list=M('jifenjilu')->where($w)->order('id desc')->select();
		$this->assign('list',$list);
		$this->assign('jifen',$uinfo['jifen']);
		$this->display();
		
    }
	
}

?>