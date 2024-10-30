<?php //Event Registration Subpage 4 - View Attendees
function event_registration_reports() {
	global $wpdb;
	$events_detail_tbl = get_option('events_detail_tbl');
	$current_event = get_option('current_event');
	$events_attendee_tbl = get_option('events_attendee_tbl');

	$sql = "SELECT * FROM ".$events_detail_tbl;
	$result = mysql_query($sql);

	echo '<div id="configure_organization_form" class="wrap">';
	echo '<div id="icon-options-general" class="icon32"><br /></div>';
	echo '<h2>Events and attendees</h2>';

	echo '<h3 class="title">Select an event below to view attendees</h3>';
	echo '<span class="description">Select an event and scroll to the bottom of the page to see attendees. Greyed out events have no attendees.</span>';
	echo '<table class="eventlist" cellspacing="0">';
	echo '<tr><td><strong>Date</strong></td><td><strong>Description</strong></td><td><strong>Attendees</strong></td><td><strong>Options</strong></td></tr>';
	while($row = mysql_fetch_assoc($result)) {

		$event_id = $row['id'];
		$event_name=$row['event_name'];
		$event_date=$row['start_date'];
		$oDate = strtotime($row['start_date']);
		$sDate = date("F jS, Y",$oDate);

		$sqlquery1 = "SELECT * FROM ".$events_attendee_tbl." WHERE event_id = $event_id";
		$resultquery1 = mysql_query($sqlquery1);
		$howmany = mysql_num_rows($resultquery1);

		if($howmany == 0) {$blurstyle = ' style="color:#999999"';}
		if($howmany != 0) {$blurstyle = '';};
		echo '<tr>';
		echo '<form name="form" method="post" action="'.$_SERVER['REQUEST_URI'].'">';
		echo '<input type="hidden" name="display_action" value="view_list" />';
		echo '<input type="hidden" name="event_id" value="'.$row['id'].'" />';
		echo '<td'.$blurstyle.'>'.$sDate.'</td>';
		echo '<td'.$blurstyle.'>'.$event_name.'</td>';
		echo '<td'.$blurstyle.'>'.$howmany.'</td>';
		echo '<td><input class="button" type="submit" value="Click to view" /></form></td>';
		echo '<tr>';
	}
	echo '</table>';
	if($_REQUEST['display_action'] == 'view_list') {
		attendee_display_edit();
	}
}
?>
