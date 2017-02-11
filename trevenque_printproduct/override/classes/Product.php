<?php 
/**
 * Trevenque parent canonical
 * 
 *
 * @version   : 1.0.1
 * @date      : 2016 03 16
 * @author    : Elías <elias@trevenque.es>
 * @license   : http://opensource.org/licenses/osl-3.0.php
 * @compatibility : PS == 1.6.1.0
 */

class Product extends ProductCore{


    public $id_printproduct;

    public function __construct($id_product = null, $full = false, $id_lang = null, $id_shop = null, Context $context = null){
        self::$definition["fields"]["id_printproduct"] = array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId');
        parent::__construct($id_product, $full, $id_lang, $id_shop, $context);
    }
    public function getCustomizationFields($id_lang = false, $id_shop = null)
    {
        
        
        if (isset($this->print_product ))
            return  $this->print_product->getCustomizationFields($id_lang , $id_shop );
        else
            return parent::getCustomizationFields($id_lang , $id_shop );

        
    }
    public function getCustomizationFieldIds()
    {
        if (isset($this->print_product ))
            return  $this->print_product->getCustomizationFieldIds($id_lang , $id_shop );
        else
            return parent::getCustomizationFieldIds($id_lang , $id_shop );
    }
}
