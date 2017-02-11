CREATE TABLE IF NOT EXISTS `PREFIX_trevenque_parentproduct` (
	`id_product` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`id_parent` int(11) unsigned NOT NULL ,
	PRIMARY KEY (`id_product`, `id_parent`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;