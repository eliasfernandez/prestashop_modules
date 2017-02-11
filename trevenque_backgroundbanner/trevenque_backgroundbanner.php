<?php
/*******************************************
 *
 * 2016 - Trevenque
 *
 * Elías Fernández
 *
 ******************************************
 *
 * Agregaga un hook al footer que muestra una imagen de fondo y un enlace cuando:
 *  * está activo en el módulo
 *  * Se cumplen las condiciones de comienzo y fín.  
 *******************************************/

if (!defined('_PS_VERSION_'))
    exit;



class Trevenque_BackgroundBanner extends Module
{
    public $html = '';

    public function __construct()
    {
        $this->name = 'trevenque_backgroundbanner';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'Elías Fernandez';
        $this->bootstrap = true;
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Trevenque Background Banner');
        $this->description = $this->l('Show a clickable background');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return  parent::install() &&
                $this->installFixtures() &&
                $this->createImgFolder() &&
                $this->registerHook('footer') &&
                $this->installTab() &&
                $this->disableDevice(Context::DEVICE_TABLET | Context::DEVICE_MOBILE);
    }

    protected function installFixtures()
    {
        Configuration::updateValue('GT_BANNER_ACTIVE', '');
        Configuration::updateValue('GT_BANNER_IMG', '');
        Configuration::updateValue('GT_BANNER_NAME', '');
        Configuration::updateValue('GT_BANNER_BEGIN', '');
        Configuration::updateValue('GT_BANNER_END', '');
        Configuration::updateValue('GT_BANNER_ACTION', '');

        return true;
    }

    public function createImgFolder()
    {
        $target_dir = _PS_IMG_DIR_ . $this->name;
        return
            is_dir($target_dir) ||
            mkdir($target_dir, 0755, true);
    }

   

    /**
     * This will add a new Tab on the Back office menu
     *
     * @param string $tab_class Class name of the Tab
     * @param string $title Title of the tab (name)
     * @param string $tab_parent Tab parent on where to put the newly created tab
     * @param boolean $active (default true) Indicate weither this new table is active or not
     *
     * @return boolean
     */
    public function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->name = array();
        $tab->class_name = 'AdminBackgroundBanner';

        foreach (Language::getLanguages(true) as $lang)
            $tab->name[$lang['id_lang']] = 'Background Banner';

        $tab->id_parent = -1;
        $tab->module = $this->name;

