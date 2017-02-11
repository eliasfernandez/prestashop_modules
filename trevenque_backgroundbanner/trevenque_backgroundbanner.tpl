
<div class="bannerclick click-to-left"><a href="{$action}" title="{$name}"> &amp; </a></div>
<div class="bannerclick click-to-right"><a href="{$action}" title="{$name}">  &amp; </a></div>
<style type="text/css">

@media(min-width: 1200px) {
  body
  {
	  background-image: url('{$img}');
	  background-repeat: no-repeat;
	  background-position:  center 150px;
	  background-size: auto !important;
	  overflow-x: hidden;
  }
  body .main-content {
  		background-color: transparent;  
  }
  body .main-content > .container{ 
  		background-color:#FFF;
  		border-radius: 5px; 
  		width: 100%; 
  		max-width: 1140px; 
  		padding: 15px; 
  		position:relative;
  }
  .bannerclick{
  	position:abolute;

  }
  .bannerclick a{
  	position: absolute;
  	top: 0px;
  	width: 220px;
  	height: 1500px;
  	display:block;
  	
  	
  }
  .click-to-left a {
  	/*left: -500px;*/
  	left:0px;
 
  }
  .click-to-right a {
  	right:0px;
  
  }

}
</style>
