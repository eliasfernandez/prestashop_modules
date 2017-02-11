<?php 

class Trevenque_AttachmentAttachmentModuleFrontController extends ModuleFrontController
{
    /**
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        include_once _PS_ROOT_DIR_.'/modules/trevenque_attachment/classes/TAttachmentCategory.php';
        include_once _PS_ROOT_DIR_.'/modules/trevenque_attachment/classes/TAttachment.php';
        

        $this->module = Module::getInstanceByName(Tools::getValue('module')); // an instance of the module is created
        parent::initContent();
        $id_attachment_category = (int)Tools::getValue('id_attachment_category');
        $id_attachment = (int)Tools::getValue('id_attachment');

        if ($id_attachment) {
            $this->getAttachment($id_attachment);
        }

        if ($id_attachment_category) {
            $this->showCategory($id_attachment_category);
        }
        
        $this->showCategoryList($id_attachment_category);
        $this->setTemplate('attachment.tpl');
        //return parent::initContent();
    }
    protected function showCategory($id_attachment_category = 0)
    {
        $attachment_category   = new TAttachmentCategory($id_attachment_category, $this->context->language->id);
        $this->context->smarty->assign(array(
            "attachments"=>TAttachmentCategory::getAttachments((int)$this->context->language->id, $id_attachment_category),
            "attachment_category"=> $attachment_category,
            "attachment_breadcrumb"=> $attachment_category->getCategoryParents((int)$this->context->language->id),

        ));
    }
    protected function showCategoryList($id_attachment_category = 0)
    {
        
        $this->context->smarty->assign(array(
                 "attachment_categories"=>TAttachmentCategory::getCategoryList((int)$this->context->language->id, $id_attachment_category),

            ));
    }

    public function getAttachment($id_attachment)
    {
        $a = new Attachment(Tools::getValue('id_attachment'), $this->context->language->id);
        if (!$a->id) {
            Tools::redirect('index.php');
        }

        Hook::exec('actionDownloadAttachment', array('attachment' => &$a));

        if (ob_get_level() && ob_get_length() > 0) {
            ob_end_clean();
        }

        header('Content-Transfer-Encoding: binary');
        header('Content-Type: '.$a->mime);
        header('Content-Length: '.filesize(_PS_DOWNLOAD_DIR_.$a->file));
        #header('Content-Disposition: attachment; filename="'.utf8_decode($a->file_name).'"');
        header('filename="'.utf8_decode($a->file_name).'"');
        @set_time_limit(0);
        readfile(_PS_DOWNLOAD_DIR_.$a->file);
        exit;
    }
 }
