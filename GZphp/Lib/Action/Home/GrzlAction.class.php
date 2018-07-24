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

class GrzlAction extends BaseAction {

    public function index() {
        $uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		if($_POST){
			//dump($_POST);
			$sdata['nicheng']=get_safe_replace($_POST['nicheng']);
			$sdata['name']=get_safe_replace($_POST['name']);
			$sdata['srn']=get_safe_replace($_POST['srn']);
			$sdata['sry']=get_safe_replace($_POST['sry']);
			$sdata['srr']=get_safe_replace($_POST['srr']);
			$sdata['szds']=get_safe_replace($_POST['szds']);
			$sdata['szdsq']=get_safe_replace($_POST['szdsq']);
			$sdata['szdq']=get_safe_replace($_POST['szdq']);
			$sdata['jxs']=get_safe_replace($_POST['jxs']);
			$sdata['jxss']=get_safe_replace($_POST['jxss']);
			$sdata['jxq']=get_safe_replace($_POST['jxq']);
			$sdata['sex']=get_safe_replace($_POST['sex']);
			
			$User = M("huiyuan");
			$lw['id']=$uinfo['id'];
			$sidsup=$User->where($lw)->limit(1)->save($sdata);
			//if($sidsup) {
				header('Location:/index.php/Home/PersonalProfile/?url=Grzl');
				//exit('lok');
			//}
			exit;
			
		}else $this->display();
		
    }
	
}

?>