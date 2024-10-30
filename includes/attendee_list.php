<?php
//List Attendees - used for the {EVENTATTENDEES} tag
function event_attendee_list_run() {
	global $wpdb;
	$events_detail_tbl = get_option('events_detail_tbl');
	$events_attendee_tbl = get_option('events_attendee_tbl');

	$sql = "SELECT * FROM ".$events_detail_tbl." WHERE is_active='yes'";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)) {
		$event_id = $row['id'];
		$event_name = $row['event_name'];
		$event_desc = $row['event_desc'];
		echo '<h2>Attendee listing for: <em>'.$event_name.'</em></h2>';
		echo $event_desc."<br /><br /><hr />";
	}

	$sql = "SELECT * FROM ".$events_attendee_tbl." WHERE event_id='$event_id'";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)) {
		$id = $row['id'];
		$lname = $row['lname'];
		$fname = $row['fname'];
		echo $fname.' '.$lname.'<br />';
	}
}
?>
