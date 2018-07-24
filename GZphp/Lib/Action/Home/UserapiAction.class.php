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

class UserapiAction extends BaseAction {
	
	function index(){
		//echo json_encode($_POST); 
		
	}
	function regiser(){
		//注册
		$tel=get_safe_replace($_POST['tel'])+0;
		$email=get_safe_replace($_POST['email']);
		//$pass=get_safe_replace($_POST['pass']);
			$sjs=$this->ggssjjyyxx($tel);
			if($sjs) exit('a1'); //手机号已经注册
			$sjsyx=$this->ggssjjyyxxyx($email);
			if($sjsyx) exit('a2'); //邮箱已经注册
			
			$User = M("huiyuan");
			$data['lang'] = 1;
			$data['createtime'] = time();
			$data['salt'] = $this->get_passwordssss(8);
			$data['pass'] = md5(md5($_POST['pass']).$data['salt']);
			$data['tel'] = $tel;
			$data['email'] = $email;
			$data['token']=$_POST['token'];
			echo $adduserid=$User->add($data);
				//注册成功
				if($adduserid){
					//$data['lsalt']=get_safe_replace($_POST['lsalt']);//$this->get_passwordssss(18);
					$ldata['loginsalt']=get_safe_replace($_POST['loginsalt']);//md5(md5($data['lsalt']).$adduserid);
					$lw['id']=$adduserid;
					$User->where($lw)->limit(1)->save($ldata);
				}
			
			
			exit;
	}
	//随机字符串
	function get_passwordssss( $length = 8 ){
		$str = substr(md5(time()), 0, 6);
		return $str;
	}
    public function check() {
		//检测手机号、邮箱是否登录
		$tel=get_safe_replace($_POST['tel'])+0;
		$email=get_safe_replace($_POST['email']);
			$sjs=$this->ggssjjyyxx($tel);
			if($sjs) exit('e2'); //手机号已经注册
			$sjsyx=$this->ggssjjyyxxyx($email);
			if($sjsyx) exit('e3'); //邮箱已经注册
    }
	//检测手机号否注册
	function ggssjjyyxx($sj){
		$User = M("huiyuan"); // 实例化User对象
		$w['tel']=$sj+0;
		$r=$User->where($w)->limit(1)->select();
		return $r['0']['id'];
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