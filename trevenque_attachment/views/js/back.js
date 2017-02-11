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
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/
$( document ).ready(function() {
	$('#content_type_selectbox').change(function() {
		if (this.value == 'category')
			$('#productBox, #static_content_textarea, #manufacturerBox').closest('div.form-group').slideUp(400, function(){
				$('#categoryBox').closest('div.form-group').slideDown();
			});
		else if(this.value == 'product')
			$('#categoryBox, #static_content_textarea, #manufacturerBox').closest('div.form-group').slideUp(400, function(){
				$('#productBox').closest('div.form-group').slideDown();
			});
		else if(this.value == 'html')
			$('#categoryBox, #productBox, #manufacturerBox').closest('div.form-group').slideUp(400, function(){
				$('#static_content_textarea').closest('div.form-group').slideDown();
			});
		else if(this.value == 'manufacturer')
			$('#categoryBox, #productBox, #static_content_textarea').closest('div.form-group').slideUp(400, function(){
				$('#manufacturerBox').closest('div.form-group').slideDown();
			});
		else {
			$('#categoryBox').closest('div.form-group').slideUp();
			$('#productBox').closest('div.form-group').slideUp();
			$('#static_content_textarea').closest('div.form-group').slideUp();
			$('#manufacturerBox').closest('div.form-group').slideUp();
		}
			
	});

	$('#content_type_selectbox').trigger('change');
	
	tInitTableDnD('trevenque_attachment', 'attachment_category');
	
});