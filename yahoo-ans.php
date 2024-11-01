<?PHP
/*
Plugin Name: Yah00-ans
Plugin URI: http://www.arcgate.com/
Description: This plugin allows you to show Yahoo Answers posts as a widget.
Version: 1.0
Author: Imran khan
Author URI: http://www.arcgate.com
*/

/*  Copyright 2010 Imran khan - imran@arcgate.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function yahoo_ans()
{
    $path = plugins_url('/yahoo-ans/');
    
    echo "<link rel='stylesheet' href='".$path."yahooans.css' type='text/css' media='all' />";
    //You can comment this code if jquery is included some where else.
    echo "<script type='text/javascript' src='wp-includes/js/jquery/jquery.js'></script>";
    echo "<script type='text/javascript' src='".$path."js/jquery.yahooans.js'></script>";
    $opt_title = get_option( "yahooans_title");
    $opt_search_query = get_option('yahooans_search');
    $opt_num_show = get_option('yahooans_num_show');
    echo "<img src='".$path."in.gif' />";
    echo $before_widget; 
    $yahoo_ans_xml = simplexml_load_file('http://answers.yahooapis.com/AnswersService/V1/questionSearch?appid=YVlwlAnV34EYDjMYKxtQ3zKudY.XG3Pl3GB.MScyFCuwI1kZ6vdLH0Y3tQVfBcOhzdhEoWVfgISiYg--&query='.$opt_search_query);
    $i=0;
    echo "<ul id='yahooans_ul_id'>";
    if(count($yahoo_ans_xml))
    {
	$i=0;
	
	foreach ($yahoo_ans_xml as $node)
	{
	    $Answers = $node->Subject;
	    $Answerslink = $node->Link;
	    $y_chosen_ans = $node->ChosenAnswer;
	    echo "<li ><label class='yahooans_li'>".$Answers."</label>&nbsp;<i class='js_see_ans yahooans_i' val=".$i.">See Answer</i><br><p class='js_chosen_ans' id='js_chosen_".$i."'>".$y_chosen_ans."<a href='".$Answerslink."' rel='nofollow' style='color:#000000'>&nbsp;Read more</a></p></li>";
	    if($i++ >= $opt_num_show) break;
	    
	}
	
    }
    else
    {
	echo "Enter different keywords and try again. Example Iphone,computer etc";
    }
    echo "</ul>";
    echo $after_widget;
}

function init_yahooans_widget() {
    register_sidebar_widget("Yahoo-ans", "yahoo_ans");
}

add_action("plugins_loaded", "init_yahooans_widget");
//Admin setting page

// Hook for adding admin menus
add_action('admin_menu', 'yahooans_add_pages');

// action function for above hook
function yahooans_add_pages() {
    add_options_page('Yahoo settings','Yahoo-ans', 'administrator', 'yahoo-ans', 'yahoo_ans_options_page');
}

function yahoo_ans_options_page()
{
    if( isset($_POST['Submit']))
    {
	// Read their posted value
	$opt_title = $_POST['yahooans_title'];
	$opt_search_query = $_POST['yahooans_search'];
	$opt_num_show = $_POST['yahooans_num_show'];
	// Save the posted value in the database
	update_option( "yahooans_title", $opt_title );
	update_option( "yahooans_search", $opt_search_query );
	update_option( "yahooans_num_show", $opt_num_show );
	// Put an options updated message on the screen
    }
    else
    {
	$opt_title = get_option( "yahooans_title");
	$opt_search_query = get_option('yahooans_search');
	$opt_num_show = get_option('yahooans_num_show');
    }

?>
<form name="form1" method="post" action="">
<p><?php _e("Yahoo Widget Title", 'mt_trans_domain'); ?> 
<input type="text" name="yahooans_title" value="<?php echo $opt_title; ?>" size="50">
</p><hr />

<p><?php _e("Search Query: ", 'mt_trans_domain' ); ?> 
<input type="text" name="yahooans_search" value="<?php echo $opt_search_query; ?>" size="50">
</p><hr />

<p><?php _e("Number of Answers items", 'mt_trans_domain' ); ?> 
<input type="text" name="yahooans_num_show" value="<?php echo $opt_num_show; ?>" size="3">
</p><hr />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'mt_trans_domain' ) ?>" />
</p><hr />
</form>
<h3>Preview</h3>

<?PHP yahoo_ans();?>

<?PHP



}


?>