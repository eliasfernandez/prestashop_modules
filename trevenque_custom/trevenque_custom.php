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

class Trevenque_Custom extends Module
{
    public function __construct()
    {
        $this->name = 'trevenque_custom';
        $this->tab = 'frontend';
        $this->version = '0.9';
        $this->author = 'Elías';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Trevenque Custom Folder + Mobile template');
        $this->description = $this->l('A simpler way to add custom tpl to the selected theme and extend the mobile functionality of PS.');
    }

    // Creamos la carpeta y creamos un README para maquetadores
    public function install()
    {
        if (!is_dir(_PS_THEME_CUSTOM_DIR_)) {
            mkdir(_PS_THEME_CUSTOM_DIR_);
            file_put_contents(_PS_THEME_CUSTOM_DIR_."README", "Funcionamiento
==============

Si un archivo existe en esta carpeta con el mismo nombre y en el mismo nivel de anidamiento, sobreescribirá al que exista en un nivel anterior. ");
        }
        return  parent::install() ;
    }
    public function uninstall()
    {
        return parent::uninstall();
    }
}
