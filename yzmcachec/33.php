<?php
if(!defined('ZH')){
    exit('');
}
		for($i=0;$i<2;$i++){
		 $rand.=dechex(rand(1,15));
		}
		$im=imagecreatetruecolor(66,33);
	$te = imagecolorallocate($im,255,255,255);
	for($i=0;$i<9;$i++){
		$te2 = imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255));
		imageline($im,rand(0,66),rand(0,33),rand(0,66),rand(0,33),$te2);
	}
 	for($i=0;$i<9;$i++){
  		imagesetpixel($im,rand()%66,rand()%33,$te2);
  	}
  	imagettftext($im,14,9,rand(5,3),20,$te,__DIR__.'/minion_pro.ttf',$str);
		header("Content-type: image/jpeg");
		imagejpeg($im);