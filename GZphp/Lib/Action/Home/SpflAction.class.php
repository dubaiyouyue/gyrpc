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

class SpflAction extends BaseAction {

    public function index() {
        
		$mm= get_safe_replace($_GET['mm']);
		
		if($mm=='Price'){
			echo '
			<span class="xonewktanchuccc" onclick="Priceff(\'Priceqq\',\'0-99元\',\'0#99\');">0-99元</span>
			<span class="xonewktanchuccc" onclick="Priceff(\'Priceqq\',\'99-199元\',\'99#199\');">99-199元</span>
			<span class="xonewktanchuccc" onclick="Priceff(\'Priceqq\',\'199-299元\',\'199#299\');">199-299元</span>
			<span class="xonewktanchuccc" onclick="Priceff(\'Priceqq\',\'299-599元\',\'299#599\');">299-599元</span>
			<span class="xonewktanchuccc" onclick="Priceff(\'Priceqq\',\'599-1000元\',\'599#1000\');">599-1000元</span>
			<span class="xonewktanchuccc" onclick="Priceff(\'Priceqq\',\'1000元以上\',\'1000#\');">1000元以上</span>
			<script>
			//款式分类等
			function Priceff(pinlei,pinleiname,pinleiid){
				$.cookie(pinlei+\'name\', pinleiname);
				$.cookie(pinlei+\'id\', pinleiid);
				tccc();
			}
			</script>
			';
			exit;
		}
		
		$User = M($mm);
		$r=$User->select();
		$this->assign('list',$r);
		if($r) $this->display();
    }
    
}

?>