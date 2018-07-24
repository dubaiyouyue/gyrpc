<?php
	error_reporting(E_ALL^E_NOTICE^E_WARNING);
	session_start();
	if(!$_SESSION['dxyzmtel'] || !$_SESSION['dxyzmssss'] || !$_SESSION['verify'] || ($_SESSION['verify']!=$_SESSION['verifysms'])) exit;
	$mysmscode='SMS_41635116';
	$mycoded='code';
	if($_SESSION['dxyzmtelzhuimim']){
		$mysmscode='SMS_41600119';
		$mycoded='newpass';
	}
	
    include_once 'aliyun-php-sdk-sms/aliyun-php-sdk-core/Config.php';
    use Sms\Request\V20160927 as Sms;            
    $iClientProfile = DefaultProfile::getProfile("cn-hangzhou", "", "");        
    $client = new DefaultAcsClient($iClientProfile);    
    $request = new Sms\SingleSendSmsRequest();
    $request->setSignName("广羽人");/*签名名称*/
    $request->setTemplateCode($mysmscode);/*模板code*/
    $request->setRecNum($_SESSION['dxyzmtel']);/*目标手机号*/
    $request->setParamString("{\"".$mycoded."\":\"".$_SESSION['dxyzmssss']."\"}");/*模板变量，数字一定要转换为字符串*/
    try {
        $response = $client->getAcsResponse($request);
        //print_r($response);
    }
    catch (ClientException  $e) {
        //print_r($e->getErrorCode());   
        //print_r($e->getErrorMessage());   
    }
    catch (ServerException  $e) {        
        //print_r($e->getErrorCode());   
        //print_r($e->getErrorMessage());
    }
?>