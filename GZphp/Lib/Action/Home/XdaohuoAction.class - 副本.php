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

class XdaohuoAction extends BaseAction {

    public function index() {
		
        $uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		$this->assign('userinfoall',$uinfo);
		$uid=$uinfo['id'];
		
		$w['uid']=$uid;
		$list=M('unotification')->where($w)->select();
		
		//dump($list);exit;
		
		foreach($list as $k=>$v){
			$newlist[$v['sid']]=$v;
			//$this->thisku($get_id,$get_cm,$get_ys);
		}
		foreach($newlist as $k=>$v){
			$nowkc=$this->thisku($v['sid'],$v['chicun'],$v['yanse']);
			if($nowkc){
				/*$newlist[$v['sid']]['kc']=$nowkc;
				$newlist[$v['sid']]['chicun']=$v['chicun'];
				$newlist[$v['sid']]['yanse']=$v['yanse'];*/
				if($kclist) $kclist=','.$v['sid'];
				else $kclist=$v['sid'];
			}
		}
		
		$w['id']=array('in',$kclist);
		$list=M('product')->where($w)->select();
		
		//echo $kclist;
		$this->assign('list',$list);
		$this->assign('newlist',$newlist);
		$this->display();
	}
	
}

?>