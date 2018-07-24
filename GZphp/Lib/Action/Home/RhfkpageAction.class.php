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

class RhfkpageAction extends BaseAction {

    public function index() {
		//$username=iconv('GBK', 'UTF-8', $_GET['beizhu']);
		//dump($_GET);exit;
		$w['id']=210;
		$c=M('page')->where($w)->find();
		//dump($c);exit;
		$this->assign('c',$c);
		$this->display('');
        
		

	}

	
}

?>