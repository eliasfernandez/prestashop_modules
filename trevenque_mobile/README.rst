***************************
Instalación y configuración
***************************

El funcionamiento de este sistema permite que, al agregar cualquier archivo en la carpeta mobile/, se tenga en cuenta esta versión del archivo y no otro cuando se detecte que un usuario entra con el movil. 

usa las variables $smarty->is_mobile, $smarty->is_tablet, para cargar la carpeta mobile. 
Si $smarty->is_desktop no tiene en cuenta esta carpeta. 



Adaptaciones manuales en  en config/smarty.config.inc.php
=========================================================


hay que sustituir:
    
    require_once(_PS_SMARTY_DIR_.'Smarty.class.php');
    
    global $smarty;
    $smarty = new Smarty();

por 

    require_once(_PS_ROOT_DIR_.'/modules/trevenque_mobile/smarty/Smarty.class.php');
    global $smarty;
    $smarty = new Trevenque_Smarty();

para que esto funcione.