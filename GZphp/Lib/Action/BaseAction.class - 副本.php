<?php
/**
 * 
 * Base (前台公共模块)
 *
 * @package      	GZPHP
 * @author          wen QQ:52009619 <admin@resonance.com.cn>
 * @copyright     	Copyright (c) 2008-2011  (http://www.resonance.com.cn)
 * @license         http://www.resonance.com.cn/license.txt
 * @version        	GzPHP企业网站管理系统 v2.1 2011-03-01 resonance.com.cn $
 */
if(!defined("GZPHP"))  exit("Access Denied");
	//$_SESSION['dxyzm']=md5(123456);//2016.11.16 短信验证码
	//echo md5(md5('123456').'21191f');exit;
class BaseAction extends Action
{
	//检测是否登录
	public function checklogincs(){
		$User = M("huiyuan"); // 实例化User对象
		$w['id']=$_COOKIE['user']+0;
		$r=$User->where($w)->limit(1)->select();
		$loginsalt=$r['0']['loginsalt'];
		if($loginsalt==$_COOKIE['lsalt'] && $loginsalt) return $r['0'];
	}
	
	protected   $Config ,$sysConfig,$categorys,$module,$moduleid,$mod,$dao,$Type,$Role,$_userid,$_groupid,$_email,$_username ,$forward ,$user_menu,$Lang,$member_config;
    public function _initialize() {

			$this->sysConfig = F('sys.config');
			$this->module = F('Module');
			$this->Role = F('Role');
			$this->Type =F('Type');
			$this->mod= F('Mod');
			$this->moduleid=$this->mod[MODULE_NAME];
			if(APP_LANG){
				$this->Lang = F('Lang');
				$this->assign('Lang',$this->Lang);
				if(get_safe_replace($_GET['l'])){
					if(!$this->Lang[$_GET['l']]['status'])$this->error ( L ( 'NO_LANG' ) );
					$lang=$_GET['l'];
				}else{
					$lang=$this->sysConfig['DEFAULT_LANG'];
				}
				define('LANG_NAME', $lang);
				define('LANG_ID', $this->Lang[$lang]['id']);
				$this->categorys = F('Category_'.$lang);
				$this->Config = F('Config_'.$lang);
				$this->assign('l',$lang);
				$this->assign('langid',LANG_ID);
				$T = F('config_'.$lang,'', APP_PATH.'Tpl/Home/'.$this->sysConfig['DEFAULT_THEME'].'/');
				C('TMPL_CACHFILE_SUFFIX','_'.$lang.'.php');
				cookie('think_language',$lang);
			}else{
				$T = F('config_'.$this->sysConfig['DEFAULT_LANG'],'',  APP_PATH.'Tpl/Home/'.$this->sysConfig['DEFAULT_THEME'].'/');
				$this->categorys = F('Category');
				$this->Config = F('Config');
				cookie('think_language',$this->sysConfig['DEFAULT_LANG']);
			}
			$this->assign('T',$T);
			$this->assign($this->Config);
			$this->assign('Role',$this->Role);
			$this->assign('Type',$this->Type);
			$this->assign('Module',$this->module);
			$this->assign('Categorys',$this->categorys);
			
			import("@.ORG.Form");			
			$this->assign ( 'form',new Form());
 
			C('HOME_ISHTML',$this->sysConfig['HOME_ISHTML']);
			C('PAGE_LISTROWS',$this->sysConfig['PAGE_LISTROWS']);
			C('URL_M',$this->sysConfig['URL_MODEL']);
			C('URL_M_PATHINFO_DEPR',$this->sysConfig['URL_PATHINFO_DEPR']);
			C('URL_M_HTML_SUFFIX',$this->sysConfig['URL_HTML_SUFFIX']);
			C('URL_LANG',$this->sysConfig['DEFAULT_LANG']);
			C('DEFAULT_THEME_NAME',$this->sysConfig['DEFAULT_THEME']);


			import("@.ORG.Online");
			$session = new Online();
			if(cookie('auth')){
				$gzphp_auth_key = sysmd5($this->sysConfig['ADMIN_ACCESS'].$_SERVER['HTTP_USER_AGENT']);
				list($userid,$groupid, $password) = explode("-", authcode(cookie('auth'), 'DECODE', $gzphp_auth_key));
				$this->_userid = $userid;
				$this->_username =  cookie('username');
				$this->_groupid = $groupid; 
				$this->_email =  cookie('email');
			}else{
				$this->_groupid = cookie('groupid') ?  cookie('groupid') : 4;
				$this->_userid =0;
			}


			foreach((array)$this->module as $r){
				if($r['issearch'])$search_module[$r['name']] = L($r['name']);
				if($r['ispost'] && (in_array($this->_groupid,explode(',',$r['postgroup']))))$this->user_menu[$r['id']]=$r;
			}
			if(GROUP_NAME=='User'){
				$langext = $lang ? '_'.$lang : '';
				$this->member_config=F('member.config'.$langext);
				$this->assign('member_config',$this->member_config);
				$this->assign('user_menu',$this->user_menu);
				if($this->_groupid=='5' &&  MODULE_NAME!='Login'){ 
					$this->assign('jumpUrl',URL('User-Login/emailcheck'));
					$this->assign('waitSecond',3);
					$this->success(L('no_regcheckemail'));
					exit;
				}
				$this->assign('header',TMPL_PATH.'Home/'.THEME_NAME.'/Home_header.html');
			}
			if($_GET['forward'] || $_POST['forward']){	
				$this->forward = get_safe_replace($_GET['forward'].$_POST['forward']);
			}else{
				if(MODULE_NAME!='Register' || MODULE_NAME!='Login' )
				$this->forward =isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] :  $this->Config['site_url'];
			}
			$this->assign('forward',$this->forward);
			$this->assign('search_module',$search_module);
			$this->assign('module_name',MODULE_NAME);
			$this->assign('action_name',ACTION_NAME);
			//会员信息
			$uidinfoall=$this->checklogincs();
			$this->assign('userinfoall',$uidinfoall);//记得加后括号 默认不加括号 输出 boolean false
			