        return $tab->add();
    }

    /**
     * This will remove the Tab on the Back office menu
     * @return boolean
     */
    public function uninstallTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminBackgroundBanner');

        if ($id_tab)
        {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }

        return false;
    }


    public function uninstall()
    {
        return  parent::uninstall() &&
                $this->deleteImgFolder() &&
                $this->uninstallTab() &&
                $this->unregisterHook('footer');
    }

    
    public function deleteImgFolder()
    {
        // de momento no borramos las imagenes ni el directorio
        // $target_dir = _PS_IMG_DIR_ . $this->name;
        return true;
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitStoreConf'))
        {
            if (isset($_FILES['GT_BANNER_IMG']) && $_FILES['GT_BANNER_IMG']["error"] != UPLOAD_ERR_NO_FILE)
            {
                if ($error = ImageManager::validateUpload($_FILES['GT_BANNER_IMG'], 4000000))
                    return $error;
                else
                {
                    $ext = substr($_FILES['GT_BANNER_IMG']['name'], strrpos($_FILES['GT_BANNER_IMG']['name'], '.') + 1);
                    $file_name = md5($_FILES['GT_BANNER_IMG']['name']).'.'.$ext;
                    if (!move_uploaded_file($_FILES['GT_BANNER_IMG']['tmp_name'], _PS_IMG_DIR_ . $this->name . DIRECTORY_SEPARATOR.$file_name))
                        return $this->displayError($this->l('An error occurred while attempting to upload the file.'));
                }

                $update_images_values = true;
                Configuration::updateValue('GT_BANNER_IMG', $file_name);
            }
            
            Configuration::updateValue('GT_BANNER_ACTIVE', Tools::getValue('GT_BANNER_ACTIVE'));
            Configuration::updateValue('GT_BANNER_NAME', Tools::getValue('GT_BANNER_NAME'));
            Configuration::updateValue('GT_BANNER_BEGIN', Tools::getValue('GT_BANNER_BEGIN'));
            Configuration::updateValue('GT_BANNER_END', Tools::getValue('GT_BANNER_END'));
            Configuration::updateValue('GT_BANNER_ACTION', Tools::getValue('GT_BANNER_ACTION'));

            return $this->displayConfirmation($this->l('The settings have been updated.'));
        }
        return '';
    }
    public function renderForm()
    {


        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Is Active?'),
                        'name' => 'GT_BANNER_ACTIVE',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        ),
                        'desc' => $this->l('Set active if campaign is active.')

                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Campaign name'),
                        'name' => 'GT_BANNER_NAME',
                        'desc' => $this->l('Please enter the campaign name.')
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->l('Background Banner Image'),
                        'name' => 'GT_BANNER_IMG',
                        'image' =>  "<img src='"._PS_IMG_ . $this->name . DIRECTORY_SEPARATOR . Configuration::get('GT_BANNER_IMG')."' class='img-responsive' />"
                        //'size'  => ""
                        
                    ),
                    array(
                        'type' => 'date',
                        'label' => $this->l('Campaign begin'),
                        'name' => 'GT_BANNER_BEGIN',
                        'desc' => $this->l('Please enter the campaign begin date.')
                    ),

                    array(
                        'type' => 'date',
                        'label' => $this->l('Campaign end'),
                        'name' => 'GT_BANNER_END',
                        'desc' => $this->l('Please enter the campaign end date.')
                    ),

                    array(
                        'type' => 'text',
                        'label' => $this->l('Campaign action'),
                        'name' => 'GT_BANNER_ACTION',
                        'desc' => $this->l('Please enter the Google Conversion Label.')
                    ),


                ),
                'submit' => array(
                    'title' => $this->l('Save')
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
        $helper->submit_action = 'submitStoreConf';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'fields_value' => $this->getConfigFieldsValues(),
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        $fields = array();

        $fields['GT_BANNER_ACTIVE'] =  Tools::getValue('GT_BANNER_ACTIVE', Configuration::get('GT_BANNER_ACTIVE'));
        $fields['GT_BANNER_IMG'] =  Tools::getValue('GT_BANNER_IMG', Configuration::get('GT_BANNER_IMG'));
        $fields['GT_BANNER_NAME'] =  Tools::getValue('GT_BANNER_NAME', Configuration::get('GT_BANNER_NAME'));
        $fields['GT_BANNER_BEGIN'] =  Tools::getValue('GT_BANNER_BEGIN', Configuration::get('GT_BANNER_BEGIN'));
        $fields['GT_BANNER_END'] =  Tools::getValue('GT_BANNER_END', Configuration::get('GT_BANNER_END'));
        $fields['GT_BANNER_ACTION'] =  Tools::getValue('GT_BANNER_ACTION', Configuration::get('GT_BANNER_ACTION'));
        
        return $fields;
    }
    public function getContent()
    {
        return $this->postProcess().$this->renderForm();
    }

    public function hookFooter($params)
    {
        $lower_than_begin   = time() - strtotime(Configuration::get('GT_BANNER_BEGIN')) > 0;
        $upper_than_end     = strtotime(Configuration::get('GT_BANNER_END')) - time();

        if (Configuration::get('GT_BANNER_ACTIVE') && $lower_than_begin && $upper_than_end) {
            $fields = array();
            $fields['img'] =  _PS_IMG_ . $this->name . DIRECTORY_SEPARATOR . Configuration::get('GT_BANNER_IMG');
            $fields['action'] = Configuration::get('GT_BANNER_ACTION');
            $fields['name'] = Configuration::get('GT_BANNER_NAME');
            $this->context->smarty->assign($fields);
            return $this->display(__FILE__, 'trevenque_backgroundbanner.tpl');


        } else {
            return "";
        }
    }

    


}
