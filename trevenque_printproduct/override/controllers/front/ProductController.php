<?php
/**
 * Trevenque parent canonical
 * 
 *
 * @version   : 1.0.1
 * @date      : 2016 03 16
 * @author    : Elías <elias@trevenque.es>
 * @license   : http://opensource.org/licenses/osl-3.0.php
 * @compatibility : PS == 1.6.1.0
 */

class ProductController extends ProductControllerCore
{
   

    protected function textRecord()
    {

        if (!$field_ids = $this->product->getCustomizationFieldIds())
            return false;
     
        
        
        $authorized_text_fields = array();
        foreach ($field_ids as $field_id)
            if ($field_id['type'] == Product::CUSTOMIZE_TEXTFIELD)
                $authorized_text_fields[(int)$field_id['id_customization_field']] = 'textField'.(int)$field_id['id_customization_field'];

        $indexes = array_flip($authorized_text_fields);
       
        foreach ($_POST as $field_name => $value)
            if (in_array($field_name, $authorized_text_fields))
            {
                if (!Validate::isMessage($value))
                    $this->errors[] = Tools::displayError('Invalid message');
                else{
                    $qty = Tools::getValue('quantity_wanted',null);
                    if ($value != ""){
                        if ($this->product->id_printproduct){
                            
                            $this->context->cart->addTextFieldToProduct($this->product->id_printproduct, $indexes[$field_name], Product::CUSTOMIZE_TEXTFIELD, "ref ".$this->product->reference.":".$value);
                            $this->context->cart->updateQty($qty, $this->product->id_printproduct);                
                          

                        }else
                            $this->context->cart->addTextFieldToProduct($this->product->id, $indexes[$field_name], Product::CUSTOMIZE_TEXTFIELD, $value);
                        
                    }
                    $attributes = Tools::getValue('attributes', null);
                   

                    $attributes  = json_decode($attributes);
                    $attrs=Array();                     $attrs[]=0;                         foreach ($attributes as $key => $att){
                            $attrs[] = $att;
                    }
                    $id_product_attribute=null;
                    if ($attributes){
                                                                                                
                        $sql = "SELECT pac.id_product_attribute
                                            FROM ps_attribute a
                                            INNER JOIN ps_product_attribute_combination pac ON (pac.id_attribute=a.id_attribute AND pac.id_attribute IN (".implode(", ",$attrs).")  )
                                            INNER JOIN ps_product_attribute pa ON (pa.id_product=".$this->product->id." AND pac.id_product_attribute = pa.id_product_attribute )
                                GROUP BY pac.id_product_attribute
                                ORDER BY COUNT(pac.id_product_attribute)  DESC";
                        $id_product_attribute = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);

                    }


                    $this->context->cart->updateQty($qty, $this->product->id, $id_product_attribute);                
      
                
                  
                    Tools::redirect('index.php?controller=order');
                }
            }

    }

}