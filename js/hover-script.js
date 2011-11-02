/*
 * This will do an hover effect to show you the edit and delete links
 */
 
 
 jQuery(document).ready(function($){
	 $('.wp-clicky-td').hover(
		function(){
			//alert('g');
			$(this.childNodes[3].childNodes[1]).css({'visibility':'visible'});
		},
		function(){
			$(this.childNodes[3].childNodes[1]).css({'visibility':'hidden'});
		}
	 );
 });