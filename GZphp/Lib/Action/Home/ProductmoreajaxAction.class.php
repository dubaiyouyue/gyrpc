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

class ProductmoreajaxAction extends BaseAction {

    public function index() {
		$psffd=$_GET['psffd']+0;
		$pmore=$psffd*6;
		$mlist=M('Product')->order('xiliang desc')->limit($pmore,6)->select();
		$this->assign('mlist',$mlist);
		$this->display();
		//exit;
	}
}

?>