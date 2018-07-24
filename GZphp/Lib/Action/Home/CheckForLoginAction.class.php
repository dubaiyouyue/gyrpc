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

class CheckForLoginAction extends BaseAction {

    public function index() {
			$uidinfo=$this->checklogincs();
			$suid=$uidinfo['id'];
	
		//新浪微博登录
		if(($_SESSION['sinatokenuid']+0)){
			$sinatokenuid=$_SESSION['sinatokenuid']+0;
			$u=M('huiyuan')->where('sinauid='.$sinatokenuid)->limit('1')->select();
			$uid=$u['0']['id'];
			//if(!$uid && $suid) $uid=$suid;
			if($uid){
				//已经绑定
				//登录成功
				$ldata['loginsalt']=md5(md5($this->get_passwordssss(18)).$uid);
				$lw['id']=$uid;
				$dok=M('huiyuan')->where($lw)->limit(1)->save($ldata);
			
				setcookie("user", $uid, time()+3600000,'/');
				setcookie("lsalt", $ldata['loginsalt'], time()+3600000,'/');
					header('Location:/index.php/Home/PersonalProfile/');
					exit();
			}else{
				//已登录绑定
				$uinfo=$this->checklogincs();
				$sinatokenuid=$_SESSION['sinatokenuid']+0;
				if($uinfo['id'] && $sinatokenuid){
					$w['id']=$uinfo['id'];
					$s['sinauid']=$sinatokenuid;
					$s['sinatx']=$_SESSION['sinatokenimage'];
					$s['sinaname']=$_SESSION['sinatokename'];
					$c=M('huiyuan')->where($w)->save($s);
					header('Location:/index.php/Home/PersonalProfile/?url=Dsfdl');
					exit;
				}
				header('Location:/index.php/Home/Bindingsina/index.html');
				exit();
			}
		}else if($_SESSION['qquid']){
			//qq登录
			$qquid=$_SESSION['qquid'];
			$u=M('huiyuan')->where('qquid=\''.$qquid.'\'')->limit('1')->select();
			$uid=$u['0']['id'];
			//if(!$uid && $suid) $uid=$suid;
			if($uid){
				//已经绑定
				//登录成功
				$ldata['loginsalt']=md5(md5($this->get_passwordssss(18)).$uid);
				$lw['id']=$uid;
				$dok=M('huiyuan')->where($lw)->limit(1)->save($ldata);
			
				setcookie("user", $uid, time()+3600000,'/');
				setcookie("lsalt", $ldata['loginsalt'], time()+3600000,'/');
					header('Location:/index.php/Home/PersonalProfile/');
					exit();
			}else{
				//已登录绑定
				$uinfo=$this->checklogincs();
				$qquid=$_SESSION['qquid'];
				if($uinfo['id'] && $qquid){
					$w['id']=$uinfo['id'];
					$s['qquid']=$qquid;
					$s['qqname']=$_SESSION['qqname'];
					$s['qqtx']=$_SESSION['qqtx'];
					$c=M('huiyuan')->where($w)->save($s);
					header('Location:/index.php/Home/PersonalProfile/?url=Dsfdl');
					exit;
				}
				header('Location:/index.php/Home/Bindingqq/index.html');
				exit();
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