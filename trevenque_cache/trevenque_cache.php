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

class Trevenque_Cache extends Module
{
    public function __construct()
    {
        $this->name = 'trevenque_cache';
        $this->tab = 'frontend';
        $this->version = '0.9';
        $this->author = 'Elías';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Trevenque Cache template'); 
        $this->description = $this->l('Minimal cache system html per category, product, index anonymal request ');

    }

    
    public function install()
    {
	@mkdir(_PS_ROOT_DIR_."/cache/sardine/");

        return  parent::install() 
		&& $this->registerHook('actionProductSave') 
		&& $this->registerHook('actionCategoryAdd') 
		&& $this->registerHook('actionCategoryDelete')
		&& $this->registerHook('actionCategoryUpdate') ;
    }
    public function hookActionProductSave($params)
    {
	$id_product= $params["id_product"];
	$product = $params["product"];
        self::purge("product-{$id_product}*");
        foreach ($product->getCategories() as $cat) {
            self::purge("category-{$cat}*");
        }
    }
    public function hookActionCategoryDelete($params)
    {
	$category= $params["category"];
        self::purge("category-{$category->id}*");
    }
    public function hookActionCategoryAdd($params)
    {
	$category= $params["category"];
        self::purge("category-{$category->id}*");
    }
    public function hookActionCategoryUpdate($params)
    {
	$category= $params["category"];
        self::purge("category-{$category->id}*");
    }

    public function uninstall()
    {
    
        return parent::uninstall();
    }
    public static function purge($pattern = "*")
    {
	$pattern = str_replace(DIRECTORY_SEPARATOR, "", $pattern);
	//var_dump(_PS_ROOT_DIR_);
	
	array_map('unlink', glob(_PS_ROOT_DIR_."/cache/sardine/$pattern"));
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitCacheChange'))
        {
            Trevenque_Cache::purge("*");
            return $this->displayConfirmation($this->l('Correctly purged.'));
        }
        return '';
    }

    public function getContent()
    {
        return $this->postProcess().$this->renderForm();
    }

   
    public function renderForm()
    {
        $files = glob(_PS_ROOT_DIR_."/cache/sardine/*.html");

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l(count($files)." files"),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                 
                ),
                'submit' => array(
                    'title' => $this->l('Purge files')
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table =  $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitCacheChange';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

   
}


