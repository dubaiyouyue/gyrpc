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

class InfouccAction extends BaseAction {

    public function index() {
		
		/*$_POST['id']='2';
		$_POST['loginsalt']='c5afa4368e45074bdb35543eaf6aa977';
		$_POST['token']='7302fba764ced1a067ca9f0b6ce44a6d250cf8b51c773f3f8dc8b4be867a9a02';
		$_POST['mm']='b';
		$_POST['nicheng']='测试昵称';
		$_POST['name']='测试姓名';
		$_POST['sex']='女';
		$_POST['srn']='生日年';
		$_POST['sry']='生日月';
		$_POST['srr']='生日日';
		$_POST['szds']='所在地省';
		$_POST['szdsq']='所在地市';
		$_POST['szdq']='所在地区';
		$_POST['jxs']='家乡省1111';
		$_POST['jxss']='家乡市';
		$_POST['jxq']='家乡区';*/
		
		/*$_POST['id']='2';
		$_POST['loginsalt']='c5afa4368e45074bdb35543eaf6aa977';
		$_POST['token']='7302fba764ced1a067ca9f0b6ce44a6d250cf8b51c773f3f8dc8b4be867a9a02';
		$_POST['mm']='p';
		$_POST['passold']='111111';
		$_POST['passnew']='123456';*/
		
			$mm=$_POST['mm'];
			$token=get_safe_replace($_POST['token']); //令牌
			$id=get_safe_replace($_POST['id'])+0; //会员uid
			$loginsalt=get_safe_replace($_POST['loginsalt']);//登录凭证
			$User = M("huiyuan");
			$w['id']=$id;
			$w['token']=$token;
			$w['loginsalt']=$loginsalt;
		
			
			
			//基本资料修改
			if($mm=='b'){
				$data['nicheng'] = get_safe_replace($_POST['nicheng']);//昵称
				$data['name'] = get_safe_replace($_POST['name']);//真实姓名
				$data['sex'] = get_safe_replace($_POST['sex']);//性别
				
				$data['srn'] = get_safe_replace($_POST['srn']);//生日年
				$data['sry'] = get_safe_replace($_POST['sry']);//生日月
				$data['srr'] = get_safe_replace($_POST['srr']);//生日日
				
				$data['szds'] = get_safe_replace($_POST['szds']);//所在地省
				$data['szdsq'] = get_safe_replace($_POST['szdsq']);//所在地市
				$data['szdq'] = get_safe_replace($_POST['szdq']);//所在地区
				
				$data['jxs'] = get_safe_replace($_POST['jxs']);//家乡省
				$data['jxss'] = get_safe_replace($_POST['jxss']);//家乡市
				$data['jxq'] = get_safe_replace($_POST['jxq']);//家乡区
				
				$r=$User->where($w)->save($data);
				if($r) echo 'sok';
			}else if($mm=='p' && $_POST['passold'] && $_POST['passnew']){
				//密码修改
				//$data['salt'] = $this->get_passwordssss(8);
				//旧密码
				$passold=$_POST['passold'];
				//新密码
				$passnew=$_POST['passnew'];
				$r=$User->where($w)->limit(1)->select();
				$salt=$r['0']['salt'];
				$pass=$r['0']['pass'];
				$passold=md5(md5($passold).$salt);
				if($pass==$passold){
					//修改密码
					$s=$this->get_passwordssss(8);
					$lsalt=$this->get_passwordssss(18);
					$d['pass']=md5(md5($passnew).$s);
					$d['salt']=$s;
					$d['loginsalt']=md5(md5($lsalt).$id);
					$ss=$User->where($w)->save($d);
					if($ss) echo 'sok';
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