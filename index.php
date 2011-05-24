<?php
/*
Plugin Name: Custom Page Menus
Description: Adds an option for defining custom menus on a per page basis.
Author: <a href="http://www.fubra.com">Ray Viljoen</a>
Version: 1.0
Plugin URI: http://catn.com/community/plugins/
Usage: widget or <?php cpMenu() ?>
*/
// © 2009-2011 Fubra Limited, all rights reserved. 

if(isset($_POST['add'])){ add_cpMenu(); }
if(isset($_POST['remove'])){ remove_cpMenu(); }
if(isset($_POST['custom-page-menu-title'])){ add_cpMenu_title(); }

add_action('submitpage_box', 'create_custom_page_menu');

//Include template tag code
include 'template-tag.php';

// Include widget class
include 'widget-class.php';

// PLUGIN FUNCTIONS
function create_custom_page_menu(){
	global $post;
	
	isset($_GET['post']) ? $page_ID = $_GET['post'] : $page_ID = '';
	
	if (get_post_custom_values('custom-pages', $page_ID))
	{
		$exclude_pages = get_post_custom_values('custom-pages', $page_ID);
		$exclude_pages = $exclude_pages[0];
	}
	else{ $exclude_pages = ''; }
	$pages = get_pages('hierarchical=1&post_status=publish&exclude='.$exclude_pages);
	STATIC $box_id_add = 1;
	STATIC $box_id_remove = 1;
?>
<!-- Custom Title For Menu -->
<div id="custom-pages-div" class="postbox " style="display: block;">
<h3  style="cursor:default;"class="hndle"><span>Custom Pages - Alt Page Title</span></h3>
<div class="inside">
<form style="display:none"></form> <!-- Ommitted by WP ----- DO NOT REMOVE !! -->

		<form action="#" method="post" name="remove">
		<?php
		if (get_post_custom_values('custom-page-menu-title', $page_ID))
		{	
			$title = get_post_custom_values('custom-page-menu-title', $page_ID);
			
			echo '<input type="text" class="howto" style="position:relative; width:100%;" name="custom-page-menu-title" value="'.$title[0].'" />';		
				
		}else{
			
			echo '<input type="text" class="howto" style="position:relative; width:100%;" name="custom-page-menu-title" value="'.$post->post_title.'" />';
		}
		?>
		<input name="save" type="submit" value="Save"style=" margin-top:7px; padding:2px 10px;">
		</form>
</div>

<h3  style="cursor:default;"class="hndle"><span>Custom Pages - Menu</span></h3>
<div class="inside">
<form style="display:none"></form> <!-- Ommitted by WP ----- DO NOT REMOVE !! -->

<!-- REMOVE FORM -->
		<form action="#" method="post" name="remove">
		<div style="max-height: 150px; overflow: auto; border:1px solid #DFDFDF; padding:3px;" >
		<?php
		if (get_post_custom_values('custom-pages', $page_ID))
		{	
			$related = get_post_custom_values('custom-pages', $page_ID);
			$related = explode(',', $related[0]);
			array_pop($related);
			foreach ($related as $id){
			$cpMenu[] = get_page($id);
			}
			echo '<input type="hidden" name="remove" />';
			foreach ($cpMenu as $page)
			{	
				echo '<label for="remove'.$box_id_remove.'" >';
				echo '<input id="remove'.$box_id_remove.'" type="checkbox" name="'.$box_id_remove.'" value="'.$page->ID.'" > ';
				echo $page->post_title;
				echo '</label><br/>';
				$box_id_remove++;
			}			
		}else{ echo '<p class="howto" style="margin:0; padding:0;">No Pages</p>';}
		?>
		</div>
		<input type="submit" value="Remove From Menu" style="margin:10px 0 10px 0;" >
		</form>
<!-- ADD FORM -->
		<form action="#" method="post" name="add" >
		<div style="max-height: 150px; overflow: auto; border:1px solid #DFDFDF; padding:3px;" >
<?php
		if ($pages){
			echo '<input type="hidden" name="add" />';
			foreach ($pages as $page)
			{
				echo '<label for="add'.$box_id_add.'" >';
				echo '<input id="add'.$box_id_add.'" type="checkbox" name="'.$box_id_add.'" value="'.$page->ID.'" /> ';
				if ($page->post_parent !== 0){ echo ' - '.$page->post_title; }
				else {echo '<span style="font-weight:bold;">'.$page->post_title.'</span>';}
				
				echo '</label><br/>';
				$box_id_add++;
			}
		}else{ echo '<p class="howto" style="margin:0; padding:0;">No Pages</p>';}
?>
		</div>
		<input type="submit" value="Add To Menu" style="margin:10px 0 5px 0;" >
		</form>
</div>
</div>
<?php
} 

function add_cpMenu()
{	
	$page_ID = $_GET['post'];
	$id_string = $_POST;
	unset($id_string['add']);
	$new_IDs = '';
	foreach ($id_string as $id) {
		$new_IDs .= $id.',' ;
	}
	if (!get_post_custom_values('custom-pages', $page_ID))
	{
		add_post_meta($page_ID, 'custom-pages', $new_IDs, TRUE);	
	}else{
		$existing_IDs = get_post_custom_values('custom-pages', $page_ID);
		$existing_id_string = $existing_IDs[0];
		$new_id_string = $existing_id_string.$new_IDs;
		update_post_meta($page_ID, 'custom-pages', $new_id_string);		
	}
}
function remove_cpMenu()
{
	$page_ID = $_GET['post'];
	$id_string = $_POST;
	unset($id_string['remove']);
	$existing_IDs = get_post_custom_values('custom-pages', $page_ID);
	$existing_id_string = $existing_IDs[0];
	$existing_id_array = explode(',',$existing_id_string);
	array_pop($existing_id_array);
	foreach ($existing_id_array as $key=>$val)
	{
		if (in_array($val, $id_string)){
		unset($existing_id_array[$key]);
		}
	}
	$new_id_string = implode(',', $existing_id_array);
	if($new_id_string)
	{
		$new_id_string .= ',';
		update_post_meta($page_ID, 'custom-pages', $new_id_string);
	}else{
		delete_post_meta($page_ID, 'custom-pages');
	}
}
function add_cpMenu_title()
{	
	$page_ID = $_GET['post'];
	$current_title = get_the_title($page_ID);
	
	$new_title = $_POST['custom-page-menu-title'];
	
	if ($new_title !== $current_title){
		if(get_post_custom_values('custom-page-menu-title', $page_ID)){
			update_post_meta($page_ID, 'custom-page-menu-title', $new_title);
		}else{
			add_post_meta($page_ID, 'custom-page-menu-title', $new_title, TRUE);	
		}
	} 
}