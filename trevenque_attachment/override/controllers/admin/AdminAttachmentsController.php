<?php 

class AdminAttachmentsController extends AdminAttachmentsControllerCore 
{

    public function postProcess()
    {
        $return = parent::postProcess();
        if (Tools::isSubmit('submitAdd'.$this->table)) {
            include_once _PS_ROOT_DIR_.'/modules/trevenque_attachment/classes/TAttachment.php';
            $id = (int)Tools::getValue('id_attachment');
            $id_attachment_category = (int)Tools::getValue("id_attachment_category");
            
            if ($id && ($tattachment = new TAttachment($id)) && isset($id_attachment_category)) {
                //$tattachment->id_attachment  = $id;
                $tattachment->id_attachment_category = $id_attachment_category;
                $tattachment->save();
            }

            if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
                if ($_FILES['image']['size'] > (Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE') * 1024 * 1024)) {
                    $this->errors[] = sprintf(
                        $this->l('The image is too large. Maximum size allowed is: %1$d kB. The image you are trying to upload is %2$d kB.'),
                        (Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE') * 1024),
                        number_format(($_FILES['file']['size'] / 1024), 2, '.', '')
                    );
                } else {
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    
                    if (false === $ext = array_search(
                        $finfo->file($_FILES['image']['tmp_name']),
                        array(
                            'jpg' => 'image/jpeg',
                            'png' => 'image/png',
                            'gif' => 'image/gif',
                        ),
                        true
                    )) {
                        $this->errors[] = 'Invalid file format.';
                        return;
                    }
                    
                    if (! ImageManager::resize($_FILES['image']['tmp_name'], _PS_IMG_DIR_."atts/".$id.".jpg", 100, 100)) {
                        $this->errors[] = $this->l('Failed to copy the file.');
                    }
                    @unlink($_FILES['file']['tmp_name']);
                }
            }
        }
        return $return;
    }
    public function renderForm()
    {
        include_once _PS_ROOT_DIR_.'/modules/trevenque_attachment/classes/TAttachmentCategory.php';
        include_once _PS_ROOT_DIR_.'/modules/trevenque_attachment/classes/TAttachment.php';
        

        if (($obj = $this->loadObject(true)) && Validate::isLoadedObject($obj)) {
            /** @var Attachment $obj */
            $link = $this->context->link->getPageLink('attachment', true, null, 'id_attachment='.$obj->id);

            if (file_exists(_PS_DOWNLOAD_DIR_.$obj->file)) {
                $size = round(filesize(_PS_DOWNLOAD_DIR_.$obj->file) / 1024);
            }


            $image = _PS_IMG_DIR_."atts/".$obj->id.'.jpg';

            if (Tools::getIsset("deleteAttachmentImage")) {
                unlink($image);
            }
            if (file_exists($image)) {
                $image_url = ImageManager::thumbnail($image, $this->table.'_'.(int)$obj->id.'.'.$this->imageType, 350, $this->imageType, true, true);
                $image_size = file_exists($image) ? filesize($image) / 1000 : false;
            }

        }
        
        $TAttachment = new TAttachment($obj->id);
        $obj->id_attachment_category = $TAttachment->id_attachment_category;
        $cats = array_merge(array(array(
                                'id_attachment_category'=>0,
                                'name' => "----"
            )), TAttachmentCategory::getList((int)$this->context->language->id, false));

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Attachment'),
                'icon' => 'icon-paper-clip'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Filename'),
                    'name' => 'name',
                    'required' => true,
                    'lang' => true,
                    'col' => 4
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Description'),
                    'name' => 'description',
                    'lang' => true,
                    'col' => 6
                ),
                array(
                    'type' => 'file',
                    'file' => isset($link) ? $link : null,
                    'size' => isset($size) ? $size : null,
                    'label' => $this->l('File'),
                    'name' => 'file',
                    'required' => true,
                    'col' => 6
                ),
                array(
                        'type' => 'file',
                        'label' => $this->l('Attachment Image'),
                        'name' => 'image',
                        'hint' => $this->l('Upload a new background image for dropdown menu from your computer'),
                        'display_image' => true,
                        'image' => $image_url ? $image_url: false,
                        'size' => $image_size,
                        'delete_url' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminAttachments').'&updateattachment&deleteAttachmentImage&id_attachment='.$obj->id,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Attachment Category'),
                    'name' => 'id_attachment_category',
                    'required' => false,
                    'options' => array(
                        'query' => $cats ,
                        'id' => 'id_attachment_category',
                        'name' => 'tree_name'
                    ),
                    'desc'=>"You must create new ones <a href='?controller=AdminModules&configure=trevenque_attachment&token=".Tools::getAdminTokenLite('AdminModules')."'>here</a>"
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );

        return AdminController::renderForm();
    


    }

}