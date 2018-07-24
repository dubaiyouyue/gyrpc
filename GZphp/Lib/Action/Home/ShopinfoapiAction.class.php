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

class ShopinfoapiAction extends BaseAction {

    public function index() {
		$User = M("Product");
		$ps=$_POST['np']+0;//当前页数
		$pm=$_POST['pm']+0;//每页显示
		$or=$_POST['or'];//排序
		//最新降序 za
		//最新升序 zd
		//销量降序 xa
		//销量升序 xd
		//价格降序 ja
		//价格升序 jd
		switch ($or){
			case 'za':
			  $ord=' id desc ';
			  break;
			case 'zd':
			  $ord=' id asc ';
			  break;
			case 'xa':
			  $ord=' xiliang desc,price desc,id desc ';
			  break;
			case 'xd':
			  $ord=' xiliang asc,price asc,id asc ';
			  break;
			case 'ja':
			  $ord=' price desc,xiliang desc,id desc ';
			  break;
			case 'jd':
			  $ord=' price asc,xiliang asc,id asc ';
			  break;
			default:
			  $ord=' id desc ';
		}
		$r=$User->order($ord)->limit($ps*$pm,$pm)->select();
		//dump($r);
		echo json_encode($r);
		exit;
	}
}

?>