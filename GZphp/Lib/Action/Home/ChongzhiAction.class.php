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

class ChongzhiAction extends BaseAction {

    public function index() {
        $uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		$uid=$uinfo['id'];
		$fkfs=$_GET['fkfs']+0;
		$je=$_GET['je']+0;
		$je=number_format($je,2);
		$sdingdan=$uid;
		if($je){
			if($fkfs==2){
				//微信支付
				$url = APP_DEBUGwxxxxx."wx/example/native.php";
				$post_data = array (
					"Body" => "广羽人充值",
					"Attach"=>'2#'.$uid.'#'.$sdingdan.'#pc',
					'Total_fee'=>$je,
					'Product_id'=>$sdingdan
				);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				// post数据
				curl_setopt($ch, CURLOPT_POST, 1);
				// post的变量
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
				$output = curl_exec($ch);
				curl_close($ch);
				//打印获得的数据
				//print_r($output);
				$wximg=APP_DEBUGwxxxxx.'wx/example/qrcode.php?data='.$output;
				$this->assign('wximg',$wximg);
				$this->assign('je',$je);
				$this->assign('fkfs',$fkfs);
				$this->display('./GZphp/Tpl/Home/Default/Chongzhi_wx.html');
			}else{
				$this->display('./GZphp/Tpl/Home/Default/Chongzhi_zfb.html');
			}
		}
	}
}

?>