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

class OrderfkAction extends BaseAction {

    public function index() {
        $uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		
		$oid=$_GET['oid'];
		$uid=$uinfo['id'];
		$w['userid']=$uid;
		$w['id']=$oid;
		$list=M('dingdan')->where($w)->select();
		$this->assign('list',$list['0']);
		
		$tstatus=$list['tstatus'];
		if($tstatus==0){
			$tstatus='未交易';
		}else if($tstatus==2){
			$tstatus='交易完成';
		}else{
			$tstatus='交易取消';
		}
		$this->assign('tstatus',$tstatus);
		//dump($list);exit;
		
		$this->display();
		
	}

}

?>