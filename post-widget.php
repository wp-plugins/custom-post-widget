<?php

// First create the widget for the admin panel
class custom_post_widget extends WP_Widget
{
  function custom_post_widget()
  {
    $widget_ops = array('description' => __('Displays custom post content in a widget', CUSTOM_POST_WIDGET_TEXTDOMAIN));
    $this->WP_Widget('custom_post_widget', __('Content Block', CUSTOM_POST_WIDGET_TEXTDOMAIN), $widget_ops);
  }

  function form($instance)
  {
    $custom_post_id = esc_attr($instance['custom_post_id']);
    $show_custom_post_title  = isset($instance['show_custom_post_title ']) ? $instance['show_custom_post_title '] : true;
    
    ?>
      <p>
        <label for="<?php echo $this->get_field_id('custom_post_id'); ?>"> <?php echo __('Content Block to Display:', CUSTOM_POST_WIDGET_TEXTDOMAIN) ?>
          <select class="widefat" id="<?php echo $this->get_field_id('custom_post_id'); ?>" name="<?php echo $this->get_field_name('custom_post_id'); ?>">
            <?php query_posts('post_type=content_block&orderby=ID&order=ASC&showposts=-1');
              if ( have_posts() ) : while ( have_posts() ) : the_post();
                $currentID = get_the_ID();
                if($currentID == $custom_post_id)
                  $extra = 'selected' and
                  $widgetExtraTitle = get_the_title();
                else
                  $extra = '';
                echo '<option value="'.$currentID.'" '.$extra.'>'.get_the_title().'</option>';
                endwhile; else:
                echo '<option value="empty">' . __('No content blocks available', CUSTOM_POST_WIDGET_TEXTDOMAIN) . '</option>';
              endif;
            ?>
          </select>
        </label>
      </p>
   <?php ?>
     <input type="hidden" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $widgetExtraTitle; ?>" />
     <?php wp_reset_query(); ?>
      <p>
        <input class="checkbox" type="checkbox" <?php checked( (bool) $instance['show_custom_post_title'], true ); ?> id="<?php echo $this->get_field_id( 'show_custom_post_title' ); ?>" name="<?php echo $this->get_field_name( 'show_custom_post_title' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'show_custom_post_title' ); ?>"><?php echo __('Show Post Title', CUSTOM_POST_WIDGET_TEXTDOMAIN) ?></label>
      </p>

      <?php
  }

  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['custom_post_id'] = strip_tags($new_instance['custom_post_id']);
    $instance['show_custom_post_title'] = $new_instance['show_custom_post_title'];

    return $instance;
  }

  function widget($args, $instance)
  {
    extract($args);

    $custom_post_id  = ( $instance['custom_post_id'] != '' ) ? esc_attr($instance['custom_post_id']) : __('Find', CUSTOM_POST_WIDGET_TEXTDOMAIN);

    /* Variables from the widget settings. */
    $show_custom_post_title = isset( $instance['show_custom_post_title'] ) ? $instance['show_custom_post_title'] : false;

    $content_post = get_post($custom_post_id);
    $content = $content_post->post_content;
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]>', $content);

    echo $before_widget;
	
    if ( $show_custom_post_title )
	{
	  echo $before_title;
      echo $content_post->post_title; // This is the line that displays the title (only if show title is set)
	  echo $after_title;
	}
	
    echo $content; // This is where the actual content of the custom post is being displayed
	
    echo $after_widget;

  }
}

