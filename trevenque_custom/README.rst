***************************
Instalación y configuración
***************************

El funcionamiento de este sistema permite que, al agregar cualquier archivo en la carpeta mobile/, se tenga en cuenta esta versión del archivo y no otro cuando se detecte que un usuario entra con el movil. 

usa las variables $smarty->is_mobile, $smarty->is_tablet, para cargar la carpeta mobile. 
Si $smarty->is_desktop no tiene en cuenta esta carpeta. 



Adaptaciones manuales en  en config/defines_uri.inc.php
===================================================


hay que añadir esta constante:
define('_PS_THEME_CUSTOM_DIR_', _PS_THEME_DIR_.'custom/');


para que esto funcione.