			//获取购物车
			$uid=$uidinfoall['id'];
			$anum=0;
			if($uid){
				$a['uid']=$uid;
				$a['opid']=array('eq','0');
				$scartlist=M('scart')->where($a)->select();
				$anum=count($scartlist)+0; //购物车商品数量
				if($anum>9) $anum='+';
			}
			$this->assign('uidnum',$anum);
			//end
			
			//退货退款
			$thtkw['userid']=$uid;
			$thtkw['tuihuotk']=0;
			$thtkw['tuihuo']=2;
			$thtks['tuihuotk']=1;
			$thtkm=M('dingdan')->where($thtkw)->find();
			if($thtkm['id']){
				$thtkw['id']=$thtkm['id'];
				$thtkmt=M('dingdan')->where($thtkw)->save($thtks);
				
				//更新余额
				$thtkhw['id']=$uid;
				$thtkhs['yue']=array('exp','yue+'.$thtkm['zje']);
				//更新积分
				if($thtkm['sjifen']){
					//扣除送的积分
					$thtkhs['jifen']=array('exp','jifen-'.$thtkm['sjifen']);
					$jifenjiluadd['jifen']=$thtkm['sjifen'];
					$tuihuogtype=4;
				}else if($thtkm['jifen']){
					//返回使用积分
					$thtkhs['jifen']=array('exp','jifen+'.$thtkm['jifen']);
					$jifenjiluadd['jifen']=$thtkm['jifen'];
					$tuihuogtype=3;
				}
				$thtkmf=M('huiyuan')->where($thtkhw)->save($thtkhs);
				
					//更新积分记录
					//$jtype=1;//增加 1增加 2使用 3退货
					$jifenjiluadd['uid']=$uid;
					$jifenjiluadd['laiyuan']=$thtkm['id'];
					$jifenjiluadd['ctime']=time();
					$jifenjiluadd['jtype']=$tuihuogtype;
					$jifenjiluaddmm=M('jifenjilu')->add($jifenjiluadd);
					
					//添加会员消费记录
					$hyxfjladd['uid']=$uid;
					$hyxfjladd['je']=$thtkm['zje'];
					$hyxfjladd['fkfs']=$tuihuogtype;
					$hyxfjladd['ctime']=time();
					$hyxfjladd['info']=$thtkm['onum'];
					$hyxfjladd['gtype']=$tuihuogtype;
					$hyxfjladd['ctype']=$tuihuogtype;
					$hyxfjladd['oid']=$thtkm['onum'];
					M('hyxfjl')->add($hyxfjladd);
					
			}
			//end
			
	}

    public function index($catid='',$module='')
    {
                $parentcount = count(explode(',', $this->categorys[$catid]['arrparentid']));
                if($this->categorys[$catid]['child'] != '0'){
                    $jump_url = M('category')->where("parentid = '$catid'")->order('listorder,id')->getfield('url');
                    //redirect("http://".$_SERVER['HTTP_HOST'].$jump_url);
                }
		$this->Urlrule =F('Urlrule');
		if(empty($catid)) $catid =  intval($_REQUEST['id']);
		$p= max(intval($_REQUEST[C('VAR_PAGE')]),1);
		if($catid){
			$cat = $this->categorys[$catid];
			$bcid = explode(",",$cat['arrparentid']); 
			$bcid = $bcid[1]; 
			if($bcid == '') $bcid=intval($catid);
			if(empty($module))$module=$cat['module'];
			$this->assign('module_name',$module);
			unset($cat['id']);
			$this->assign($cat);
			$cat['id']=$catid;
			$this->assign('catid',$catid);
			$this->assign('bcid',$bcid);
		}
		if($cat['readgroup'] && $this->_groupid!=1 && !in_array($this->_groupid,explode(',',$cat['readgroup']))){$this->assign('jumpUrl',URL('User-Login/index'));$this->error (L('NO_READ'));}
		$fields = F($this->mod[$module].'_Field');
		foreach($fields as $key=>$r){
			$fields[$key]['setup'] =string2array($fields[$key]['setup']);
		}
		$this->assign ( 'fields', $fields); 


		$seo_title = $cat['title'] ? $cat['title'] : $cat['catname'];
		$this->assign ('seo_title',$seo_title);
		$this->assign ('seo_keywords',$cat['keywords']);
		$this->assign ('seo_description',$cat['description']);
				

		if($module=='Guestbook'){
			$where['status']=array('eq',1);
			$this->dao= M($module);
			$count = $this->dao->where($where)->count();
			if($count){
				import ( "@.ORG.Page" );
				$listRows =  !empty($cat['pagesize']) ? $cat['pagesize'] : C('PAGE_LISTROWS');		
				$page = new Page ( $count, $listRows );
				$page->urlrule = geturl($cat,'');
				$pages = $page->show();
				$field =  $this->module[$cat['moduleid']]['listfields'];
				$field =  $field ? $field : '*';
				$list = $this->dao->field($field)->where($where)->order('listorder desc,createtime desc,id desc')->limit($page->firstRow . ',' . $page->listRows)->select();
				$this->assign('pages',$pages);
				$this->assign('list',$list);
			}
			$template = $cat['module']=='Guestbook' && $cat['template_list'] ? $cat['template_list'] : 'index';
			$this->display(THEME_PATH.$module.'_'.$template.'.html');            
		}elseif($module=='Feedback'){
			$template = $cat['module']=='Feedback' && $cat['template_list'] ? $cat['template_list'] : 'index' ;

			$this->display(THEME_PATH.$module.'_'.$template.'.html');
		}elseif($module=='Page'){
			$modle=M('Page');
			$data = $modle->find($catid);
			unset($data['id']);

			//分页
			$CONTENT_POS = strpos($data['content'], '[page]');
			if($CONTENT_POS !== false) {			
				$urlrule = geturl($cat,'',$this->Urlrule);
				$urlrule[0] =  urldecode($urlrule[0]);
				$urlrule[1] =  urldecode($urlrule[1]);
				$contents = array_filter(explode('[page]',$data['content']));
				$pagenumber = count($contents);
				for($i=1; $i<=$pagenumber; $i++) {
					$pageurls[$i] = str_replace('{$page}',$i,$urlrule);
				} 
				$pages = content_pages($pagenumber,$p, $pageurls);
				//判断[page]出现的位置
				if($CONTENT_POS<7) {
					$data['content'] = $contents[$p];
				} else {
					$data['content'] = $contents[$p-1];
				}
				$this->assign ('pages',$pages);	
			}

			$template = $cat['template_list'] ? $cat['template_list'] :  'index' ;
			$this->assign ($data);	
			$this->display(THEME_PATH.$module.'_'.$template.'.html');

		}else{
			
			if($catid){
				$seo_title = $cat['title'] ? $cat['title'] : $cat['catname'];
				$this->assign ('seo_title',$seo_title);
				$this->assign ('seo_keywords',$cat['keywords']);
				$this->assign ('seo_description',$cat['description']);
				

				$where = " status=1 ";
				if($cat['child']){							
					$where .= " and catid in(".$cat['arrchildid'].")";			
				}else{
					$where .=  " and catid=".$catid;			
				}
				
				//2016.11.24 原新顺达获取一级总栏目内容
				/*$newcatid=$_GET['newcatid']+0;
				if(!$newcatid){
					//dump($cat['arrchildid']);
					$nnneecattid=explode(',',$cat['arrchildid']);
					$nnneecattid=$nnneecattid['1'];
					if($catid==148 || $catid==156 || $catid==167) $newcatid=$nnneecattid;
				}
				
				if($newcatid){
					$where .=  " and catid=".$newcatid;
				}
				$this->assign('newcatidnew',$newcatid);*/
				
				if($module=='Product'){
					//商品筛选 2016.11.29
					//热销品类
					$pinleiidwh=$_COOKIE['pinleiid']+0;
					if($pinleiidwh) $where.=' and Pinlei='.$pinleiidwh;
					//性别
					$sexidwh=$_COOKIE['sexid']+0;
					if($sexidwh) $where.=' and sex='.$sexidwh;
					//尺码
					$chimaidwh=$_COOKIE['chimaid'];
					//if($chimaidwh=='XXXL') $chimaidwh=='3XL';
					if($chimaidwh){
						$where.=' and kc LIKE \'%lizhenqiu'.$chimaidwh.'lizhenqiulizhenqiu%\' ';
					}
					//价格区间
					$Priceqqidwh=$_COOKIE['Priceqqid'];
					if($Priceqqidwh){
						$Priceqqidwh_arr=explode('#',$Priceqqidwh);
						if($Priceqqidwh_arr['0']!=1000){
							$where.=' and (price >'.$Priceqqidwh_arr['0'].' or price ='.$Priceqqidwh_arr['0'].') and (price <'.$Priceqqidwh_arr['1'].' or price = '.$Priceqqidwh_arr['0'].') ';
						}else{
							$where.=' and (price >'.$Priceqqidwh_arr['0'].' or price='.$Priceqqidwh_arr['0'].') ';
						}
						
					}
					//款式
					$Kuansidwh=$_COOKIE['Kuansid']+0;
					if($Kuansidwh) $where.=' and Kuans='.$Kuansidwh;
					//分类
					$Sfenleiidwh=$_COOKIE['Sfenleiid']+0;
					if($Sfenleiidwh) $where.=' and Sfenlei='.$Sfenleiidwh;
					//风格
					$Sfenggeidwh=$_COOKIE['Sfenggeid']+0;
					if($Sfenggeidwh) $where.=' and Sfengge='.$Sfenggeidwh;
					//领型
					$Slingxingidwh=$_COOKIE['Slingxingid']+0;
					if($Slingxingidwh) $where.=' and Slingxing='.$Slingxingidwh;
					//袖型
					$Sxiuxidwh=$_COOKIE['Sxiuxid']+0;
					if($Sxiuxidwh) $where.=' and Sxiux='.$Sxiuxidwh;
					//面料
					$Smiaoliaoidwh=$_COOKIE['Smiaoliaoid']+0;
					if($Smiaoliaoidwh) $where.=' and Smiaoliao='.$Smiaoliaoidwh;
					//搜索
					$secahstrwh=$_COOKIE['secahstr'];
					if($secahstrwh) $where.=' and title LIKE \'%'.$secahstrwh.'%\' ';
					//新品
					if($_COOKIE['ddqqppxx']=='xinpin') $where.=' and xinpin=1';
				}
				
				
				
				if(empty($cat['listtype'])){
					$this->dao= M($module);
					$count = $this->dao->where($where)->count();
					if($count){
						import ( "@.ORG.Page" );
						$listRows =  !empty($cat['pagesize']) ? $cat['pagesize'] : C('PAGE_LISTROWS');
						$page = new Page ( $count, $listRows );
						$page->urlrule = geturl($cat,'',$this->Urlrule);
						//dump($page);//$page->totalPages
						
						
						//上一页
						$nesssfirstRow=$page->firstRow;
						if(!$nesssfirstRow) $nesssfirstRow=1;
						//下一页
						$nexxxff=$page->nowPage;
						$nexxxff=$nexxxff+1;
						if($nexxxff>$page->totalPages) $nexxxff=$page->totalPages;
						$this->assign('nesssfirstRow',$nesssfirstRow);
						$this->assign('nexxxff',$nexxxff);
						
						$this->assign('page',$page);
						$pages = $page->show(1);
						$field =  $this->module[$this->mod[$module]]['listfields'];
						$field =  $field ? $field : 'id,catid,userid,url,username,title,title_style,keywords,description,thumb,createtime,hits';
						
						
						if($module=='Product'){
							$ddqqppxx=$_COOKIE['ddqqppxx'];
							//商品排序 2016.11.29
							switch ($ddqqppxx){
								case 'xiliang':
								  if($_COOKIE['xiliang']=='desc') $ddqorder='xiliang desc,price desc,hits desc,id desc'; //销量排序
								  else $ddqorder='xiliang asc,price desc,hits desc,id desc';
								  break;
								case 'price':
								  if($_COOKIE['price']=='desc') $ddqorder='price desc,xiliang desc,hits desc,id desc'; //价格排序
								  else $ddqorder='price asc,xiliang desc,hits desc,id desc';
								  break;
								case 'xinpin':
								  if($_COOKIE['xinpin']=='desc') $ddqorder='id desc,xiliang desc,price desc,hits desc'; //新品排序
								  else $ddqorder='xinpin asc,xiliang desc,hits desc,price desc';
								  break;
								default:
								  $ddqorder='hits desc,xiliang desc,price asc,id desc'; //综合排序
							}
							$ddqorder=' '.$ddqorder.',';
						}
						
						
						$list = $this->dao->field($field)->where($where)->order($ddqorder.' listorder desc,createtime desc,id desc')->limit($page->firstRow . ',' . $page->listRows)->select();
						
						
						
						$this->assign('pages',$pages);
						$this->assign('list',$list);
					}
					$template_r = 'list';
				}else{
					$template_r = 'index';
				}
			}else{
				$template_r = 'list';
			}
			$template = $cat['template_list'] ? $cat['template_list'] : $template_r;
			$this->display($module.':'.$template);
		}
    }

 

	public function show($id='',$module='')
    {
		//echo $module;exit;
		//看了又看
		if($module=='Product'){
			
			//登录
			$uidinfo=$this->checklogincs();
			//dump($uid);exit;
			$uid=$uidinfo['id'];
			//echo $uid;exit;
			$seeingsidc=$_COOKIE['seeingsid']+0;
			if($seeingsidc!=$id && $id && $uid && $seeingsidc){
				$seeing_arr['sid']=$seeingsidc;
				$seeing_arr['uid']=$uid;
				$seeing_arr['aid']=$id;
				$seeing_arr['stime']=time();
				M('seeing')->add($seeing_arr);
			}
			if(!$seeingsidc || $seeingsidc!=$id) setcookie('seeingsid', $id);
			
			//获取看了又看商品id
			if($uid){
				$swhere['uid']=$uid;
				$swhere['sid']=array('neq',$id);
				$sseeingspid_arr=M('seeing')->where($swhere)->order('id desc')->limit(19)->select();
				//dump($sseeingspid_arr);exit;
				foreach($sseeingspid_arr as $v){
					$seeingsidall.=','.$v['aid'];
				}
				$aid_arr_s_a=array_unique(explode(',',$seeingsidall));
				
				//判断是否收藏
				$cwhere['uid']=$uid;
				$cwhere['sid']=$id;
				$cwhere['type']=1;
				$chave=M('collection')->where($cwhere)->limit(1)->select();
				//dump($chave);exit;
				$this->assign('chave',$chave['0']['id']);
				//end
			}else{
				//$seeingsidall_arr=explode(',',$_COOKIE['seeingsidall']);
				//if(!in_array($id,$seeingsidall_arr)) 
				$seeingsidall=$_COOKIE['seeingsidallss'].','.$id;
				//替换去掉重复
				/*$seeingsidall_arr_c=explode('gyr',$seeingsidall);
				$seeingsidall_arr_c=array_unique($seeingsidall_arr_c);
				dump($seeingsidall_arr_c);exit;
				foreach($seeingsidall_arr_c as $ksc=>$vsc){
					if($seeingsidallc) $seeingsidallc.=','.$vsc;
					else $seeingsidallc=$vsc;
				}*/
				//dump(array_unique(explode(',',$seeingsidall)));exit;
				//str_replace($id.','.$id,$id,$seeingsidall);
				//echo $seeingsidall;exit;
				setcookie('seeingsidallss', $seeingsidall);
				$aid_arr_s_a=array_unique(explode(',',$seeingsidall));
				//arsort($aid_arr_s_a);
			}
			//获取看了又看商品id
			foreach($aid_arr_s_a as $v){
				if($v){
					if($v!=$id){
						if($aid_arr_s) $aid_arr_s.=','.$v;
						else $aid_arr_s=$v;
					}
				}
			}
			$aid_arr['id']=array('in',$aid_arr_s);
			$aid_arr_list=M('Product')->field('id,thumb,url,price')->where($aid_arr)->order('id desc')->limit(3)->select();
			//dump($aid_arr_list);exit;
			$this->assign('aid_arr_list',$aid_arr_list);
			
			//热销单品
				$hot_list=M('Product')->field('id,thumb,url,price,cprice,title')->order('xiliang desc')->limit(6)->select();
				$this->assign('hot_list',$hot_list);
			//end
			
		}
		//end
		
		
		
		$this->Urlrule =F('Urlrule');
		$p= max(intval($_REQUEST[C('VAR_PAGE')]),1);		
		$id = $id ? $id : intval($_REQUEST['id']);
		$module = $module ? $module : MODULE_NAME;
		$this->assign('module_name',$module);
		$this->dao= M($module);//;
		$data = $this->dao->find($id);
		
		$Kuans_new=M("Kuans")->where('id='.$data['Kuans'])->select();
		$Slingxing_new=M('Slingxing')->where('id='.$data['Slingxing'])->select();
		$Sfengge_new=M('Sfengge')->where('id='.$data['Sfengge'])->select();
		$Sxiux_new=M('Sxiux')->where('id='.$data['Sxiux'])->select();
		$Pinlei_new=M('Pinlei')->where('id='.$data['Pinlei'])->select();
		//dump($Slingxing_new);exit;
		//echo $Kuans[$data['Kuans']];
		//echo $Kuans[$data['Kuans']]['kuanshi'];exit;
		$data['Kuans']=$Kuans_new['0']['kuanshi'];
		$data['Slingxing']=$Slingxing_new['0']['lingxing'];
		$data['Sfengge']=$Sfengge_new['0']['fengge'];
		$data['Sxiux']=$Sxiux_new['0']['xiux'];
		$data['Pinlei']=$Pinlei_new['0']['pinleis'];
		$this->assign('newdata',$data);
		
		$catid = $data['catid'];
		$cat = $this->categorys[$data['catid']];
		if(empty($cat['ishtml']))$this->dao->where("id=".$id)->setInc('hits'); //添加点击次数
		$bcid = explode(",",$cat['arrparentid']); 
		$bcid = $bcid[1]; 
		if($bcid == '') $bcid=intval($catid);

		if($data['readgroup']){
			if($this->_groupid!=1 && !in_array($this->_groupid,explode(',',$data['readgroup'])) )$noread=1;
		}elseif($cat['readgroup']){
			if($this->_groupid!=1 && !in_array($this->_groupid,explode(',',$cat['readgroup'])) )$noread=1;
		}
		if($noread==1){$this->assign('jumpUrl',URL('User-Login/index'));$this->error (L('NO_READ'));}

		$chargepoint = $data['readpoint'] ? $data['readpoint'] : $cat['chargepoint']; 
		if($chargepoint && $data['userid'] !=$this->_userid){
			$user = M('User');
			$userdata =$user->find($this->_userid);
			if($cat['paytype']==1 && $userdata['point']>=$chargepoint){
				$chargepointok = $user->where("id=".$this->_userid)->setDec('point',$chargepoint);
			}elseif($cat['paytype']==2 && $userdata['amount']>=$chargepoint){
				$chargepointok = $user->where("id=".$this->_userid)->setDec('amount',$chargepoint);
			}else{
				$this->error (L('NO_READ'));
			}
		}
	/*上一篇下一篇 add by wei 2012-11-09*/
	$pre = M($module)->where("id<$id and catid=$catid and lang=".LANG_ID)->order("id DESC")->find();
    $next = M($module)->where("id>$id and catid=$catid and lang=".LANG_ID)->order("id ASC")->find();
    $this->assign('pre',$pre);
    $this->assign('next',$next);
	/*end*/

	
		$seo_title = $data['title'].'-'.$cat['catname'];
		$this->assign ('seo_title',$seo_title);
		$this->assign ('seo_keywords',$data['keywords']);
		$this->assign ('seo_description',$data['description']);
		$this->assign ( 'fields', F($cat['moduleid'].'_Field') ); 
		

		$fields = F($this->mod[$module].'_Field');
		foreach($data as $key=>$c_d){
			$setup='';
			$fields[$key]['setup'] =$setup=string2array($fields[$key]['setup']);
			if($setup['fieldtype']=='varchar' && $fields[$key]['type']!='text'){
				$data[$key.'_old_val'] =$data[$key];
				$data[$key]=fieldoption($fields[$key],$data[$key]);
			}elseif($fields[$key]['type']=='images' || $fields[$key]['type']=='files'){ 
				if(!empty($data[$key])){
					$p_data=explode(':::',$data[$key]);
					$data[$key]=array();
					foreach($p_data as $k=>$res){
						$p_data_arr=explode('|',$res);					
						$data[$key][$k]['filepath'] = $p_data_arr[0];
						$data[$key][$k]['filename'] = $p_data_arr[1];
					}
					unset($p_data);
					unset($p_data_arr);
				}
			}
			unset($setup);
		}
		$this->assign('fields',$fields); 


		//手动分页
		$CONTENT_POS = strpos($data['content'], '[page]');
		if($CONTENT_POS !== false) {
			
			$urlrule = geturl($cat,$data,$this->Urlrule);
			$urlrule =  str_replace('%7B%24page%7D','{$page}',$urlrule); 
			$contents = array_filter(explode('[page]',$data['content']));
			$pagenumber = count($contents);
			for($i=1; $i<=$pagenumber; $i++) {
				$pageurls[$i] = str_replace('{$page}',$i,$urlrule);
			} 
			$pages = content_pages($pagenumber,$p, $pageurls);
			//判断[page]出现的位置是否在文章开始
			if($CONTENT_POS<7) {
				$data['content'] = $contents[$p];
			} else {
				$data['content'] = $contents[$p-1];
			}
			$this->assign ('pages',$pages);	
		}

		if(!empty($data['template'])){
			$template = $data['template'];
		}elseif(!empty($cat['template_show'])){
			$template = $cat['template_show'];
		}else{
			$template =  'show';
		}

		$this->assign('catid',$catid);
		$this->assign ($cat);
		$this->assign('bcid',$bcid);
		//dump($data);exit;
		$this->assign ($data);

		$this->display($module.':'.$template); 
    }

	public function down()
	{

		$module = $module ? $module : MODULE_NAME;
		$id = $id ? $id : intval($_REQUEST['id']);
		$this->dao= M($module);
		$filepath = $this->dao->where("id=".$id)->getField('file');
		$this->dao->where("id=".$id)->setInc('downs');

		if(strpos($filepath, ':/')) { 
			header("Location: $filepath");
		} else {	
			$filepath = '.'.$filepath;
			if(!$filename) $filename = basename($filepath);
			$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
			if(strpos($useragent, 'msie ') !== false) $filename = rawurlencode($filename);
			$filetype = strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
			$filesize = sprintf("%u", filesize($filepath));
			if(ob_get_length() !== false) @ob_end_clean();
			header('Pragma: public');
			header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
			header('Cache-Control: no-store, no-cache, must-revalidate');
			header('Cache-Control: pre-check=0, post-check=0, max-age=0');
			header('Content-Transfer-Encoding: binary');
			header('Content-Encoding: none');
			header('Content-type: '.$filetype);
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header('Content-length: '.$filesize);
			readfile($filepath);
		}
		exit;
	}

    /**
     * 获取对应商品的库存  库存格式->白色lizhenqiuMlizhenqiulizhenqiu998
     *$get_ys       颜色
     *$get_cm       尺码
     *$get_id       对应商品id
    */
    public function thisku($get_id,$get_cm,$get_ys){
        $get_kc=M('product')->where(array('id'=>$get_id))->getField('kc');
        $yanseee=explode('moweilin',$get_kc);
         foreach($yanseee as $ks=>$vs){
            $ysssaaa=explode('lizhenqiu',$vs);
            $yansearr[]=$ysssaaa['0'];
            $yuccmmck=$yuccmmck?$yuccmmck.'-'.$ysssaaa['0'].$ysssaaa['1'].'-'.$ysssaaa['3']:$ysssaaa['0'].$ysssaaa['1'].'-'.$ysssaaa['3'];
          }
          $yuccmmck=explode('-',$yuccmmck);
            foreach($yuccmmck as $kk=>$vv){
                $s[]=$vv;
            }
          $yansearr=(array_unique($yansearr));
          $str=$get_ys.$get_cm;

          $tqjz=array_search($str,$s);
          $k=$tqjz+1;
          $ku=$s[$k];
          return $ku;
    }
	
     /**
     * 库存变动
     *$get_ys       颜色
     *$get_cm       尺码
     *$get_gkc      库存
     *$gkc_number   库存数量
     *$get_number   购买数量
     *$get_id       对应商品id
     *$get_type     类型($get_type=1,加回;$get_type=2,减去)
    */
    function kubd($get_ys,$get_cm,$get_gkc,$gkc_number,$get_number,$get_id,$get_type=2){
        //2016.12.01
        //原来颜色 尺寸 库存
        $old_kc=$get_ys.'lizhenqiu'.$get_cm.'lizhenqiulizhenqiu'.$gkc_number;
        //新库存
        if($get_type==2){
            $new_kc_num=($gkc_number-$get_number)+0;
        }else{
            $new_kc_num=($gkc_number+$get_number)+0;
        }
        if($new_kc_num<0) $new_kc_num=0;
        $new_kc=$get_ys.'lizhenqiu'.$get_cm.'lizhenqiulizhenqiu'.$new_kc_num;
        $kc_data['kc']=str_ireplace($old_kc,$new_kc,$get_gkc);
        //更新库存
        if(M('product')->where(array('id'=>$get_id))->save($kc_data)){
            return 1;
        }
        
        
        
    }
	
	
	
	
	public function hits()
	{
		$module = $module ? $module : MODULE_NAME;
		$id = $id ? $id : intval($_REQUEST['id']);
		$this->dao= M($module);
		$this->dao->where("id=".$id)->setInc('hits');

		if($module=='Download'){
			$r = $this->dao->find($id);
			echo '$("#hits").html('.$r['hits'].');$("#downs").html('.$r['downs'].');';
		}else{
			$hits = $this->dao->where("id=".$id)->getField('hits');
			echo '$("#hits").html('.$hits.');';
		}
		exit;
	}
	public function verify()
    {
		header('Content-type: image/jpeg');
        $type	 =	 isset($_GET['type'])? get_safe_replace($_GET['type']):'jpeg';
        import("@.ORG.Image");
        Image::buildImageVerify(4,1,$type);
    }
}
?>