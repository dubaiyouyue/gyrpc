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

class UserapitokenAction extends BaseAction {
	
	function index(){
		//生成长令牌
		$User = M("huiyuan");
		$tel=get_safe_replace($_POST['tel'])+0;
		if($tel) $w['tel']=$tel;
		$id=get_safe_replace($_POST['id'])+0;
		if($id) $w['id']=$id;
		$d['token']=get_safe_replace($_POST['token']);
		if((!$w['tel'] && !$w['id']) || !$d['token']) exit;
		$User->where($w)->save($d);
		exit;
	}
}

?>