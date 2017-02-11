<?php
class FrontController extends FrontControllerCore
{
    
   
    protected function useMobileTheme()
    {
        if (isset($this->context->smarty->is_desktop) && !$this->context->smarty->is_desktop) {
            return true;
        }
        return parent::useMobileTheme();
    }
    public function setMedia()
    {
        
        $ret = parent::setMedia();
        $this->addCSS(_THEME_CSS_DIR_.'project.css', 'all', 100);
        return $ret;
    }
    public function setMobileMedia()
    {
        $this->addJquery();
        $this->addJS(_PS_JS_DIR_.'tools.js');
        $this->addCustomJS(array( 'global.js' ));
        if (@filemtime($this->getThemeDir().'js/autoload/')) {
            foreach (scandir($this->getThemeDir().'js/autoload/', 0) as $file) {
                if (preg_match('/^[^.].*\.js$/', $file)) {
                    $this->addJS($this->getThemeDir().'js/autoload/'.$file);
                }
            }
        }
        if (@filemtime($this->getThemeDir().'css/autoload/')) {
            foreach (scandir($this->getThemeDir().'css/autoload', 0) as $file) {
                if (preg_match('/^[^.].*\.css$/', $file)) {
                    $this->addCSS($this->getThemeDir().'css/autoload/'.$file);
                }
            }
        }
        
        $this->addJqueryPlugin('jquery.easing');
        $this->addJqueryPlugin('fancybox');
        
        $this->addCustomCSS('global.css', 'all');
        
        if ($this->php_self=="authentication") {
            $this->addJqueryPlugin('typewatch');
            $this->addCustomJS(array(
                'tools/vatManagement.js',
                'tools/statesManagement.js',
                'authentication.js',
                
            ));
            $this->addJS(_PS_JS_DIR_.'validate.js');
        } elseif ($this->php_self=="product") {
            $this->addCustomCSS('product.css');
            $this->addCustomJS(array(
                'product.js'
            ));
        } elseif ($this->php_self=="category") {
            $this->addCustomCSS(array(
            ));
        } elseif ($this->php_self == "order-opc") {
            $this->addCustomCSS('order-opc.css');
            $this->addCustomJS('order-opc.js');
        } elseif ($this->php_self == "order") {
            $this->addCustomCSS('addresses.css');
        }
    }
    public function setTemplate($default_template)
    {
        if ($this->useMobileTheme()) {
        $custom_mobile_template = str_replace(_PS_THEME_DIR_, _PS_THEME_CUSTOM_MOBILE_DIR_, $default_template);
        $mobile_template = str_replace(_PS_THEME_DIR_, _PS_THEME_MOBILE_DIR_, $default_template);
        $custom_template = str_replace(_PS_THEME_DIR_, _PS_THEME_CUSTOM_DIR_, $default_template);
        if (is_file($custom_mobile_template)) {
                $default_template = $custom_mobile_template;
            } elseif (is_file($mobile_template)) {
        $default_template = $mobile_template;
        } elseif (is_file($custom_template)) {
        $default_template = $custom_template;
        }

            $this->setMobileTemplate($default_template);
        } else {
            $template = $this->getOverrideTemplate();
            if ($template) {
                $custom_template = str_replace(_PS_THEME_DIR_, _PS_THEME_CUSTOM_DIR_, $template);
            } else {
                $custom_template = str_replace(_PS_THEME_DIR_, _PS_THEME_CUSTOM_DIR_, $default_template);
            }
            
            if (!strpos($template, "custom") && is_file($custom_template)) {
                $template = $custom_template;
            }
            
            if ($template) {
                parent::setTemplate($template);
            } else {
                parent::setTemplate($default_template);
            }
        }
    }
    protected function addCustomCSS($file, $type = "all")
    {
        if (is_array($file)) {
            foreach ($file as $k => $v) {
                $this->addCustomCSS($k, $v);
            }
            return;
        }
        if (is_file(_PS_ROOT_DIR_._THEME_MOBILE_CSS_DIR_.$file)) {
            $this->addCSS(_THEME_MOBILE_CSS_DIR_.$file, $type);
        } else {
            $this->addCSS(_THEME_CSS_DIR_.$file, $type);
        }
    }
    protected function addCustomJS($file, $type = null)
    {
        if (is_array($file)) {
            foreach ($file as $f) {
                $this->addCustomJS($f, $type);
            }
            return;
        }
        if (is_file(_PS_ROOT_DIR_._THEME_MOBILE_JS_DIR_.$file)) {
            $this->addJS(_THEME_MOBILE_JS_DIR_.$file, $type);
        } else {
            $this->addJS(_THEME_JS_DIR_.$file, $type);
        }
    }
    
    public function addCSS($css_uri, $css_media_type = 'all', $offset = null, $check_path = true)
    {
        if (strpos($css_uri, _THEME_CSS_DIR_."custom/")=== false && strpos($css_uri, _THEME_CSS_DIR_) !== false) {
            $path_pieces = explode(_THEME_CSS_DIR_, $css_uri);
            $path_pieces = $path_pieces[1];
            $path_pieces = explode("/", $path_pieces);
            $file = array_pop($path_pieces);
            if (count($path_pieces))
                $path_pieces[0]="custom/css".$path_pieces[0];
            else
                $path_pieces[0]="custom/css";
            
            $path_pieces[]="/".$file;
            $css_uri_custom = _THEME_DIR_.implode("/", $path_pieces);
            if (is_file(_PS_ROOT_DIR_.$css_uri_custom)) {
                return parent::addCSS($css_uri_custom, $css_media_type, $offset, $check_path);
            }
        }
        return  parent::addCSS($css_uri, $css_media_type, $offset, $check_path);
    }
    
    public function addJS($js_uri, $check_path = true)
    {
        if (strpos($js_uri, _THEME_JS_DIR_."custom/")=== false && strpos($js_uri, _THEME_JS_DIR_) !== false) {
            $path_pieces = explode(_THEME_JS_DIR_, $js_uri);
            $path_pieces = $path_pieces[1];
            $path_pieces = explode("/", $path_pieces);
            $file = array_pop($path_pieces);
            if (count($path_pieces))
                $path_pieces[0]="custom/js".$path_pieces[0];
            else
                $path_pieces[0]="custom/js";

            $path_pieces[]="/".$file;
            $js_uri_custom = _THEME_DIR_.implode("/", $path_pieces);

            if (is_file(_PS_ROOT_DIR_.$js_uri_custom)) {
                return parent::addJS($js_uri_custom, $check_path);
            }
        }
        return parent::addJS($js_uri, $check_path);
    }
    public function initContent()
    {
        parent::initContent();
        $this->context->smarty->assign(array(
                'HOOK_HEADER' => Hook::exec('displayHeader'),
                'HOOK_TOP' => Hook::exec('displayTop'),
                'HOOK_LEFT_COLUMN' => ($this->display_column_left ? Hook::exec('displayLeftColumn') : ''),
                'HOOK_RIGHT_COLUMN' => ($this->display_column_right ? Hook::exec('displayRightColumn', array('cart' => $this->context->cart)) : '')
            ));
    }
}
