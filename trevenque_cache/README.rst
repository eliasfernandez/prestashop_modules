***************************
Instalación y configuración
***************************

Sobreescribe el método dispatch de la clase Dispatcher de Prestashop para generar archivos html cacheados para usuarios anónimos sin carrito en las vistas:

* Index
* Product
* Category
* Dropdown


Sobre la caché
==============

1. Los archivos generados se borran al hacerse actualizaciones sobre los productos y categorías relacionados
2. La configuración del módulo es 1 botón que borra todos los archivos
3. Se puede usar una url tipo:   /modules/trevenque_cache/cron-purge.php?token={cron_token}&pattern={pattern} . donde la variable pattern: es una variable wildcard que luego se usa para borrar archivos de la carpeta cache/sardine/. P.ej:  pattern=product* borraría todos los html de productos.




