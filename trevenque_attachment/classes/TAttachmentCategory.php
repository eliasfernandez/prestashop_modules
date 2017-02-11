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

class TAttachmentCategory extends ObjectModel
{
    public $id;
    public $id_attachment_category;
    public $id_parent;

    public $name = 1;
    public $position;
    public $attachments = array();
    

    public static $definition = array(
        'table' => 'attachment_category',
        'primary' => 'id_attachment_category',
        'multilang' => true,
        'multilang_shop' => false,
        'fields' => array(
            'id_attachment_category' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'id_parent' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'position' => array('type' => self::TYPE_INT),
            'name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 254),
        ),
    );

    public function __construct($id_attachment_category = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id_attachment_category, $id_lang, $id_shop);

        if (!$this->position) {
            $this->position = 1 + $this->getMaxPosition();
        }
    }
    public function getAttachments($id_lang, $id_attachment_category = 0)
    {
        if (count($this->attachments == 0)) {
            $sql = '
                SELECT ta.id_attachment
                FROM `'._DB_PREFIX_.'trevenque_attachment` ta
                WHERE  ta.id_attachment_category = '. ((int) $id_attachment_category)
                .(!$id_lang ? ' GROUP BY a.id_attachment_category' : '');

            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
           
            foreach ($result as $v) {
                $a = new Attachment($v["id_attachment"], $id_lang);
                $image = TAttachment::getImage($v["id_attachment"]);
                if ($image) {
                    $a->image = $image;
                }
                $this->attachments[] = $a;
            }
        }
        return $this->attachments;
    }
    public static function getList($id_lang, $active = true)
    {
        $sql = '
            SELECT m.*, ml.* , IF(m_parent.id_attachment_category, CONCAT(ml_parent.name, " -> ", ml.name), ml.name) as tree_name
            FROM `'._DB_PREFIX_.'attachment_category` m
            LEFT JOIN `'._DB_PREFIX_.'attachment_category_lang` ml ON m.`id_attachment_category` = ml.`id_attachment_category`
            LEFT JOIN `'._DB_PREFIX_.'attachment_category` m_parent ON (m.id_parent = m_parent.id_attachment_category)
            LEFT JOIN `'._DB_PREFIX_.'attachment_category_lang` ml_parent ON (m_parent.`id_attachment_category` = ml_parent.`id_attachment_category` AND ml_parent.`id_lang` = '.((int)$id_lang).')

            WHERE 1 '.($id_lang ? 'AND ml.`id_lang` = '.(int)$id_lang: '')
            .($active ? 'AND `active` = 1' : '').'
            '.(!$id_lang ? 'GROUP BY m.id_attachment_category' : '')
            .'ORDER BY m.`position` ASC';
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        return $result;
    }
    public static function getCategoryList($id_lang, $id_attachment_category = 0)
    {
        $sql = '
            SELECT m.*, ml.name 
            FROM `'._DB_PREFIX_.'attachment_category` m
            LEFT JOIN `'._DB_PREFIX_.'attachment_category_lang` ml ON (m.`id_attachment_category` = ml.`id_attachment_category`)
            WHERE 1 '.($id_lang ? 'AND ml.`id_lang` = '.(int)$id_lang: '')
            ." AND id_parent = $id_attachment_category "
            .(!$id_lang ? ' GROUP BY m.id_attachment_category' : '')
            .' ORDER BY m.`position` ASC';
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        return $result;
    }
    public static function getMaxPosition()
    {
        return (int)Db::getInstance()->getValue('
            SELECT MAX(m.`position`)
            FROM `'._DB_PREFIX_.'attachment_category` m');
    }
    
    public function getCategoryParents($id_lang)
    {
        $cats = array();
        if ($this->id_parent!=0) {
            $attachment_category = $this;
            do {
                $attachment_category = new TAttachmentCategory($attachment_category->id_parent, $id_lang);
                $cats[] = $attachment_category;

            } while ($attachment_category->id_parent != 0);
        }
        
        $cats = array_reverse($cats);
        return $cats;
    }

    public static function updatePosition($id_attachment_category, $position)
    {
        $query = 'UPDATE `'._DB_PREFIX_.'attachment_category`
            SET `position` = '.(int)$position.'
            WHERE `id_attachment_category` = '.(int)$id_attachment_category;

        Db::getInstance()->execute($query);
    }

}
