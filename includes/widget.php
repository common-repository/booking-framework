<?php
add_action('widgets_init', 'load_booking_framework_widget');

function load_booking_framework_widget() {
	register_widget('Booking_Framework_Widget');
}

class Booking_Framework_Widget extends WP_Widget {
	function Booking_Framework_Widget() {
		/* Widget settings. */
		$widget_options = array('classname' => 'events', 'description' => __('A widget to display your upcoming events.', 'events'));

		/* Widget control settings. */
		$control_options = array('width' => 300, 'height' => 350, 'id_base' => 'events-widget');

		/* Create the widget. */
		$this->WP_Widget('events-widget', __('Booking Framework Widget', 'events'), $widget_options, $control_options);
	}

	function widget($args, $instance) {
		extract($args);
		global $wpdb;
		
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title']);

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ($title)
			echo $before_title . $title . $after_title;
		$events_organization_tbl = get_option('events_organization_tbl');
		$events_detail_tbl = get_option('events_detail_tbl');
		$curdate = date("Y-m-d");
		$paypal_cur = get_option('paypal_cur');

		$sql = "SELECT * FROM ".$events_organization_tbl." WHERE id='1'";
		$result = mysql_query($sql);
		while($row = mysql_fetch_assoc($result)) {
			$event_page_id = $row['event_page_id'];
		}
		$sql = "SELECT * FROM ".$events_detail_tbl." WHERE is_active='yes' AND start_date >= '".date ( 'Y-m-j' )."' ORDER BY date(start_date)";
		$result = mysql_query($sql);

		echo '<div id="widget_display_all_events"><ul class="event_items">';
		while($row = mysql_fetch_assoc($result)) {
			$event_id = $row['id'];
			$event_name=$row['event_name'];
			$start_month=$row['start_month'];
			$start_day=$row['start_day'];
			$start_year=$row['start_year'];
			echo '<li><a href="'.get_option('siteurl').'/?page_id='.$event_page_id.'&amp;regevent_action=register&amp;event_id='.$event_id.'">'.$event_name.' - '.$start_month.' '.$start_day.', '.$start_year.'</a></li>';
		}
		echo '</ul></div>';

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/* Update the widget settings. */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form($instance) {
		/* Set up some default widget settings. */
		$defaults = array('title' => __('Upcoming Events', 'events'));
		$instance = wp_parse_args((array) $instance, $defaults);?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id('title');?>"><?php _e('Title:', 'Upcoming Events');?></label>
			<input id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" value="<?php echo $instance['title'];?>" style="width:100%;" />
		</p>
<?php
	}
}
?>
