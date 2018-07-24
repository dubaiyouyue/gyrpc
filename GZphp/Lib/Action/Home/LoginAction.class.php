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

class LoginAction extends BaseAction {

    public function index() {
        //$mm=htmlspecialchars(trim($_GET['mm']));
		//dump($_SESSION);exit;
		$tel=get_safe_replace($_GET['tel'])+0;
		$pass=get_safe_replace($_GET['pass']);
		$yzm=get_safe_replace($_GET['yzm']);
		if($tel && $pass && $yzm){
			if(md5($yzm)!=$_SESSION['verify'] || !$_SESSION['verify']) {
				$_SESSION['verify']='';
				exit('e1'); //验证码错误
			}
			$User = M("huiyuan"); // 实例化User对象
			$w['tel']=$tel;
			$r=$User->where($w)->limit(1)->select();
			if(!$r['0']['id']) exit('e2'); //手机号没有注册
			$salt=$r['0']['salt'];
			$rpass=md5(md5($pass).$salt);
			if($r['0']['pass']==$rpass){
				//登录成功
				$ldata['loginsalt']=md5(md5($this->get_passwordssss(18)).$r['0']['id']);
				$lw['id']=$r['0']['id'];
				$User->where($lw)->limit(1)->save($ldata);
			
				setcookie("user", $r['0']['id'], time()+3600000,'/');
				setcookie("lsalt", $ldata['loginsalt'], time()+3600000,'/');
					//header('Location:/index.php/Home/PersonalProfile/');
					exit('lok');
			}else{
				exit('e3');
			}
		}
		exit;
    }
	//随机字符串
	function get_passwordssss( $length = 8 ){
		$str = substr(md5(time()), 0, 6);
		return $str;
	}

    
}

?>