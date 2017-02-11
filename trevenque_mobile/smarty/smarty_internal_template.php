<?php
/**
 * Trevenque_Smarty_Internal_Template
 *  Extends mobile functionality in an easier wayu 
 *
 * @package trevenque_mobile
 * @author 
 **/


    
class Trevenque_Smarty_Internal_Template extends Smarty_Internal_Template
{


    public function getSubTemplate($template, $cache_id, $compile_id, $caching, $cache_lifetime, $data, $parent_scope)
    {

        if($this->smarty->is_desktop) /*si desktop comportamiento normal*/
            return parent::getSubTemplate($template, $cache_id, $compile_id, $caching, $cache_lifetime, $data, $parent_scope);
            
        $mobile_template= str_replace(_PS_THEME_DIR_, _PS_THEME_MOBILE_DIR_,$template);
        if (!strpos($template, "mobile") && is_file( $mobile_template) )
            return parent::getSubTemplate($mobile_template, $cache_id, $compile_id, $caching, $cache_lifetime, $data, $parent_scope);

        return parent::getSubTemplate($template, $cache_id, $compile_id, $caching, $cache_lifetime, $data, $parent_scope);
    }

  
}
