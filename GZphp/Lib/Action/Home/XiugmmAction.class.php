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

class XiugmmAction extends BaseAction {

    public function index() {
		
        $uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		
		$mm=htmlspecialchars(trim($_GET['mm']));
		//手机短信验证码
		if($mm=='sjdxyzm'){
			$pass=get_safe_replace($_GET['pass']);
			$newpass=get_safe_replace($_GET['newpass']);
			
			$salt=$uinfo['salt'];
			$oldpass=md5(md5($pass).$salt);
			if($oldpass!=$uinfo['pass']) exit('e1'); //就密码错误
			
			//修改新密码
			//$newpass=$this->get_passwordssss(9);
			$salt=$this->get_passwordssss(8);
			$ldata['salt']=$salt;
			$ldata['pass'] = md5(md5($newpass).$salt);
			$User = M("huiyuan");
			$lw['id']=$uinfo['id'];
			$sidsup=$User->where($lw)->limit(1)->save($ldata);
			if($sidsup) {
				setcookie('user', '', time()-3600000,'/');
				setcookie('lsalt', '', time()-3600000,'/');
				exit('lok');
			}
			exit;
		}else{
			$this->display();
		}
    }
	//随机字符串
	function get_passwordssss( $length = 8 ){
		$str = substr(md5(time()), 0, 6);
		return $str;
	}
	//检测手机号否注册
	function ggssjjyyxx($sj){
		$User = M("huiyuan"); // 实例化User对象
		$w['tel']=$sj+0;
		$r=$User->where($w)->limit(1)->select();
		return $r['0'];
	}
	//检测邮箱是否注册
	function ggssjjyyxxyx($yx){
		$User = M("huiyuan"); // 实例化User对象
		$w['email']=$yx+0;
		$r=$User->where($w)->limit(1)->select();
		return $r['0']['id'];
	}
}

?>