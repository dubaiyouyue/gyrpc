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

class ShuodzAction extends BaseAction {

    public function index() {
        $uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		
		$uid=$uinfo['id'];
		$w['uid']=$uid;
		$list=M('dizhi')->where($w)->select();
		$this->assign('list',$list);
		
		$this->display();
		
    }
	public function del(){
        $uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		$id=$_GET['id']+0;
		if(!$id) exit;
		$uid=$uinfo['id'];
		$w['id']=$id;
		$w['uid']=$uid;
		$list=M('dizhi')->where($w)->delete();
			header('Location:/index.php/Home/PersonalProfile/?url=Shuodz');
			exit;
		
		
	}
	public function newsave(){
        $uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		$uid=$uinfo['id'];
		//dump($_POST);exit;
		$sf=$_POST['sf'];
		$shi=$_POST['shi'];
		$qy=$_POST['qy'];
		$xx=$_POST['xx'];
		$yb=$_POST['yb'];
		$xm=$_POST['xm'];
		$tel=$_POST['tel'];
		$moren=$_POST['moren']+0;
		
		if($moren){
			$w['uid']=$uid;
			$smoren['moren']=0;
			$u=M('dizhi')->where($w)->save($smoren);
		}
		
		$as['sf']=$sf;
		$as['shi']=$shi;
		$as['qy']=$qy;
		$as['xx']=$xx;
		$as['yb']=$yb;
		$as['xm']=$xm;
		$as['tel']=$tel;
		$as['moren']=$moren;
		$as['uid']=$uid;
		$a=M('dizhi')->add($as);
		
		if($a){
			header('Location:/index.php/Home/PersonalProfile/?url=Shuodz');
			exit;
		}
	}
	public function moren(){
        $uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		$uid=$uinfo['id'];
		$id=$_GET['id']+0;
			if($id){
				$w['uid']=$uid;
				$smoren['moren']=0;
				$u=M('dizhi')->where($w)->save($smoren);
				$w['id']=$id;
				$smoren['moren']=1;
				$ua=M('dizhi')->where($w)->save($smoren);
				if($ua){
					header('Location:/index.php/Home/PersonalProfile/?url=Shuodz');
					exit;
				}
			}
	}
}

?>