;(function ($) {
	var pcid;
    $.fn.tishicengjsqiu = function(options) {
		//定义插件的名称，这里为tishicengjsqiu
		// lizhenqiu.com
		
		var timestamp = Date.parse(new Date());
        var dft = {
            //以下为该插件的属性及其默认值
            textwz: '提示层插件文字', 
            tishicbj: '#ddd', //提示层背景
            colors: 'yellow', //文字颜色
			idd:'tishicengxiaolizhenqiujqcj'+timestamp,//提示层id
			iddtt:3,
			admmint:'rollIn', //显示动画
			admmout:'hinge' //隐藏动画
        };
		
        var ops = $.extend(dft,options);
		
		if($('#'+pcid)) $('#'+pcid).remove();//清除前一层提示
		var htmlss='<div class="animated ' + ops.admmint + '" id="' + ops.idd + '" style="background: ' + ops.tishicbj + ';border-radius: 3px;color: ' + ops.colors + ';display: inline-block;padding: 6px 12px;position: fixed;top: 50%;left: 50%;visibility: hidden;">' + ops.textwz + '</div>';
        $(this).append(htmlss);
		var twidth=($('#'+ops.idd).width()+24)/2;
		var theight=($('#'+ops.idd).height()+12)/2;
		$('#'+ops.idd).css({'visibility':'visible','margin-top':+(0-theight),'margin-left':+(0-twidth)});
		
		//清除定时器
		if(pcid) clearTimeout( tt );//终止触发的setTimeout防止重复执行
		
		var tt=setTimeout(function(){
			$('#'+ops.idd).removeClass(ops.admmint);
			$('#'+ops.idd).addClass(ops.admmout);
			//$('#'+ops.idd).remove();
		},ops.iddtt*1000); //延时3秒 
		
		//保存上一个提示层id
		pcid=ops.idd;
		
    }
})(jQuery);