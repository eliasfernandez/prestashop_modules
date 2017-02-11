<?php 
class Dispatcher extends DispatcherCore{
    
    /*
    * module: trevenque_cache
    * date: 2016-09-20 14:20:40
    * version: 0.9
    */
    public function dispatch()
    {
        $php_self = Tools::getValue('controller');
        if ($php_self == "" && __PS_BASE_URI__."index.php" ==  $_SERVER["REQUEST_URI"]) {
            $php_self="index";
        }

        $context =  Context::getContext();
	
        $is_cacheable = (!isset($context->cookie->id_cart) || !$context->cookie->id_cart) && 
			(!isset($context->customer->id) || !$context->customer->id) ;
	$controllers = array("product", "category", "index");
	if (Module::isInstalled('trevenque_menu')){
		$controllers[]="dropdown";
	}
 	$is_cacheable = $is_cacheable && in_array($php_self, $controllers);

	// construimos el nombre del archivo {controller}-{id}-{lang}-{mobile}
        $hash_file = $php_self;
        if ($php_self == "product") {
            $hash_file .= "-".(int)Tools::getValue('id_product');
        } elseif ($php_self == "category") {
            $hash_file .= "-".(int)Tools::getValue('id_category');
        } elseif (Module::isInstalled('trevenque_menu') && $php_self=="dropdown"){
	    $hash_file .= "-".(int)Tools::getValue('id');
	}
	$hash_file .= "-".$context->language->id;
        if ($context->smarty->is_mobile) {
            $hash_file .= "-"."mobile";
        }
	
	if ($is_cacheable && file_exists(_PS_ROOT_DIR_."/cache/sardine/".$hash_file.".html")) {
            $content = file_get_contents(_PS_ROOT_DIR_."/cache/sardine/".$hash_file.".html");
        
            echo str_replace("</html>","<!--sardine $hash_file file--></html>", $content);
            return;
        } else {
            ob_start();
            parent::dispatch();
            $content = ob_get_contents();
            ob_clean();
            if ($is_cacheable) {
                file_put_contents(_PS_ROOT_DIR_."/cache/sardine/".$hash_file.".html", $content);
                echo $content;
            } else {
                echo $content;
            }
        }
        
            
            
    }
}
