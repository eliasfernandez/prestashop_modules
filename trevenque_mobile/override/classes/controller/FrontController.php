<?php
class FrontController extends FrontControllerCore
{
    
    /*
    * module: trevenque_mobile
    * date: 2016-05-11 18:15:34
    * version: 0.9
    */
    protected function useMobileTheme()
    {
        if (isset($this->context->smarty->is_desktop) && !$this->context->smarty->is_desktop)
            return true;
        return parent::useMobileTheme();
    }
   /*
    * module: trevenque_mobile
    * date: 2016-05-11 18:15:34
    * version: 0.9
    */
    public function setMobileMedia()
    {
   
        parent::setMobileMedia();
	if (@filemtime($this->getThemeDir().'js/autoload/')) {
            foreach (scandir($this->getThemeDir().'js/autoload/', 0) as $file) {
                if (preg_match('/^[^.].*\.js$/', $file)) {
                    $this->addJS($this->getThemeDir().'js/autoload/'.$file);
                }
            }
        }
        // Automatically add css files from css/autoload directory in the template
        if (@filemtime($this->getThemeDir().'css/autoload/')) {
            foreach (scandir($this->getThemeDir().'css/autoload', 0) as $file) {

                if (preg_match('/^[^.].*\.css$/', $file)) {
                    $this->addCSS($this->getThemeDir().'css/autoload/'.$file);
                }
            }
        }
     
        $this->addCustomCSS('global.css', 'all');
        if ($this->php_self=="authentication"){
            $this->addCustomCSS('authentication.css');
            $this->addJqueryPlugin('typewatch');
            $this->addCustomJS(array(
                'tools/vatManagement.js',
                'tools/statesManagement.js',
                'authentication.js',
                
            ));
            $this->addJS(_PS_JS_DIR_.'validate.js');
        }elseif($this->php_self=="product"){
            $this->addCustomCSS('product.css');
            $this->addCustomCSS('print.css', 'print');
            $this->addJqueryPlugin(array('fancybox', 'idTabs', 'scrollTo', 'serialScroll', 'bxslider'));
            $this->addCustomJS(array(
                'tools.js',                  'product.js'
            ));
        }elseif($this->php_self=="category"){
            $this->addCustomCSS(array(
                'scenes.css' => 'all',
                'category.css' => 'all',
                'product_list.css' => 'all',
            ));
        }elseif($this->php_self == "order-opc"){
           $this->addCustomCSS('order-opc.css');
           $this->addCustomJS('order-opc.js');
        
        }elseif($this->php_self == "order"){
            $this->addCustomCSS('addresses.css');
            
        }
    }
   /*
    * module: trevenque_mobile
    * date: 2016-05-11 18:15:34
    * version: 0.9
    */
    protected function addCustomCSS($file, $type="all"){
        if(is_array($file)){
            foreach ($file as $k=>$v)
                $this->addCustomCSS($k, $v);
            return;    
        }
        if(is_file(_PS_ROOT_DIR_._THEME_MOBILE_CSS_DIR_.$file))
            $this->addCSS(_THEME_MOBILE_CSS_DIR_.$file, $type);
        else
            $this->addCSS(_THEME_CSS_DIR_.$file, $type);
    }
   /*
    * module: trevenque_mobile
    * date: 2016-05-11 18:15:34
    * version: 0.9
    */
    protected function addCustomJS($file){
        if(is_array($file)){
            foreach ($file as $f)
                $this->addCustomJS($f, $type);
            return;   
            
        }
        if(is_file(_PS_ROOT_DIR_._THEME_MOBILE_JS_DIR_.$file))
            $this->addJS(_THEME_MOBILE_JS_DIR_.$file);
        else
            $this->addJS(_THEME_JS_DIR_.$file);
    }
   /*
    * module: trevenque_mobile
    * date: 2016-05-11 18:15:34
    * version: 0.9
    */
    public function initContent()
    {
        parent::initContent();
        $this->context->smarty->assign(array(
                'HOOK_HEADER' => Hook::exec('displayHeader'),
                'HOOK_TOP' => Hook::exec('displayTop'),
                'HOOK_LEFT_COLUMN' => ($this->display_column_left ? Hook::exec('displayLeftColumn') : ''),
                'HOOK_RIGHT_COLUMN' => ($this->display_column_right ? Hook::exec('displayRightColumn', array('cart' => $this->context->cart)) : ''),
            ));
    }
}
