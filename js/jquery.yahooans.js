jQuery(document).ready(function() {
   
    jQuery("i.js_see_ans").click(function(){
	var chosen_p_id = jQuery(this).attr("val");
	jQuery("p.js_chosen_ans").hide();
	jQuery("p#js_chosen_"+chosen_p_id).show('2000');
	
    });
   
   
});
