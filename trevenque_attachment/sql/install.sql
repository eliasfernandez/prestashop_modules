CREATE TABLE  IF NOT EXISTS  `PREFIX_trevenque_attachment` (          
                           `id_attachment` int(11) NOT NULL,               
                           `id_attachment_category` int(11) DEFAULT NULL,  
                           PRIMARY KEY (`id_attachment`)                   
                         ) ENGINE=InnoDB DEFAULT CHARSET=utf8              
;

CREATE TABLE IF NOT EXISTS  `PREFIX_attachment_category` (  
                                    `id_attachment_category` int(11) NOT NULL  AUTO_INCREMENT,       
                                    `position` int(11) NOT NULL,       
                                    `id_parent` int(11) DEFAULT 0,  
                                    PRIMARY KEY (`id_attachment_category`)           
                                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 
;


CREATE TABLE IF NOT EXISTS  `PREFIX_attachment_category_lang` (  
                                    `id_attachment_category` int(11) NOT NULL,       
                                    `id_lang` int(11) NOT NULL,        
                                    `name` VARCHAR(255) NOT NULL,       
                                    PRIMARY KEY (`id_attachment_category`)           
                                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8   
;
