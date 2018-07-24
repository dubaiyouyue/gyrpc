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

class RegisterAction extends BaseAction {

    public function index() {
        $mm=htmlspecialchars(trim($_GET['mm']));
		//dump($_SESSION);exit;
		
		//手机短信验证码
		if($mm=='sjdxyzm'){
			$tel=get_safe_replace($_GET['tel']);
			$yx=get_safe_replace($_GET['yx']);
			$code=md5($_GET['code']);
			if($code!=$_SESSION['verify'] || !$_SESSION['verify']){
				$_SESSION['verify']='';
				exit('e1'); //验证码错误
			}
			$sjs=$this->ggssjjyyxx($tel);
			if($sjs) exit('e2'); //手机号已经注册
			$sjsyx=$this->ggssjjyyxxyx($yx);
			if($sjsyx) exit('e3'); //邮箱已经注册
			//发送短信手机号
			$_SESSION['dxyzmtelzhuimim']=0;
			$_SESSION['verifysms']=$_SESSION['verify'];
			$_SESSION['dxyzmtel']=$tel;//2016.11.18
			//验证码
			//$dxyzm=rand(1000,9999);
			$_SESSION['dxyzmssss']=$this->GetRandStrssss(6);
			//echo $_SESSION['dxyzmssss'];
			exit;
		}
		//提交注册前验证
		if($mm=='sjdxyzmzz'){
			//echo md5(079959);
			//dump($_SESSION);
			//exit;
			//echo $_SESSION['dxyzm'];exit;
			$tel=get_safe_replace($_GET['tel']);
			$yx=get_safe_replace($_GET['yx']);
			$code=md5(get_safe_replace($_GET['code']));
			if($code!=$_SESSION['verify'] || !$_SESSION['verify']) exit('e1'); //验证码错误
			$sjs=$this->ggssjjyyxx($tel);
			if($sjs) exit('e2'); //手机号已经注册
			$sjsyx=$this->ggssjjyyxxyx($yx);
			if($sjsyx) exit('e3'); //邮箱已经注册
			
			if($_GET['dxyzm']!=$_SESSION['dxyzmssss'] || !$_SESSION['dxyzmssss']) exit('e4'); //短信验证码错误
			exit;
		}
		//注册
		if($_POST){
			if(!$_POST['tel'] || !$_POST['email'] || !$_POST['nicheng'] || !$_POST['name']){
				header('Location:/index.php/register/');
				exit;
			}
			$sjs=$this->ggssjjyyxx(get_safe_replace($_POST['tel']));
			$sjsyx=$this->ggssjjyyxxyx(get_safe_replace($_POST['email']));
			if($sjs || $sjsyx){
				//防止重复注册
				header('Location:/index.php/register/');
				exit;
			}
			if(md5(get_safe_replace($_POST['yzm']))!=$_SESSION['verify']) {
				//验证码错误
				header('Location:/index.php/register/');
				exit;
			}
			if($_POST['dxyzm']!=$_SESSION['dxyzmssss'] || !$_SESSION['dxyzmssss']) {
				//短信验证码错误
				header('Location:/index.php/register/');
				exit;
			}
			
			$User = M("huiyuan"); // 实例化User对象
			$data['nicheng'] = get_safe_replace($_POST['nicheng']);
			$data['name'] = get_safe_replace($_POST['name']);
			//$data['tel'] = get_safe_replace($_POST['tel']);
			//发送短信手机号
			$data['tel'] = $_SESSION['dxyzmtel'];
			//验证手机号、邮箱是否被非法修改
			$data['sex'] = get_safe_replace($_POST['sex']);
			$data['email'] = get_safe_replace($_POST['email']);
			$data['srn'] = get_safe_replace($_POST['srn']);
			$data['sry'] = get_safe_replace($_POST['sry']);
			$data['srr'] = get_safe_replace($_POST['srr']);
			$data['lang'] = 1;
			//$data['pass'] = get_safe_replace($_POST['pass']);
			$data['szds'] = get_safe_replace($_POST['szds']);
			$data['szdsq'] = get_safe_replace($_POST['szdsq']);
			$data['szdq'] = get_safe_replace($_POST['szdq']);
			$data['jxs'] = get_safe_replace($_POST['jxs']);
			$data['jxss'] = get_safe_replace($_POST['jxss']);
			$data['jxq'] = get_safe_replace($_POST['jxq']);
			$data['createtime'] = time();
			$data['salt'] = $this->get_passwordssss(8);
			$data['lsalt']=$this->get_passwordssss(18);
			$data['pass'] = md5(md5($_POST['pass']).$data['salt']);
			
			//qq weibo
			$data['sinauid']=$_SESSION['sinatokenuid'];
			$data['sinatx']=$_SESSION['sinatokenimage'];
			$data['sinaname']=$_SESSION['sinatokename'];
			$data['qquid']=$_SESSION['qquid'];
			$data['qqname']=$_SESSION['qqname'];
			$data['qqtx']=$_SESSION['qqtx'];
			//end
			
			
			$adduserid=$User->add($data);
			
			$ldata['loginsalt']=md5(md5($data['lsalt']).$adduserid);
			$lw['id']=$adduserid;
			$User->where($lw)->limit(1)->save($ldata);
			
			setcookie("user", $adduserid, time()+3600000,'/');
			setcookie("lsalt", $ldata['loginsalt'], time()+3600000,'/');
				header('Location:/index.php/Home/PersonalProfile/');
				exit;
			
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
		return $r['0']['id'];
	}
	//检测邮箱是否注册
	function ggssjjyyxxyx($yx){
		$User = M("huiyuan"); // 实例化User对象
		$w['email']=$yx+0;
		$r=$User->where($w)->limit(1)->select();
		return $r['0']['id'];
	}
	function GetRandStrssss($len){ 
		$chars = array("0", "1", "2","3", "4", "5", "6", "7", "8", "9"); 
		$charsLen = count($chars) - 1; 
		shuffle($chars);   
		$output = ""; 
		for ($i=0; $i<$len; $i++) 
		{ 
			$output .= $chars[mt_rand(0, $charsLen)]; 
		}  
		return $output;  
	}
    
}

?>