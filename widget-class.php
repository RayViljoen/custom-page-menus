<?php
//Custom Page Menus WIDGET
class CustomPagesWidget extends WP_Widget {
    /** constructor */
    function CustomPagesWidget() {
    	
    	global $post;
        parent::WP_Widget(false, $name = 'Custom Page Menus');        
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        $sortby = empty( $instance['sortby'] ) ? 'menu_order' : $instance['sortby'];
		$thumb = $instance['thumb'];

        $include_IDs = get_post_custom_values('custom-pages');
        if (isset($include_IDs[0])){
        $include_IDs = $include_IDs[0];
        }else{ $include_IDs = 'None'; }

        $cpMenu = get_pages( 'sort_column='.$sortby.'&include='.$include_IDs );

        if ($cpMenu):
        		echo $before_widget;
        		echo $before_title. $title .$after_title;
             	echo '<ul>';
	             	foreach ($cpMenu as $page) 
	             	{	
	             		echo '<li class="custom-pages-menu-item">';
	             		if ($featured_image && $thumb){
	             			echo '<a href="';
		             		echo get_permalink($page->ID).'" class="custom-pages-menu-thumb" title="';
		             		echo $page->post_name.'">';
	             			echo get_the_post_thumbnail($page->ID);
	             			echo '</a>';
	             		}
	             		echo '<a href="';
	             		echo get_permalink($page->ID).'" title="';
	             		echo $page->post_name.'">';
	             		
	             		if (get_post_custom_values('custom-page-menu-title', $page->ID))
	             		{	
	             			$set_title = get_post_custom_values('custom-page-menu-title', $page->ID);
	             			echo '<span>'.$set_title[0].'</span>';
	             		}else{
	             			echo '<span>'.$page->post_title.'</span>';
	             		}
						echo '</a>';
	             		echo '</li>';
	             	}
             	echo '</ul>';
             	echo  $after_widget;
	      endif;
    }
    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {			
	$instance = $old_instance;
	
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['thumb'] = strip_tags($new_instance['thumb']);
	
   if ( in_array( $new_instance['sortby'], array( 'post_title', 'menu_order', 'ID' ) ) ) {
		$instance['sortby'] = $new_instance['sortby'];
		} else {
		$instance['sortby'] = 'menu_order';
		}
															
        return $instance;
    }
    /** @see WP_Widget::form */
    function form($instance) {
    	if(array_key_exists('title', $instance)){
    	   $title = esc_attr($instance['title']); }else{$title = '';}
    	   
    	   if(array_key_exists('sortby', $instance)){
    	   	$sortby = $instance['sortby'];
    	   }else{$sortby = '';}
    	   
    	   	if(array_key_exists('thumb', $instance)){
			$thumb = esc_attr($instance['thumb']);
			}else{$thumb = 0;}
        ?>
            <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
   			<p><input id="<?php echo $this->get_field_id('thumb'); ?>" name="<?php echo $this->get_field_name('thumb'); ?>" <?php if($thumb){ echo 'checked="checked"'; } ?> type="checkbox" value= "1" ><label for="<?php echo $this->get_field_id('thumb'); ?>"> Include Featured Images</label></p>
            <p>
			<label for="<?php echo $this->get_field_id('sortby'); ?>"><?php _e( 'Sort by:' ); ?></label>
			<select name="<?php echo $this->get_field_name('sortby'); ?>" id="<?php echo $this->get_field_id('sortby'); ?>" class="widefat">
				<option value="post_title"<?php selected( $sortby, 'post_title' ); ?>><?php _e('Page Title'); ?></option>
				<option value="menu_order"<?php selected( $sortby, 'menu_order' ); ?>><?php _e('Page Order'); ?></option>
				<option value="post_date"<?php selected( $sortby, 'post_date' ); ?>><?php _e('Page Date'); ?></option>
				<option value="ID"<?php selected( $sortby, 'ID' ); ?>><?php _e( 'Page ID' ); ?></option>
			</select>
		</p>
        <?php 
    }
}
add_action('widgets_init', create_function('', 'return register_widget("CustomPagesWidget");'));
//custom Pages WIDGET -- END