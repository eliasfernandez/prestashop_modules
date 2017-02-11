<?php
/*******************************************
 *
 * 2015 - Trevenque
 *
 * Elías <elias@trevenque.es>
 *
 *******************************************/

if (!defined('_PS_VERSION_'))
    exit;

class Trevenque_PrintProduct extends Module
{
    public function __construct()
    {
        $this->name = 'trevenque_printproduct';
        $this->tab = 'administration';
        $this->version = '1.0.1';
        $this->author = 'Elías';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Trevenque Print product option');
        $this->description = $this->l('Allows to set a print product option (with its costs)');

    }

    // Creamos la tabla donde se almacenarán los related
    public function install()
    {
        
        $sql = 'ALTER TABLE `'._DB_PREFIX_.'product` ADD COLUMN
            `id_printproduct` int(10) unsigned NOT NULL default \'0\'';

        Db::getInstance()->execute($sql);
        return  parent::install() && $this->registerHook('displayAdminProductsExtra');


    }
    public function prepareNewTab()
    {
     
        $this->context->smarty->assign(array(
            'custom_field' => '',
            'languages' => $this->context->controller->_languages,
            'default_language' => (int)Configuration::get('PS_LANG_DEFAULT')
        ));
     
    }
    public function uninstall()
    {
        $sql = 'ALTER TABLE `'._DB_PREFIX_.'product` DROP COLUMN
            `id_printproduct` int(10) unsigned NOT NULL default \'0\'';

        Db::getInstance()->execute($sql);
        
        return parent::uninstall() && $this->unregisterHook('displayAdminProductsExtra');
    }

    public function hookDisplayAdminProductsExtra($params)
    {

        if (Validate::isLoadedObject($product = new Product((int)Tools::getValue('id_product'))))
        {
            
            $this->prepareNewTab();
            $sql = 'SELECT p.id_product, pl.name FROM `'._DB_PREFIX_.'product` p
                    INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.id_product=p.id_product AND pl.id_lang='.$this->context->language->id.') 
                    WHERE p.id_printproduct=0 AND p.active=1 AND NOT p.id_product='.$product->id.'';

            $products = Db::getInstance()->executes($sql);
            //var_dump($sql);
            $this->context->smarty->assign(array(
                    "products" => $products,
                    "id_printproduct" => $product->id_printproduct
                ));
            //var_dump($products, $sql);
            return $this->display(__FILE__, 'printproduct.tpl');
        }else{
            $this->prepareNewTab();
            return $this->display(__FILE__, 'printproduct.tpl');

        }
    }   

}


