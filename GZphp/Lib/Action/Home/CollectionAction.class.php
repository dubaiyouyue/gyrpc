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

class CollectionAction extends BaseAction {

    public function index() {
		//登录
		$uidinfo=$this->checklogincs();
		//dump($uidinfo);exit;
		$uid=$uidinfo['id'];
		$sid=$_GET['sid']+0;
		if(!$uid || !$sid) exit;
		
		//校验是否已收藏
		$cwhere['uid']=$uid;
		$cwhere['sid']=$sid;
		$cwhere['type']=1;
		$chave=M('collection')->where($cwhere)->limit(1)->select();
		if($chave['0']['id']) exit('ok');
		//end
		$a['uid']=$uid;
		$a['sid']=$sid;
		$a['ftime']=time();
		$a['type']=1;//客户端类型 1电脑 2手机 3微信
		$r=M('collection')->add($a);
		if($r) exit('ok');
		exit;
	}
}

?>