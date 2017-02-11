<?php
/*******************************************
 *
 * 2016 - Trevenque
 *
 *
 *******************************************/


require(dirname(__FILE__) . '/../../config/config.inc.php');
require "trevenque_cache.php";


if (Tools::getValue('token') != Configuration::getGlobalValue('CRONJOBS_EXECUTION_TOKEN'))
        die('Invalid token');

$pattern = Tools::getValue("pattern");
if($pattern)
	Trevenque_Cache::purge($pattern);

