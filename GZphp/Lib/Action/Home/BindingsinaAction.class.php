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

class BindingsinaAction extends BaseAction {

    public function index() {
        $csina=$_GET['csina'];
		if($csina=='ok'){
			$sinatokenuid=$_SESSION['sinatokenuid']+0;
				$uinfo=$this->checklogincs();
				if(!$uinfo['id'] || !$sinatokenuid){
					header('Location:/index.php/login/');
					exit;
				}
				$w['id']=$uinfo['id'];
				$s['sinauid']=$sinatokenuid;
				$s['sinatx']=$_SESSION['sinatokenimage'];
				$s['sinaname']=$_SESSION['sinatokename'];
				$c=M('huiyuan')->where($w)->save($s);
				header('Location:/index.php/Home/PersonalProfile/?url=Dsfdl');
				$this->assign('userinfoall',$uinfo);
			exit;
		}
		$this->display();
	}
	
}

?>