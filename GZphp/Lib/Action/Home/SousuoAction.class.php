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

class SousuoAction extends BaseAction {
	///index.php/Home/Sousuo/index/key/123
    public function index() {
		$key=urldecode($_GET['key']);
		
		if($key){
			$map['title'] = array('like','%'.$key.'%');
			$alist=M('article')->field('id,title,url')->where($map)->select();
			$plist=M('product')->field('id,title,url')->where($map)->select();
		}
		//dump($alist);exit;
		$n=count($alist)+count($plist);
		$this->assign('alist',$alist);
		$this->assign('plist',$plist);
		$this->assign('n',$n);
		$this->assign('ssskey',$key);
		$this->display();
	}
}

?>