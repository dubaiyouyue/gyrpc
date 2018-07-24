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

class GetcartnumAction extends BaseAction {

    public function index() {
			$uidinfo=$this->checklogincs();
			$suid=$uidinfo['id'];
			if(!$suid) exit('0');
			
			
			//获取购物车
			$anum=0;
			if($suid){
				$a['uid']=$suid;
				$a['opid']=array('eq','0');
				$scartlist=M('scart')->where($a)->select();
				$anum=count($scartlist)+0; //购物车商品数量
				if($anum>9) $anum='+';
			}
			echo $anum;
			exit;
	}

}

?>