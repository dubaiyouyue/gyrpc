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

class ZhuimimAction extends BaseAction {

    public function index() {
		$mm=htmlspecialchars(trim($_GET['mm']));
		//手机短信验证码
		if($mm=='sjdxyzm'){
			$tel=get_safe_replace($_GET['tel']);
			$yx=get_safe_replace($_GET['email']);
			$code=md5(get_safe_replace($_GET['yzm']));
			if($code!=$_SESSION['verify'] || !$_SESSION['verify']) {
				$_SESSION['verify']='';
				exit('e1'); //验证码错误
			}
			$sjs=$this->ggssjjyyxx($tel);
			if(!$sjs['id']) exit('e2'); //手机号未注册
			//$sjsyx=$this->ggssjjyyxxyx($yx);
			//if($sjs['email']!=$yx) exit('e3'); //邮箱错误
			//修改新密码
			$newpass=$this->get_passwordssss(9);
			
			//sms
			$_SESSION['dxyzmtelzhuimim']=1;
			$_SESSION['dxyzmtel']=$tel;
			$_SESSION['verifysms']=$_SESSION['verify'];
			$_SESSION['dxyzmssss']=$newpass;
			//end
			
			$salt=$this->get_passwordssss(8);
			$ldata['salt']=$salt;
			$ldata['pass'] = md5(md5($newpass).$salt);
			
			//qq weibo
			/*if($_SESSION['sinatokenuid']){
				$ldata['sinauid']=$_SESSION['sinatokenuid'];
				$ldata['sinatx']=$_SESSION['sinatokenimage'];
				$ldata['sinaname']=$_SESSION['sinatokename'];
			}
			if($_SESSION['qquid']){
				$ldata['qquid']=$_SESSION['qquid'];
				$ldata['qqname']=$_SESSION['qqname'];
				$ldata['qqtx']=$_SESSION['qqtx'];
			}*/
			//end
			
			
			$User = M("huiyuan");
			$lw['id']=$sjs['id'];
			$sidsup=$User->where($lw)->limit(1)->save($ldata);
			if($sidsup) exit('lok');
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