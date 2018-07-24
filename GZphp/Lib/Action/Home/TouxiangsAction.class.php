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

class TouxiangsAction extends BaseAction {

    public function index() {
        $uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		$utx=get_safe_replace($_GET['u']);
		if($utx){
			$User = M("huiyuan");
			$lw['id']=$uinfo['id'];
			$ldata['tx']=$utx;
			$sidsup=$User->where($lw)->limit(1)->save($ldata);
			//if($sidsup) {
				header('Location:/index.php/Home/PersonalProfile/?urls=Touxiangs');
				exit;
			//}
		}
		//$this->display();
		
    }
	
}

?>