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

class SubmitOrderokAction extends BaseAction {

    public function index() {
		//$username=iconv('GBK', 'UTF-8', $_GET['beizhu']);
		
		$fkbeizhuss=$_GET['beizhu'];
		$_GET['beizhu']=urldecode($_GET['beizhu']);
		//$this->assign('beizhu',$_GET['beizhu']);
		/*dump($_GET);
		exit;*/
	
        $uinfo=$this->checklogincs();
		if(!$uinfo['id']){
			header('Location:/index.php/login/');
			exit;
		}
		$this->assign('userinfoall',$uinfo);
		$uid=$uinfo['id'];
		$wd['id']=$_GET['dizhi']+0;
		$wd['uid']=$uid;
		$dizhi=M('dizhi')->where($wd)->find();
		$dizhi=$dizhi['sf'].$dizhi['shi'].$dizhi['qy'].$dizhi['xx'].' ,邮编 '.$dizhi['yb'].' ,姓名 '.$dizhi['xm'].' ,电话 '.$dizhi['tel'];
		
		$sd['dz']=$dizhi;//地址
		
		
		$carid=$_GET['carid'];
		$carid_arr=explode(',',$carid);
		$carid_arr_count=count($carid_arr);
		foreach($carid_arr as $ck=>$cv){
			$cv=$cv+0;
			if($cv){
				if(!$ck) $ospid=$cv;
				else $ospid.=','.$cv;
			}
		}
		//echo $ospid;exit;
		
		if($carid_arr_count>1) $a['id']=array('in',$ospid);
		else $a['id']=$ospid;
		$a['uid']=$uid;
		$scartlist=M('scart')->where($a)->select();
		
		//是否已经存在 刷新
		$have_caid_yes=$scartlist['0']['opid'];
		if($have_caid_yes){
			$ass['uid']=$uid;
			$ass['opid']=array('neq','0');
			if($carid_arr_count>1) $ass['id']=array('in',$ospid);
			else $ass['id']=$ospid;
			$scartlist=array();
			$scartlist=M('scart')->where($ass)->select();
		}
		
		//dump($scartlist);exit;
		foreach($scartlist as $v){
			if($spid) $spid.=','.$v['sid'];
			else $spid=$v['sid'];
			
			if($scartid) $scartid.=','.$v['id'];
			else $scartid=$v['id'];
			
		}
		$sw['id']=array('in',$spid);
		
		$slist=M('product')->field('id,title,url,price,cprice,thumb,jifen')->where($sw)->select();
		
		if(!$slist){
			header('Location:/index.php/Home/SubmitOrderok/no.html');
			exit;
		}
		
		foreach($scartlist as &$v){
			foreach($slist as $sv){
				if($sv['id']==$v['sid']){
					$v['price']=$sv['price'];
					$v['cprice']=$sv['cprice'];
					$v['title']=$sv['title'];
					$v['url']=$sv['url'];
					$v['thumb']=$sv['thumb'];
					if($sv['thumb']) $scartlistthumb=$sv['thumb'];
					$sjifen=$sjifen+$sv['jifen'];//送积分
					$zje=$zje+$sv['price']*$v['num'];
					$znum=$znum+$v['num'];
					
					$sinfo=$sinfo.$sv['title'].' '.$v['yanse'].$v['chicun'].' 单价'.$sv['price'].' 数量'.$v['num'].' 小计'.$sv['price']*$v['num'].' , ';
				}
			}
		}
		
		//会员积分
		//dump($uinfo);
		$userinfoalljifen=$uinfo['jifen'];
		$jifen=$_GET['jifen']+0;
		if($jifen>$userinfoalljifen) $jifen=$userinfoalljifen;
		//积分减少金额
		$allconfig=($this->Config);
		$jifenduihuan=$allconfig['jifenduihuan'];
		$jsje=$jifen/$jifenduihuan;
		$zje=$zje-$jsje+$allconfig['youfei'];
		//exit;
		
		$sinfo=$sinfo.' 总数量'.$znum.' 总金额'.$zje;
		//dump($sinfo);exit;
		//使用积分 不赠送积分
		if($jifen) $sjifen=0;
		
		$sd['info']=$sinfo;//商品信息
		$sd['onum']=time().rand(100000,999999);
		$sd['userid']=$uid;
		$sd['zje']=$zje;
		$sd['znum']=$znum;
		$sd['thumb']=$scartlistthumb;
		$sd['createtime']=time();
		$sd['updatetime']=time();
		$sd['beizhu']=$_GET['beizhu'];
		$sd['jifen']=$jifen;
		$sd['sid']=$spid;
		$sd['scartid']=$scartid;
		$sd['sjifen']=$sjifen;
		$sd['youfei']=$allconfig['youfei'];
		//添加购物车
		if(!$have_caid_yes) $sdingdan=M('dingdan')->add($sd);
		if($sdingdan){
			//删除购物车
			/*$dw['uid']=$uid;
			$dw['id']=$a['id'];
			$dc=M('scart')->where($dw)->delete();*/
			
			$dw['uid']=$uid;
			$dw['id']=$a['id'];
			$ds['opid']=$sdingdan;
			//$ds['uid']=0;
			$dc=M('scart')->where($dw)->save($ds);
			
		}
		if(!$sdingdan && !$have_caid_yes){
			header('Location:/index.php/Home/SubmitOrderok/no.html');
			exit;
		}
		if(!$sdingdan && $have_caid_yes) $sdingdan=$have_caid_yes;
		
			//获取订单时间
			$ctimeupp['userid']=$uid;
			$ctimeupp['id']=$sdingdan;
			$createtimeupp=M('dingdan')->where($ctimeupp)->find();
			//echo date('Y/m/d H:i:s',($createtimeupp['createtime']+1800));exit;
			$this->assign('createtimeupp',$createtimeupp['createtime']);
		
		if(($createtimeupp['createtime']+1800)<time()){
			$suppqxxxdingdan['tstatus']=2;
			$uppqxxxdingdanw['id']=$sdingdan;
			$uppqxxxdingdanw['userid']=$uid;
			$ddcreatetimeupp=M('dingdan')->where($uppqxxxdingdanw)->save($suppqxxxdingdan);
			header('Location:/index.php/Home/SubmitOrderok/noqx.html');
			exit;
		}
		
		
		
		$this->assign('sdingdan',$sdingdan);
		$this->assign('ospid',$ospid);
		$fkfs=$_GET['fkfs']+0;
		if(!$fkfs) $fkfs=3;
		if($fkfs==4){
			header('Location:/index.php/Home/SubmitOrder/index/gmid/'.$ospid.'/ddfkok/ok/qfkfs/'.$_GET['qfkfs'].'/jifen/'.$jifen.'/dizhi/'.$_GET['dizhi'].'.html?beizhu='.$fkbeizhuss);
			exit;
		}else if($fkfs==3){
			//余额支付
			$nowyue=$uinfo['yue'];
			if($nowyue<$zje){
				$this->display('./GZphp/Tpl/Home/Default/SubmitOrderok_yue.html');
			}else{
				//更新会员余额
				$yuew['id']=$uid;
				$yues['yue']=array('exp','yue-'.$zje);
				//更新积分 增加
				if($sjifen){
					//增加
					$yues['jifen']=array('exp','jifen+'.$sjifen);
				}else if($jifen){
					$yues['jifen']=array('exp','jifen-'.$jifen);
				}
				$yuemm=M('huiyuan')->where($yuew)->save($yues);
				
				//添加会员消费记录
				$hyxfjla['uid']=$uid;
				$hyxfjla['je']=$zje;
				$hyxfjla['fkfs']=3;
				$hyxfjla['ctime']=time();
				$hyxfjla['info']=1;
				$hyxfjla['gtype']=1;
				$hyxfjla['ctype']='pc';
				$hyxfjla['oid']=time().rand(111111,999999);
				$hyxfjlmm=M('hyxfjl')->add($hyxfjla);
				
				//更新订单
				$dingdanw['userid']=$uid;
				$dingdanw['id']=$sdingdan;
				$dingdanw['zje']=$zje;
				$dingdans['fk']=1;
				$dingdans['tstatus']=1;
				$dingdans['oid']=$oid;
				$dingdanmm=M('dingdan')->where($dingdanw)->save($dingdans);
				
					//更新积分记录
				$jifenjilua['uid']=$uid;
				$jifenjilua['laiyuan']=$sdingdan;
				$jifenjilua['ctime']=time();
				$jifenjilua['jifen']=$jifen;
				$jifenjilua['jtype']=2;//增加 1增加 2使用
				if($sjifen){
					$jifenjilua['jifen']=$sjifen;
					$jifenjilua['jtype']=1;
				}
				$jifenjilumm=M('jifenjilu')->add($jifenjilua);
				
				header('Location:/index.php/Home/PersonalProfile/?url=Allorid&tishi=1&didnow='.$sdingdan);
				exit;
			}
		}
		else if($fkfs==2){
			//微信支付
			$url = APP_DEBUGwxxxxx."wx/example/native.php";
			$post_data = array (
				"Body" => "广羽人",
				"Attach"=>'1#'.$uid.'#'.$sdingdan.'#pc#'.$sjifen.'#'.$jifen,
				'Total_fee'=>$zje,
				'Product_id'=>$sdingdan
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// post数据
			curl_setopt($ch, CURLOPT_POST, 1);
			// post的变量
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			$output = curl_exec($ch);
			curl_close($ch);
			//打印获得的数据
			//print_r($output);
			
			
			//获取购物车
			$anum=0;
			if($uid){
				$ass['uid']=$uid;
				$ass['opid']=array('eq','0');
				$scartlist=M('scart')->where($ass)->select();
				$anum=count($scartlist)+0; //购物车商品数量
				if($anum>9) $anum='+';
			}
			$this->assign('uidnum',$anum);
			//end
			
			
			
			$this->assign('zje',$zje);
			

			
			$wximg=APP_DEBUGwxxxxx.'wx/example/qrcode.php?data='.$output;
			$this->assign('wximg',$wximg);
			$this->display('./GZphp/Tpl/Home/Default/SubmitOrderok_wx.html');
		}else{
			//支付宝
			$this->display('./GZphp/Tpl/Home/Default/SubmitOrderok_zfb.html');
		}
		

	}
	public function no(){
		$this->display('');
	}
	
}

?>