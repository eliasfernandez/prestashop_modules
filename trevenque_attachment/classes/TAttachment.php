<?php
/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customite PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class TAttachment extends ObjectModel
{
    public $id_attachment;
    public $id;
    public $id_attachment_category;
    public $image;
    public $force_id = true;
    public static $definition = array(
        'table' => 'trevenque_attachment',
        'primary' => 'id_attachment',
        'multilang' => false,
        'multilang_shop' => false,
        'fields' => array(
            'id_attachment' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'id_attachment_category' => array('type' => self::TYPE_INT)
        ),
    );

    public function __construct($id_attachment, $id_lang = null, $id_shop = null)
    {
        
        $this->id = $id_attachment;
        $this->id_attachment = $id_attachment;


       
        
        return parent::__construct($id_attachment, $id_lang, $id_shop);
    }
    public function getImage($id_attachment)
    {
        if (file_exists(_PS_IMG_DIR_."atts/".$id_attachment.".jpg")) {
            return _PS_IMG_."atts/".$id_attachment.".jpg";
        }

        return false;
    }
    public function save($null_values = false, $auto_date = true)
    {
        $this->delete();
        if ($this->id_attachment_category != 0) {
            $this->add($auto_date, $null_values);
        }
    }
    
}
