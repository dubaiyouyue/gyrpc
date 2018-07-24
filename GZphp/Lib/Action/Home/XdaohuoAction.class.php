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
			//$newlist[$v['sid']]=$v;
			//$this->thisku($get_id,$get_cm,$get_ys);
			if($kclist) $kclist.=','.$v['sid'];
			else $kclist=$v['sid'];
		}
		/*echo $allsid;exit;
		foreach($newlist as $k=>$v){
			$nowkc=$this->thisku($v['sid'],$v['chicun'],$v['yanse']);
			if($nowkc){
				/*$newlist[$v['sid']]['kc']=$nowkc;
				$newlist[$v['sid']]['chicun']=$v['chicun'];
				$newlist[$v['sid']]['yanse']=$v['yanse'];*/
				/*if($kclist) $kclist=','.$v['sid'];
				else $kclist=$v['sid'];
			}
		}*/
		
		$w['id']=array('in',$kclist);
		//$w['kc']=array('like','%\''..'\'%');
		$listp=M('product')->field('title,id,thumb,price,cprice,kc,url')->where($w)->select();
		
		foreach($listp as $K=>$v){
			$newlistp[$v['id']]=$v;
			/*$kc_arr=explode('moweilin',$v['kc']);
			foreach($kc_arr as $kk=>$kv){
				$kc_arr=explode('lizhenqiu',$kv);
				//dump($kc_arr);exit;
				$new_kc_arr[$v['id']][]=$kc_arr;
			}*/
		}
		//dump($newlistp);exit;
		//echo $this->thisku(26,'S','白色');exit;
		foreach($list as $lk=>$lv){
				$newkc=$this->thisku($lv['sid'],$lv['chicun'],$lv['yanse']);
				//dump($newkc);
				if($newkc){
					$have_kc_arr[$lv['id']]['sid']=$lv['sid'];
					$have_kc_arr[$lv['id']]['yanse']=$lv['yanse'];
					$have_kc_arr[$lv['id']]['chicun']=$lv['chicun'];
					$have_kc_arr[$lv['id']]['title']=$newlistp[$lv['sid']]['title'];
					$have_kc_arr[$lv['id']]['url']=$newlistp[$lv['sid']]['url'];
					$have_kc_arr[$lv['id']]['thumb']=$newlistp[$lv['sid']]['thumb'];
					$have_kc_arr[$lv['id']]['id']=$lv['id'];
					$have_kc_arr[$lv['id']]['newkc']=$newkc;
				}
				/*exit;
		
			$lvkc=$lv['yanse'].'lizhenqiu'.$lv['chicun'].'lizhenqiulizhenqiu';
			$newlistp[$lv['sid']]['kc'];
			if(stripos($newlistp[$lv['sid']]['kc'],$lvkc)){
				if(!stripos($newlistp[$lv['sid']]['kc'],$lvkc.'0')){
					//echo $lv['sid'];
					$have_kc_arr[$lv['id']]['sid']=$lv['sid'];
					$have_kc_arr[$lv['id']]['yanse']=$lv['yanse'];
					$have_kc_arr[$lv['id']]['chicun']=$lv['chicun'];
					$have_kc_arr[$lv['id']]['title']=$newlistp[$lv['sid']]['title'];
					$have_kc_arr[$lv['id']]['url']=$newlistp[$lv['sid']]['url'];
					$have_kc_arr[$lv['id']]['thumb']=$newlistp[$lv['sid']]['thumb'];
					$have_kc_arr[$lv['id']]['id']=$lv['id'];
					//$have_kc_arr[]['kc']=$lv['kc'];
				}
				//echo $lv['sid'];
			}*/
			//echo in_array($lv['yanse'],$new_kc_arr);exit;
			/*foreach($new_kc_arr as $newk=>$newv){
				黑色lizhenqiuSlizhenqiulizhenqiu99
				foreach($newv as $nnk=>$nnv){
					//dump($nnv);
					//echo $lv['yanse'];
					//echo $nnv['0'];
					if($lv['yanse']==$nnv['0'] && $lv['chicun']==$nnv['1']){
						echo $nnv['3'];
					}
				}
			}*/
		}
		//dump($have_kc_arr);exit;
		//echo $kclist;
		//$this->assign('list',$list);
		$this->assign('have_kc_arr',$this->array_unique_fb($have_kc_arr));
		$this->display();
	}
	//二维数组去掉重复值
	function array_unique_fb($array2D){
	 foreach ($array2D as $v){
	  $v=join(',',$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
	  $temp[]=$v;
	 }
	 $temp=array_unique($temp); //去掉重复的字符串,也就是重复的一维数组
	 foreach ($temp as $k => $v){
	  $temp[$k]=explode(',',$v); //再将拆开的数组重新组装
	 }
	 return $temp;
	}
	public function delll(){
		$id=$_GET['did']+0;
        $uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		$this->assign('userinfoall',$uinfo);
		$uid=$uinfo['id'];
		
		if($id){
			$w['uid']=$uid;
			$w['id']=$id;
			$list=M('unotification')->where($w)->delete();
		}
			header('Location:/index.php/Home/PersonalProfile/?url=Xdaohuo');
			exit;
	}
}

?>