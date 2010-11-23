<?php
/*
 Plugin Name: Custom Post Widget
 Plugin URI: http://www.vanderwijk.com/services/web-design/wordpress-custom-post-widget/
 Description: Show the content of a custom post of the type 'content_block' in a widget.
 Version: 1.0
 Author: Johan van der Wijk
 Author URI: http://www.vanderwijk.com

 Release notes: 1.0 First version

*/

// First create the widget for the admin panel
class custom_post_widget extends WP_Widget
{
  function custom_post_widget()
  {
    $widget_ops = array('description' => __('Displays custom post content in a widget'));
    $this->WP_Widget('custom_post_widget', __('Content Block'), $widget_ops);
  }

  function form($instance)
  {
    $custom_post_id = esc_attr($instance['custom_post_id']);
    
    ?>
      <p>
        <label for="<?php echo $this->get_field_id('custom_post_id'); ?>"> <?php echo __('Content Block to Display:') ?>
          <select id="<?php echo $this->get_field_id('custom_post_id'); ?>" name="<?php echo $this->get_field_name('custom_post_id'); ?>">
            <?php query_posts('post_type=content_block&orderby=ID&order=ASC&showposts=-1');
              if ( have_posts() ) : while ( have_posts() ) : the_post();
                $currentID = get_the_ID();
                if($currentID == $custom_post_id)
                  $extra = 'selected';
                else
                  $extra = '';
                echo '<option value="'.$currentID.'" '.$extra.'>'.get_the_title().'</option>';
                endwhile; else:
                echo '<option value="empty">No content blocks available</option>';
              endif;
            wp_reset_query(); ?>
          </select>
        </label>
      </p><?php
  }

  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['custom_post_id'] = strip_tags($new_instance['custom_post_id']);
    return $instance;
  }

  function widget($args, $instance)
  {
    extract($args);
    
    $custom_post_id  = ( $instance['custom_post_id'] != '' ) ? esc_attr($instance['custom_post_id']) : 'Zoeken';
    
    // Output title & $before_widget
        echo $title . $before_widget;
    
    // Output the query to find the custom post
    query_posts( 'post_type=content_block&p=' . $custom_post_id );
      while (have_posts()) : the_post();
        echo the_content();
        endwhile;
    wp_reset_query();

    // Output $after_widget
    echo $after_widget;
  }
}
add_action('widgets_init', create_function('', 'return register_widget("custom_post_widget");'));

// Create the Content Block custom post type

add_action('init', 'my_content_block_post_type_init');

  function my_content_block_post_type_init()
  {
    $labels = array(
      'name' => _x('Content Blocks', 'post type general name'),
      'singular_name' => _x('Content Block', 'post type singular name'),
      'add_new' => _x('Add Content Block', 'block'),
      'add_new_item' => __('Add New Content Block'),
      'edit_item' => __('Edit Content Block'),
      'new_item' => __('New Content Block'),
      'view_item' => __('View Content Block'),
      'search_items' => __('Search Content Blocks'),
      'not_found' =>  __('No Content Blocks Found'),
      'not_found_in_trash' => __('No Content Blocks found in Trash'),
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
      'supports' => array('title','editor','revisions','thumbnail')
    );
    register_post_type('content_block',$options);
  }

add_filter('post_updated_messages', 'content_block_messages');
  
  function content_block_messages( $messages ) {
   
    $messages['content_block'] = array(
    0 => '', 
    1 => sprintf( __('Content Block updated. <a href="%s">View Content Block</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Content Block updated.'),
    5 => isset($_GET['revision']) ? sprintf( __('Content Block restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Content Block published. <a href="%s">View Content Block</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Block saved.'),
    8 => sprintf( __('Content Block submitted. <a target="_blank" href="%s">Preview Content Block</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Content Block scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview block</a>'),
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Content Block draft updated. <a target="_blank" href="%s">Preview Content Block</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    );
   
    return $messages;
  }

?>