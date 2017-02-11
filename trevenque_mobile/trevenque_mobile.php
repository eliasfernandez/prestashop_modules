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

class Trevenque_Mobile extends Module
{
    public function __construct()
    {
        $this->name = 'trevenque_mobile';
        $this->tab = 'frontend';
        $this->version = '0.9';
        $this->author = 'Elías';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Trevenque Mobile template'); 
        $this->description = $this->l('A simpler way to extend the mobile functionality of PS.');

    }

    // Creamos la tabla donde se almacenarán los related
    public function install()
    {
        
        return  parent::install() ;
    }
    public function uninstall()
    {
    

        return parent::uninstall();
    }

   
}


