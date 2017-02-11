<?php

require_once(_PS_SMARTY_DIR_.'Smarty.class.php');
require_once(dirname(__FILE__).'/smarty_internal_template.php');
/**
 * Trevenque smarty
 *  Extends mobile functionality in an easier way
 *
 * @package trevenque_mobile
 * @author 
 **/



class Trevenque_Smarty extends Smarty{

        
    public  $is_mobile=false;
    public  $is_tablet=false;
    public  $is_desktop = true;
    
    public $template_class = "Trevenque_Smarty_Internal_Template";
    public function __construct()
    {
        
        parent::__construct();
        $context= Context::getContext();
        $this->is_mobile = $context->isMobile();
        $this->is_tablet = $context->isTablet();
        
        if (isset($_GET["mobile"]))
            $this->is_mobile = true;
        //$this->is_mobile = true;

        $this->is_desktop =  !$this->is_mobile && !$this->is_tablet;

    }

}

