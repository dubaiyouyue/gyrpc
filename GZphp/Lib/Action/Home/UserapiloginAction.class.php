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

class UserapiloginAction extends BaseAction {
	
	function check(){
		//登录接口
		$User = M("huiyuan");
		$tel=get_safe_replace($_POST['tel'])+0;
		$pass=get_safe_replace($_POST['pass']);
		$token=get_safe_replace($_POST['token']);
		$w['tel']=$tel;
		//$w['pass']=$pass;
		if(!$tel || !$pass |!$token) exit('e3');
		
		$loginsalt=$this->get_passwordssss(18);
		$r=$User->where($w)->limit(1)->select();
		//dump($r);
		$salt=$r['0']['salt'];
		$pass=md5(md5($pass).$salt);
		if($r['0']['pass']==$pass){
			//登录成功
			$ldata['loginsalt']=md5(md5($loginsalt).$r['0']['id']);
			$lw['id']=$r['0']['id'];
			$ldata['token']=$token;
			$rs=$User->where($lw)->limit(1)->save($ldata);
			$r['0']['loginsalt']=$ldata['loginsalt'];
			//$r['token']=$token;
			echo json_encode($r['0']);
			exit();
		}else{
			exit('e4');
		}
		exit('e5');
	}
	//随机字符串
	function get_passwordssss( $length = 8 ){
		$str = substr(md5(time()), 0, 6);
		return $str;
	}
}

?>