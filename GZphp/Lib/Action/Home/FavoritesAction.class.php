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

class FavoritesAction extends BaseAction {

    public function index() {
        $uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		
		$sex=$_GET['sex']+0;
		$scartlist=$this->flist($sex);
		
		//全部数量
		$anum=count($this->flist())+0;
		
		
		$this->assign('anum',$anum);
		//男数量
		$anum=count($this->flist(1))+0;
		$this->assign('anumo',$anum);
		//女数量
		$anum=count($this->flist(2))+0;
		$this->assign('anumt',$anum);
		
		$this->assign('sex',$sex);
		$this->assign('scartlist',$scartlist);
		$this->display();
		
    }
	public function flist($sex){
		$uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		
		$fw['uid']=$uinfo['id'];
		$fw['type']=1;
		$scartlist=M('collection')->where($fw)->order('id desc')->select();
		if(!$scartlist['0']['id']) return;
		
		//获取商品id array_unique
		foreach($scartlist as $v){
			if($spid) $spid.=','.$v['sid'];
			else $spid=$v['sid'];
		}
		
		//echo $spid;
		if($sex) $sw['sex']=array('eq',$sex);
		$sw['id']=array('in',$spid);
		//dump($sw);exit;
		$slist=M('product')->field('id,title,url,price,cprice,thumb')->where($sw)->select();
		//dump($slist);exit;
		if(empty($slist)) return $slist;
		
		
		foreach($scartlist as &$v){
			foreach($slist as $sv){
				if($sv['id']==$v['sid']){
					$v['price']=$sv['price'];
					$v['cprice']=$sv['cprice'];
					$v['title']=$sv['title'];
					$v['url']=$sv['url'];
					$v['thumb']=$sv['thumb'];
				}
			}
		}
		foreach($scartlist as $kkv=>$ssv){
			if($ssv['title']){
				$scartlistnew[]=$ssv;
			}
		}
		//dump($scartlist);exit;
		return array_filter($scartlistnew);
		//dump($scartlist);exit;
		//$this->assign('anum',$anum);
		//$this->assign('scartlist',$scartlist);
	}
	//删除
	function del(){
		$uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		$id=$_GET['id']+0;
		if(!$id) exit;
		$uid=$uinfo['id'];
		$fw['uid']=$uid;
		$fw['id']=$id;
		$scartlist=M('collection')->where($fw)->delete();
		header('Location:/index.php/Home/PersonalProfile/?url=Favorites');
		exit;
	}
}

?>