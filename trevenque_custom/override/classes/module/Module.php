<?php

class Module extends ModuleCore
{
    

    protected static $is_desktop;
    /*
    ** Template management (display, overload, cache)
    */
    protected static function _isTemplateOverloadedStatic($module_name, $template)
    {
        if (!isset(self::$is_desktop)) {
            self::$is_desktop = Context::getContext()->smarty->is_desktop;
        }
    
        if (!self::$is_desktop) { // si es movil entonces:

            /*si encontramos una carpeta custom en movil*/
            if (is_dir(_PS_THEME_MOBILE_DIR_.'custom/')) {
                if (Tools::file_exists_cache(_PS_THEME_MOBILE_DIR_.'custom/modules/'.$module_name.'/'.$template)) {
                    return _PS_THEME_MOBILE_DIR_.'custom/modules/'.$module_name.'/'.$template;
                } elseif (Tools::file_exists_cache(_PS_THEME_MOBILE_DIR_.'custom/modules/'.$module_name.'/views/templates/hook/'.$template)) {
                    return _PS_THEME_MOBILE_DIR_.'custom/modules/'.$module_name.'/views/templates/hook/'.$template;
                } elseif (Tools::file_exists_cache(_PS_THEME_MOBILE_DIR_.'custom/modules/'.$module_name.'/views/templates/front/'.$template)) {
                    return _PS_THEME_MOBILE_DIR_.'custom/modules/'.$module_name.'/views/templates/front/'.$template;
                }
            }
            

            if (Tools::file_exists_cache(_PS_THEME_MOBILE_DIR_.'modules/'.$module_name.'/'.$template)) {
                return _PS_THEME_MOBILE_DIR_.'modules/'.$module_name.'/'.$template;
            } elseif (Tools::file_exists_cache(_PS_THEME_MOBILE_DIR_.'modules/'.$module_name.'/views/templates/hook/'.$template)) {
                return _PS_THEME_MOBILE_DIR_.'modules/'.$module_name.'/views/templates/hook/'.$template;
            } elseif (Tools::file_exists_cache(_PS_THEME_MOBILE_DIR_.'modules/'.$module_name.'/views/templates/front/'.$template)) {
                return _PS_THEME_MOBILE_DIR_.'modules/'.$module_name.'/views/templates/front/'.$template;
            }
        }
        /*si encontramos una carpeta custom en escritorio*/
        if (is_dir(_PS_THEME_DIR_.'custom/')) {
            if (Tools::file_exists_cache(_PS_THEME_DIR_.'custom/modules/'.$module_name.'/'.$template)) {
                return _PS_THEME_DIR_.'custom/modules/'.$module_name.'/'.$template;
            } elseif (Tools::file_exists_cache(_PS_THEME_DIR_.'custom/modules/'.$module_name.'/views/templates/hook/'.$template)) {
                return _PS_THEME_DIR_.'custom/modules/'.$module_name.'/views/templates/hook/'.$template;
            } elseif (Tools::file_exists_cache(_PS_THEME_DIR_.'custom/modules/'.$module_name.'/views/templates/front/'.$template)) {
                return _PS_THEME_DIR_.'custom/modules/'.$module_name.'/views/templates/front/'.$template;
            }
        }
        
        if (Tools::file_exists_cache(_PS_THEME_DIR_.'modules/'.$module_name.'/'.$template)) {
            return _PS_THEME_DIR_.'modules/'.$module_name.'/'.$template;
        } elseif (Tools::file_exists_cache(_PS_THEME_DIR_.'modules/'.$module_name.'/views/templates/hook/'.$template)) {
            return _PS_THEME_DIR_.'modules/'.$module_name.'/views/templates/hook/'.$template;
        } elseif (Tools::file_exists_cache(_PS_THEME_DIR_.'modules/'.$module_name.'/views/templates/front/'.$template)) {
            return _PS_THEME_DIR_.'modules/'.$module_name.'/views/templates/front/'.$template;
        } elseif (Tools::file_exists_cache(_PS_MODULE_DIR_.$module_name.'/views/templates/hook/'.$template)) {
            return false;
        } elseif (Tools::file_exists_cache(_PS_MODULE_DIR_.$module_name.'/views/templates/front/'.$template)) {
            return false;
        } elseif (Tools::file_exists_cache(_PS_MODULE_DIR_.$module_name.'/'.$template)) {
            return false;
        }
        return null;
    }
}
