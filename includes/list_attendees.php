<?php
function event_list_attendees() { //Displays attendee information from current active event.
	global $wpdb;
	$events_detail_tbl = get_option('events_detail_tbl');
	$events_attendee_tbl = get_option('events_attendee_tbl');

	if($_REQUEST['event_id'] !="") {$view_event = $_REQUEST['event_id'];}
	if($_REQUEST['view_event'] !="") {$view_event = $_REQUEST['view_event'];}

	$sql = "SELECT * FROM ".$events_detail_tbl." WHERE id='$view_event'";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)) {
		$event_id = $row['id'];
		$event_name = $row['event_name'];
		$event_desc = $row['event_desc'];
		$event_description = $row['event_desc'];
		$event_identifier = $row['event_identifier'];
		$cost = $row['event_cost'];

		$active = $row['is_active'];
		$question1 = $row['question1'];
		$question2 = $row['question2'];
		$question3 = $row['question3'];
		$question4 = $row['question4'];
	}
	$sql = "SELECT * FROM ".$events_attendee_tbl." WHERE event_id='$view_event'";
	$result = mysql_query($sql);
	echo '<hr /><h3 class="title">Attendee list for <em>'.$event_name.'</em></h3>';
	?>
	<button class="button" onclick="window.location='<?php echo get_bloginfo('wpurl').'/wp-admin/admin.php?event_regis&amp;id='.$view_event.'&amp;export=report&amp;action=excel';?>'">Export current attendee list to XLS</button>
	<hr />
	<table class="attendeelist" cellspacing="0">
		<tr>
			<td><strong>Name</strong></td>
			<td><strong>Attendees</strong></td>
			<td><strong>Email</strong></td>
			<td><strong>City</strong></td>
			<td><strong>Phone</strong></td>
			<td><strong>Options</strong></td>
		</tr>
		<?php
		while($row = mysql_fetch_assoc($result)) {
			$id = $row['id'];
			$lname = $row['lname'];
			$fname = $row['fname'];
			$address = $row['address'];
			$city = $row['city'];
			$state = $row['state'];
			$zip = $row['zip'];
			$email = $row['email'];
			$phone = $row['phone'];
			$date = $row['date'];
			$payment_status = $row['payment_status'];
			$txn_type = $row['txn_type'];
			$txn_id = $row['txn_id'];
			$amount_pd = $row['amount_pd'];
			$quantity = $row['quantity'];
			$payment_date = $row['payment_date'];
			$event_id = $row['event_id'];
			$custom1 = $row['custom_1'];
			$custom2 = $row['custom_2'];
			$custom3 = $row['custom_3'];
			$custom4 = $row['custom_4'];
		?>
		<tr>
			<td><?php echo $lname;?>, <?php echo $fname;?> [<?php echo $payment_status;?>]</td>
			<td><?php echo $quantity;?></td>
			<td><?php echo $email;?></td>
			<td><?php echo $city;?></td>
			<td><?php echo $phone;?></td>
			<td>
				<form name="form" method="post" action="<?php echo $_SERVER["REQUEST_URI"];?>" style="display:inline">
					<input type="hidden" name="display_action" value="view_list" />
					<input type="hidden" name="view_event" value="<?php echo $view_event;?>" />
					<input type="hidden" name="form_action" value="edit_attendee" />
					<input type="hidden" name="id" value="<?php echo $id;?>" />
					<input type="submit" class="button" value="View/Edit" />
				</form>
				<form name="form" method="post" action="<?php echo $_SERVER["REQUEST_URI"];?>" style="display:inline">
					<input type="hidden" name="form_action" value="edit_attendee" />
					<input type="hidden" name="display_action" value="view_list" />
					<input type="hidden" name="attendee_action" value="delete_attendee" />
					<input type="hidden" name="view_event" value="<?php echo $view_event;?>" />
					<input type="hidden" name="id" value="<?php echo $id;?>" />
					<input type="submit" class="button" value="Delete" onclick="return confirm('Are you sure you want to delete <?php echo $fname;?> <?php echo $lname;?> - ID <?php echo $id;?>?')" />
				</form>
			</td>
		</tr>
	<?php }?>
	</table>
<?php }?>
