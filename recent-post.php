<?php
/*
Plugin Name: Recent Post Widget
Plugin URI: https://webcrews.net/
Description: Its a simple widget plugins
Version: 0.1.0
Author: Sahadat Hossain
Author URI: https://webcrews.net/
Text Domain: recent-post-widget
*/


/**
 * wc Recent Post Widget
 *
 */


class wc_recent_post_widget extends WP_Widget{

  //setup widget name, description etc
  public function __construct(){
    $widget_ops = array(
      'classname' => 'wc_widget',
      'description' => 'Recent Post Widget'
    );

    parent::__construct( 'wc_recent_post_widget', 'wc Recent Post Widget', $widget_ops);
  }

  //back-end display of widget
  public function form( $instance ){

    $title = ( !empty( $instance[ 'title' ]) ? $instance[ 'title' ] : null);
    $total_show_post = ( !empty( $instance[ 'total_show_post' ]) ? absint ($instance[ 'total_show_post' ]) : 4);

    $output = '<p>';
    $output .= '<label for="'.esc_attr($this->get_field_id( 'title' )).'">Title:</label>';
    $output .= '<input type="text" class="widefat" id="'.esc_attr($this->get_field_id( 'title' )).'" name="'.esc_attr($this->get_field_name( 'title' )).'" value="'.esc_attr( $title ).'"';
    $output .= '</p>';

    $output .= '<p>';
    $output .= '<label for="'.esc_attr($this->get_field_id( 'total_show_post' )).'">Number Of Posts:</label>';
    $output .= '<input type="number" class="widefat" id="'.esc_attr($this->get_field_id( 'total_show_post' )).'" name="'.esc_attr($this->get_field_name( 'total_show_post' )).'" value="'.esc_attr( $total_show_post ).'"';
    $output .= '</p>';

    echo $output;
  }


  //update widget
  public function update($new_instance, $old_instance){
    $instance = array();
    $instance['title'] = (!empty($new_instance['title']) ? strip_tags($new_instance['title']):'');
    $instance['total_show_post'] = (!empty($new_instance['total_show_post']) ? absint(strip_tags($new_instance['total_show_post'])):0);

    return $instance;
  }

  //front-end display of widget
  public function widget($args, $instance){
    $total_show_post = absint($instance['total_show_post']);

    $recent_posts = new WP_Query(array(
    	'post_type' => 'post',
    	'order_by' => 'title',
    	'order' => 'desc',
    	'posts_per_page' => $total_show_post,

    ));

	echo $args['before_widget'];
		echo '<div class="footer-content">';

		    if(!empty($instance['title'])):
		      echo $args['before_title'] . apply_filters( 'widget_title', $instance[ 'title' ] ) . $args['after_title'];
		    endif;
			?>
			<?php if($recent_posts->have_posts()) : while($recent_posts->have_posts() ) : $recent_posts->the_post(); ?>



			<div class="footer-post">
				<div class="footer-news">
					<ul class="list-inline">
					  <li>By : <a href="#"><?php the_author(); ?></a></li>
					  <li><?php the_time('F j, Y'); ?></li>
					</ul><!-- /.end of list-inline -->
					<a href="<?php the_permalink(); ?>"><h4><?php the_title(); ?></h4></a>
				</div><!-- /.end of footer-news -->
			</div><!-- /.end of footer-post -->

			<div class="clearfix"></div>
			<?php endwhile; ?>
				 <?php else: 
				 	esc_html_e('No Post Found', 'recent-post-widget');
			endif;?>

			<?php
		echo '<div>';
	echo $args['after_widget'];
  }


}
add_action('widgets_init', function(){
	register_widget( 'wc_recent_post_widget' );
});