// Create the Content Block custom post type
add_action('init', 'my_content_block_post_type_init');

  function my_content_block_post_type_init()
  {
    $labels = array(
      'name' => _x('Content Blocks', 'post type general name', CUSTOM_POST_WIDGET_TEXTDOMAIN),
      'singular_name' => _x('Content Block', 'post type singular name', CUSTOM_POST_WIDGET_TEXTDOMAIN),
      'plural_name' => _x('Content Blocks', 'post type plural name', CUSTOM_POST_WIDGET_TEXTDOMAIN),
      'add_new' => _x('Add Content Block', 'block', CUSTOM_POST_WIDGET_TEXTDOMAIN),
      'add_new_item' => __('Add New Content Block', CUSTOM_POST_WIDGET_TEXTDOMAIN),
      'edit_item' => __('Edit Content Block', CUSTOM_POST_WIDGET_TEXTDOMAIN),
      'new_item' => __('New Content Block', CUSTOM_POST_WIDGET_TEXTDOMAIN),
      'view_item' => __('View Content Block', CUSTOM_POST_WIDGET_TEXTDOMAIN),
      'search_items' => __('Search Content Blocks', CUSTOM_POST_WIDGET_TEXTDOMAIN),
      'not_found' =>  __('No Content Blocks Found', CUSTOM_POST_WIDGET_TEXTDOMAIN),
      'not_found_in_trash' => __('No Content Blocks found in Trash', CUSTOM_POST_WIDGET_TEXTDOMAIN),
      'parent_item_colon' => ''
    );
    $options = array(
      'labels' => $labels,
      'public' => false,
      'publicly_queryable' => false,
      'exclude_from_search' => true,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => true,
      'capability_type' => 'post',
      'hierarchical' => false,
      'menu_position' => null,
      'supports' => array('title','editor','revisions','thumbnail','author')
    );
    register_post_type('content_block',$options);
  }
 
 
// Add custom styles to admin screen and menu
add_action('admin_head', 'content_block_header');

  function content_block_header() {
    
    global $post_type; ?>
    
    <style type="text/css"><!--
    <?php if (($post_type == 'content_block')) : ?>
      #icon-edit { background:transparent url('<?php echo CUSTOM_POST_WIDGET_URL; ?>images/contentblock-32.png') no-repeat 0 0 !important; }
    <?php endif; ?>
      #adminmenu #menu-posts-contentblock div.wp-menu-image{background:transparent url('<?php echo CUSTOM_POST_WIDGET_URL;?>images/contentblock.png') no-repeat center -32px;}
      #adminmenu #menu-posts-contentblock:hover div.wp-menu-image,#adminmenu #menu-posts-contentblock.wp-has-current-submenu div.wp-menu-image{background:transparent url('<?php echo CUSTOM_POST_WIDGET_URL;?>images/contentblock.png') no-repeat center 0px;}
    --></style><?php
    
  }

add_filter('post_updated_messages', 'content_block_messages');
  
  function content_block_messages( $messages ) {
   
    $messages['content_block'] = array(
    0 => '', 
    1 => sprintf( __('Content Block updated. <a href="%s">View Content Block</a>', CUSTOM_POST_WIDGET_TEXTDOMAIN), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.', CUSTOM_POST_WIDGET_TEXTDOMAIN),
    3 => __('Custom field deleted.', CUSTOM_POST_WIDGET_TEXTDOMAIN),
    4 => __('Content Block updated.', CUSTOM_POST_WIDGET_TEXTDOMAIN),
    5 => isset($_GET['revision']) ? sprintf( __('Content Block restored to revision from %s', CUSTOM_POST_WIDGET_TEXTDOMAIN), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Content Block published. <a href="%s">View Content Block</a>', CUSTOM_POST_WIDGET_TEXTDOMAIN), esc_url( get_permalink($post_ID) ) ),
    7 => __('Block saved.', CUSTOM_POST_WIDGET_TEXTDOMAIN),
    8 => sprintf( __('Content Block submitted. <a target="_blank" href="%s">Preview Content Block</a>', CUSTOM_POST_WIDGET_TEXTDOMAIN), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Content Block scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview block</a>', CUSTOM_POST_WIDGET_TEXTDOMAIN),
      date_i18n( __( 'M j, Y @ G:i' , CUSTOM_POST_WIDGET_TEXTDOMAIN), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Content Block draft updated. <a target="_blank" href="%s">Preview Content Block</a>', CUSTOM_POST_WIDGET_TEXTDOMAIN), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    );
   
    return $messages;
  }

?>