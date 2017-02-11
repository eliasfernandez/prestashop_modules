<?php
/*******************************************
 *
 * 2015 - Trevenque
 *
 * Elías <elias@trevenque.es>
 *
 *******************************************/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Trevenque_ParentProduct extends Module
{
    public function __construct()
    {
        $this->name = 'trevenque_parentproduct';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Elías';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Trevenque Parent product');
        $this->description = $this->l('Allows to set a parent product for a product, modifying the canonical url of the child to point to parent');
    }

    // Creamos la tabla donde se almacenarán los product canonical
    public function install()
    {
        if (!file_exists(dirname(__FILE__).'/sql/install.sql')) {
            return false;
        } else if (!$sql = Tools::file_get_contents(dirname(__FILE__).'/sql/install.sql')) {
            return false;
        }

        $sql = str_replace(array('PREFIX_', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
        $sql = preg_split("/;\s*[\r\n]+/", trim($sql));

        foreach ($sql as $query) {
            if (!Db::getInstance()->execute(trim($query))) {
                return false;
            }
        }

        return  parent::install()
                && $this->registerHook('displayAdminProductsExtra')
                && $this->registerHook('displayProductContent')
                && $this->registerHook('actionProductSave');
    }


    public function uninstall()
    {
        $sql = 'DROP TABLE `'._DB_PREFIX_.'trevenque_parentproduct`';
        Db::getInstance()->execute($sql);
        
        return parent::uninstall()
            && $this->unregisterHook('displayAdminProductsExtra')
            && $this->unregisterHook('displayProductContent')
            && $this->unregisterHook('actionProductSave');
    }


    public function prepareNewTab()
    {
     
        $this->context->smarty->assign(array(
            'custom_field' => '',
            'languages' => $this->context->controller->_languages,
            'default_language' => (int)Configuration::get('PS_LANG_DEFAULT')
        ));
    }
    public function hookDisplayProductContent($params)
    {

        $product = $params["product"];
        $product->id_productparent = self::getParentProduct($product);
        $product->siblings = self::getSiblings($product);
    }
    public static function getParentProduct($product)
    {
        $sql = 'SELECT tp.id_parent FROM `'._DB_PREFIX_.'product` p
               INNER JOIN `'._DB_PREFIX_.'trevenque_parentproduct` tp ON (tp.id_product = p.id_product)
               WHERE tp.id_product = '.(int)$product->id.';';

        return Db::getInstance()->getValue($sql);
    }
    
    protected function getSiblings($product)
    {
        $id_productparent = (int) $product->id_productparent;
        $id_lang = Context::getContext()->language->id;
      
        
        $sql = 'SELECT p.id_product, pl.name FROM `'._DB_PREFIX_.'product` p
                INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON(pl.id_product = p.id_product AND pl.id_lang='.$id_lang.')
                LEFT JOIN `'._DB_PREFIX_.'trevenque_parentproduct` tp ON (tp.id_product = p.id_product)
                WHERE 
                    '.(
                        ($id_productparent==0)?
                        '(tp.id_parent ='. $product->id .' OR p.id_product ='.$product->id.')':
                        '(tp.id_parent ='. $id_productparent .' OR p.id_product ='.$id_productparent.')'
                        );
             

        $siblings = Db::getInstance()->executes($sql);
        if (count($siblings)<2) { // si no hay artículos que comparar
            return array();
        }
       
        $parts = explode(" ", $siblings[0]["name"]); //extraemos el primero
        foreach ($siblings as $key => $sibling) {
            $sibling_name = $sibling["name"];
            $sibling_parts = explode(" ", $sibling_name);
            $diff_sibling_parts = array_diff($sibling_parts, $parts);//remove common first parts
            $siblings[$key]["name"] = implode(" ", $diff_sibling_parts);
        }
        $siblings[0]["name"] = implode(" ", array_diff($parts, $sibling_parts));

        return $siblings;
    }

    public function hookActionProductSave($params)
    {
        $id_product = $params["id_product"];
        $id_parent = Tools::getValue("id_productparent");
        $sql = "DELETE FROM `"._DB_PREFIX_."trevenque_parentproduct` WHERE id_product = $id_product;";
        Db::getInstance()->execute($sql);
        if (isset($id_product) && $id_parent > 0) {
            $sql .= "INSERT INTO `"._DB_PREFIX_."trevenque_parentproduct` VALUES($id_product, $id_parent);";
            Db::getInstance()->execute($sql);
        }
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        if (Validate::isLoadedObject($product = new Product((int)Tools::getValue('id_product')))) {
            $product->id_productparent = self::getParentProduct($product);

            $this->prepareNewTab();
            $sql = 'SELECT p.id_product, pl.name FROM `'._DB_PREFIX_.'product` p
                    LEFT JOIN `'._DB_PREFIX_.'trevenque_parentproduct` tp ON (tp.id_product = p.id_product)
                    INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.id_product=p.id_product AND pl.id_lang='.$this->context->language->id.') 
                    WHERE ISNULL(tp.id_parent) AND p.active=1 AND NOT p.id_product='.$product->id.'';

            $products = Db::getInstance()->executes($sql);
            $this->context->smarty->assign(array(
                    "products" => $products,
                    "id_productparent" => $product->id_productparent
                ));
            //var_dump($products, $sql);
            return $this->display(__FILE__, 'productparent.tpl');
        }
    }
}
