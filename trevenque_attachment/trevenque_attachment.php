<?php
/*******************************************
 *
 * 2016 - Trevenque
 *
 * Elías <elias@trevenque.es>
 *
 ******************************************
 *
 *  Muesta el codigo de remarketing en el hook del FOOTER
 *  solo hay que introducir los valores correctos
 *  de conversion_id y conversion_label
 *
 *  En el .tpl se han definido las reglas para mostrar
 *  unos u otros tags dependiendo de la pagina segun
 *  la documentacion de google
 *
 *******************************************/

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once dirname(__FILE__).'/classes/TAttachmentCategory.php';

class Trevenque_Attachment extends Module
{
    public function __construct()
    {
        $this->name = 'trevenque_attachment';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'Elías Fernández';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Trevenque Attachment');
        $this->description = $this->l('Add simple categories to attachment.');
    }

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
		          var_dump($query);exit;
                return false;
            }
        }

        return parent::install() &&
        //$this->installFixtures() &&
        $this->registerHook('DisplayAttachmentTree') &&
        mkdir(_PS_ROOT_DIR_."/img/atts/");
    }

    public function hookDisplayAttachmentTree()
    {
        if (!$this->isCached('trevenquemenu.tpl', $this->getCacheId())) {
            $this->preProcess();
        }
    
        return $this->display(__FILE__, 'trevenque_attachment.tpl', $this->getCacheId());
    }
    public function getContent()
    {

        $this->context->controller->addJqueryPlugin('tablednd');
        $this->context->controller->addJS($this->_path.'views/js/position.js');
        $this->context->controller->addJS($this->_path.'views/js/back.js');

        if (Tools::isSubmit('addattachmentcategory') || Tools::isSubmit('updateattachment_category')) {
            return $this->renderAttachmentCategoryForm();
        } elseif (Tools::isSubmit('deleteattachment_category')) {
	    $this->processDeleteAttachmentCategory();
	    
        } elseif (Tools::isSubmit('saveattachmentcategory')) {
            if ($this->processSaveAttachmentCategory()) {
                return $this->renderAttachmentCategoryList();
            } else {
                return $this->renderAttachmentCategoryForm();
            }
        } elseif (Tools::getValue('updatePositions') == 'attachment_category') {
            $this->updatePositionsAttachmentCategory();
        } 
        
        
        
        
	   return $this->renderAttachmentCategoryList();
        
    }

    protected function updatePositionsAttachmentCategory()
    {
        $positions = Tools::getValue('attachment_category');

        if (empty($positions)) {
            return;
        }

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);

            if (isset($pos[2])) {
                TAttachmentCategory::updatePosition($pos[2], $position + 1);
            }
        }
        //$this->_clearCache('*');
    }
    protected function processDeleteAttachmentCategory()
    {
        $id_attachment_category = (int)Tools::getValue('id_attachment_category');
        if ($id_attachment_category) {
            $attachment_category = new TAttachmentCategory($id_attachment_category);
	    return $attachment_category->delete();
        }
	return false;

    }
    
    protected function processSaveAttachmentCategory()
    {
        $id_attachment_category = (int)Tools::getValue('id_attachment_category');
        if ($id_attachment_category) {
            $attachment_category = new TAttachmentCategory($id_attachment_category);
        } else {
            $attachment_category = new TAttachmentCategory();
        }

        /*if (isset($_FILES['drop_bgimage']) && isset($_FILES['drop_bgimage']['tmp_name']) && !empty($_FILES['drop_bgimage']['tmp_name']))
        {
            if ($error = ImageManager::validateUpload($_FILES['drop_bgimage'], Tools::convertBytes(ini_get('upload_max_filesize'))))
                $this->html .= '<div class="alert alert-danger conf error">'.$error.'</div>';
            else
            {
                if (move_uploaded_file($_FILES['drop_bgimage']['tmp_name'], $this->local_path._BG_IMAGES_FOLDER_.$_FILES['drop_bgimage']['name']))
                    $attachment_category->drop_bgimage = $_FILES['drop_bgimage']['name'];
                else
                    $this->html .= '<div class="alert alert-danger conf error">'.$this->l('File upload error.').'</div>';
            }
        }*/
        
        $languages = Language::getLanguages(false);
        $id_lang_default = (int)$this->context->language->id;
        $name = array();

        foreach ($languages as $lang) {
            $name[$lang['id_lang']] = Tools::getValue('name_'.$lang['id_lang']);
            if (!$name[$lang['id_lang']]) {
                $name[$lang['id_lang']] = Tools::getValue('name_'.$id_lang_default);
            }
        }
        $attachment_category->name = $name;
        $id_parent = Tools::getValue("id_parent");
        if ($id_parent) {
            $attachment_category->id_parent = $id_parent;
        }

        $result = $attachment_category->validateFields(false) && $attachment_category->validateFieldsLang(false);

        
        if ($result) {
            $attachment_category->save();
            //$this->_clearCache('*');
        } else {
            $this->html .= '<div class="alert alert-danger conf error">'.$this->l('An error occurred while attempting to save Menu.').'</div>';
        }

        return $result;
    }

    protected function getCategoryFieldsValues()
    {
        $fields_value = array();

        $id_attachment_category = (int)Tools::getValue('id_attachment_category');
        $category = new TAttachmentCategory($id_attachment_category);

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $default_name = isset($category->name[$lang['id_lang']]) ? $category->name[$lang['id_lang']] : '';
            $fields_value['name'][$lang['id_lang']] = Tools::getValue('name_'.(int)$lang['id_lang'], $default_name);
        }

        $fields_value['id_attachment_category'] = $id_attachment_category;
        $fields_value['position'] = Tools::getValue('position', $category->position);
        $fields_value['id_parent'] = Tools::getValue('id_parent', $category->id_parent);
        
        return $fields_value;
    }
    protected function renderAttachmentCategoryForm()
    {
        $id_attachment_category = (int)Tools::getValue('id_attachment_category');

        $legent_title = $this->l('Add New Attachment Category');
        if ($id_attachment_category) {
            $legent_title = $this->l('Edit Attachment Category');
        }


        $image_url = false;
        $image_size = false;
        if ($id_attachment_category) {
            $attachment_category = new TAttachmentCategory($id_attachment_category);
            /*if ($attachment_category->drop_bgimage)
            {
                $image_url = $this->_path._BG_IMAGES_FOLDER_.$attachment_category->drop_bgimage;
                $image_size = filesize($this->local_path._BG_IMAGES_FOLDER_.$attachment_category->drop_bgimage) / 1000;
            }*/
        }
        $cats = array_merge(array(array(
                                'id_attachment_category'=>0,
                                'name' => "----"
            )), TAttachmentCategory::getList((int)$this->context->language->id, false));

        foreach ($cats as $k => $cat) {
            if ($cat["id_attachment_category"] ==  $attachment_category->id) {
                unset($cats[$k]);
            }
            if ($cat["id_parent"] == $attachment_category->id) {
                unset($cats[$k]);
            }

        }
        

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $legent_title,
                    'icon' => 'icon-book',
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'id_attachment_category',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Name'),
                        'name' => 'name',
                        'lang' => true,
                        'required' => true,
                    ),

                    array(
                        'type' => 'select',
                        'label' => $this->l('Parent Category'),
                        'name' => 'id_parent',
                        'required' => false,
                        'options' => array(
                            'query' => $cats ,
                            'id' => 'id_attachment_category',
                            'name' => 'tree_name'
                        ),
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'position'

                    ),
                    
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'buttons' => array(
                    array(
                        'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
                        'title' => $this->l('Back to Menu List'),
                        'icon' => 'process-icon-back'
                    )
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);


        $helper->identifier = "id_attachment_category";
        $helper->submit_action = 'saveattachmentcategory';
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getCategoryFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_form));
    }
    public function renderAttachmentCategoryList()
    {
        $fields_list = array(
            'id_attachment_category' => array(
                'title' => $this->l('Attachment Category ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'orderby' => false,
                'search' => false,
                'type' => 'tid_attachmen_category',
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'orderby' => false,
                'search' => false,
                'lang' => false,
                'type' => 'attachmentcategory'
            ),
            'position' => array(
                'title' => $this->l('Position'),
                'align' => 'center',
                'orderby' => false,
                'search' => false,
                'class' => 'fixed-width-md',
                'position' => true,
                'type' => 'tposition',
            )

        );
 //       $this->addRowAction('view');
        $helper = new HelperList();
        $id_attachment_category = (int)Tools::getValue('id_attachment_category');
        $attachment_categorys = TAttachmentCategory::getCategoryList((int)$this->context->language->id, $id_attachment_category, false);
        
        $attachment_category = new TAttachmentCategory($id_attachment_category, (int)$this->context->language->id);
        $parents = $attachment_category->getCategoryParents((int)$this->context->language->id);

        $helper->tpl_vars["att_category_parents"] = $parents;


        $helper->shopLinkType = '';
        $helper->toolbar_btn['new'] = array(
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addattachmentcategory&token='.Tools::getAdminTokenLite('AdminModules').($id_attachment_category ? "&id_parent=$id_attachment_category":""),
            'desc' => $this->l('New Attachment Category')
        );
        $helper->simple_header = false;
        $helper->listTotal = count($attachment_categorys);
        $helper->identifier = 'id_attachment_category';
        $helper->table = 'attachment_category';
        $helper->actions = array('view','edit', 'delete');
        $helper->show_toolbar = true;
        $helper->no_link = false;
        $helper->module = $this;
        $helper->title = $this->l('Attachment Categories');
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->position_identifier = 'attachment_category';
        $helper->position_group_identifier = 0;

        return $helper->generateList($attachment_categorys, $fields_list);
    }
}
