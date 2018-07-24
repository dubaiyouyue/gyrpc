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

class BindingqqAction extends BaseAction {

    public function index() {
        $csina=$_GET['csina'];
		if($csina=='ok'){
			$qquid=$_SESSION['qquid'];
				$uinfo=$this->checklogincs();
				if(!$uinfo['id'] || !$qquid){
					header('Location:/index.php/login/');
					exit;
				}
				$w['id']=$uinfo['id'];
				$s['qquid']=$qquid;
				$s['qqtx']=$_SESSION['qqtx'];
				$s['qqname']=$_SESSION['qqname'];
				$c=M('huiyuan')->where($w)->save($s);
				header('Location:/index.php/Home/PersonalProfile/?url=Dsfdl');
				$this->assign('userinfoall',$uinfo);
			exit;
		}
		$this->display();
	}
	
}

?>