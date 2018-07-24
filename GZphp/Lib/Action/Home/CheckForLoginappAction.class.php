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

class CheckForLoginappAction extends BaseAction {

    public function index() {
		//$uidinfo=$this->checklogincs();
		//$suid=$uidinfo['id'];
		//sina
		if($_SESSION['login_app_token']){
			if($_SESSION['sinatokenuid']){
				$w['sinauid']=$_SESSION['sinatokenuid'];
			}else if($_SESSION['qquid']){
				$w['qquid']=$_SESSION['qquid'];
			}
			$f=M('huiyuan')->where($w)->limit(1)->select();
			$fuid=$f['0']['id'];
			//if(!$fuid && $suid) $fuid=$suid;
			if($fuid){
				$s['msinatoken']=$_SESSION['login_app_token'];
				$sw['id']=$fuid;
				$sd=M('huiyuan')->where($sw)->limit(1)->save($s);
				$url='http://m.guangyuren.com/index.php/Home/Loginsinaapp/sinahave.html';
				if($_SESSION['login_app']=='weixin') $url='http://wx.guangyuren.com/index.php?s=/addon/Loginapptoken/Loginapptoken/sinahave.html';
				header('Location:'.$url);
				exit;
			}else{
					$s['token']=$_SESSION['login_app_token'];
					if($_SESSION['sinatokenuid']){
						$s['sinauid']=$_SESSION['sinatokenuid'];
						$s['sinatx']=$_SESSION['sinatokenimage'];
						$s['sinaname']=$_SESSION['sinatokename'];
					}else if($_SESSION['qquid']){
						$s['qquid']=$_SESSION['qquid'];
						$s['qqname']=$_SESSION['qqname'];
						$s['qqtx']=$_SESSION['qqtx'];
					}
					$c=M('loginapptoken')->add($s);
						$url='http://m.guangyuren.com/index.php/Home/Loginsinaapp/sinano/appid/'.$c.'.html';
						if($_SESSION['login_app']=='weixin') $url='http://wx.guangyuren.com/index.php?s=/addon/Loginapptoken/Loginapptoken/sinano/appid/'.$c.'.html';
						
					
					if($c) header('Location:'.$url);
					exit;
			}
		}
	}
	//随机字符串
	function get_passwordssss( $length = 8 ){
		$str = substr(md5(time()), 0, 6);
		return $str;
	}
}

?>