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

class TologinapiAction extends BaseAction {
	
	function index(){
		//检测 获取会员信息接口
		//echo json_encode($_POST); 
		$id=get_safe_replace($_POST['id'])+0;
		$token=get_safe_replace($_POST['token']);
		$loginsalt=get_safe_replace($_POST['loginsalt']);
		$tel=get_safe_replace($_POST['tel'])+0;
		if(!$token || !$loginsalt || (!$tel && !$id)) exit;
			$User = M("huiyuan"); // 实例化User对象
			if($id) $w['id']=$id;
			if($tel) $w['tel']=$id;
			$w['loginsalt']=$loginsalt;
			$w['token']=$token;
			$r=$User->where($w)->limit(1)->select();
			//echo '<br >'.$r['0']['token'];
			echo json_encode($r['0']);
			//dump($r);
			exit;
	}
}

?>