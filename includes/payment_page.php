<?php
// Payment Page/PayPal Buttons - Used to display the payment options and the payment link in the email. Used with the {EVENTREGPAY} tag.

// This is the initial PayPal button
function events_payment_page($event_id){
	// Setup class
	$p = new paypal_class; // initiate an instance of the class
	global $wpdb;
	$events_organization_tbl = get_option('events_organization_tbl');
	$events_detail_tbl = get_option('events_detail_tbl');
	$paypal_cur = get_option('paypal_cur');

	// query event database for organization information
	$sql = "SELECT * FROM ".$events_organization_tbl." WHERE id='1'";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)) {
		$org_id = $row['id'];
		$Organization = $row['organization'];
		$Organization_street1 = $row['organization_street1'];
		$Organization_street2 = $row['organization_street2'];
		$Organization_city = $row['organization_city'];
		$Organization_state = $row['organization_state'];
		$Organization_zip = $row['organization_zip'];
		$contact = $row['contact_email'];
		$registrar = $row['contact_email'];
		$paypal_id = $row['paypal_id'];
		$paypal_cur = $row['currency_format'];
		$events_listing_type = $row['events_listing_type'];
		$message = $row['message'];
		$return_url = $row['return_url'];
		$cancel_return = $row['cancel_return'];
		$notify_url = $row['notify_url'];
		$image_url = $row['image_url'];
		$use_sandbox = $row['use_sandbox'];
	}
	if($use_sandbox == 1) {
		$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; // testing paypal url
		echo "<h3 style=\"color:#ff0000;\" title=\"Payments will not be processed\">Debug Mode Is Turned On</h3>";
	}
	else {
		$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr'; // paypal url
	}

	// Query Database for Active event and get variable
	$sql = "SELECT * FROM ".$events_detail_tbl." WHERE id ='$event_id'";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)) {
		$event_id = $row['id'];
		$event_name = $row['event_name'];
		$event_desc = $row['event_desc'];
		$event_description = $row['event_desc'];
		$event_identifier = $row['event_identifier'];
		$event_cost = $row['event_cost'];
		$send_mail = $row['send_mail'];
		$active = $row['is_active'];
		$question1 = $row['question1'];
		$question2 = $row['question2'];
		$question3 = $row['question3'];
		$question4 = $row['question4'];
		$conf_mail = $row['conf_mail'];
		$use_coupon_code = $row['use_coupon_code'];	
		$coupon_code = $row['coupon_code'];						
		$coupon_code_price = $row['coupon_code_price'];
		$allow_multiple = $row['allow_multiple']; //CIPRIAN
	}

	$attendee_id = get_option('attendee_id');
	$attendee_name = get_option('attendee_name');
	$attendee_first = get_option('attendee_first');
	$attendee_last = get_option('attendee_last');
	$attendee_email = get_option('attendee_email');
	$attendee_address = get_option('attendee_address');
	$attendee_city = get_option('attendee_city');
	$attendee_state = get_option('attendee_state');
	$attendee_zip = get_option('attendee_zip');
	$num_people = get_option('num_people');

	// CIPRIAN
	if($num_people == 1) {
		$total_cost = $event_cost;
	}
	if(($num_people == 2) && ($allow_multiple == 'Y')) {
		$total_cost = ($event_cost * $num_people) - 50;
	}
	if(($num_people == 2) && ($allow_multiple == 'N')) {
		$total_cost = ($event_cost * $num_people) - 70; // 120x2 = 240; 240-170 = 70
	}
	if($num_people >= 3) {
		$total_cost = (($event_cost-30) * $num_people);
	}
	if($use_coupon_code == "Y") {
		if($coupon_code == $_POST['coupon_code']) {
//			$event_cost = $event_cost - $coupon_code_price;
			$total_cost = $total_cost - ($coupon_code_price * $num_people);
		}
	}

	if($event_cost != "") {
		if($paypal_id != "" || $paypal_id != " ") {
			$p->add_field('business', $paypal_id);
			$p->add_field('return', get_option('siteurl').'/?page_id='.$return_url);
			$p->add_field('cancel_return', get_option('siteurl').'/?page_id='.$cancel_return);
			$p->add_field('notify_url', get_option('siteurl').'/?page_id='.$notify_url.'&id='.$attendee_id.'&event_id='.$event_id.'&attendee_action=post_payment&form_action=payment');
			$p->add_field('item_name', $event_name . ' | Reg. ID: '.$attendee_id. ' | Name: '. $attendee_name .' | Total Registrants: '.$num_people);
			$p->add_field('amount', $total_cost);
			//$p->add_field('quantity', $num_people);

			//Post variables
			$p->add_field('first_name', $attendee_first);
			$p->add_field('last_name', $attendee_last);
			$p->add_field('email', $attendee_email);
			$p->add_field('address1', $attendee_address);
			$p->add_field('city', $attendee_city);
			$p->add_field('state', $attendee_state);
			$p->add_field('zip', $attendee_zip);				 
			?>				  
			<p align="left"><strong>Please verify your registration details:</strong></p>
			<table width="95%" border="0" id="event_regis_attendee_verify" style="margin:0 10px 10px">
				<tr>
					<td><strong>Event name:</strong></td>
					<td><?php echo $event_name;?></td>
				</tr>
				<tr>
					<td><strong>Attendee name:</strong></td>
					<td><?php echo $attendee_name;?></td>
				</tr>
				<tr>
					<td><strong>Email address:</strong></td>
					<td><?php echo $attendee_email;?></td>
				</tr>
				<tr>
					<td><strong>Number of attendees:</strong></td>
					<td><?php echo $num_people;?></td>
				</tr>
				<tr>
					<td><strong>Payment total:</strong></td>
					<td><?php echo get_option('currency_symbol');?><?php echo $total_cost;?></td>
				</tr>
			</table>
			<?php
			$p->submit_paypal_post(); // submit the fields to paypal
			if($use_sandbox == true) {
				$p->dump_fields(); // for debugging, output a table of all the fields
			}
		}
	}
}

//This is the alternate PayPal button used for the email 
function event_regis_pay() {
	global $wpdb;
	$events_attendee_tbl = get_option('events_attendee_tbl');
	$events_detail_tbl = get_option('events_detail_tbl');
    $events_organization_tbl = get_option('events_organization_tbl');
	$paypal_cur = get_option('paypal_cur');
	$id = "";
	$id = $_GET['id'];
	if($id == "") {
		echo "Please check your email for payment information.";
	}
	else {
		$query = "SELECT * FROM $events_attendee_tbl WHERE id='$id'";
	   	$result = mysql_query($query) or die('Error : ' . mysql_error());
		while($row = mysql_fetch_assoc($result)) {
			$attendee_id = $row['id'];
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
			$amount_pd = $row['amount_pd'];
			$payment_date = $row['payment_date'];
			$event_id = $row['event_id'];
			$custom1 = $row['custom_1'];
			$custom2 = $row['custom_2'];
			$custom3 = $row['custom_3'];
			$custom4 = $row['custom_4'];
			$attendee_name = $fname." ".$lname;
		}

		$sql = "SELECT * FROM ". $events_organization_tbl . " WHERE id='1'";
		$result = mysql_query($sql);
		while($row = mysql_fetch_assoc($result)) {
			$org_id = $row['id'];
			$Organization = $row['organization'];
			$Organization_street1 = $row['organization_street1'];
			$Organization_street2 = $row['organization_street2'];
			$Organization_city = $row['organization_city'];
			$Organization_state = $row['organization_state'];
			$Organization_zip = $row['organization_zip'];
			$contact = $row['contact_email'];
 			$registrar = $row['contact_email'];
			$paypal_id = $row['paypal_id'];
			$paypal_cur = $row['currency_format'];
			$events_listing_type = $row['events_listing_type'];
			$message = $row['message'];
			$return_url = $row['return_url'];
			$cancel_return = $row['cancel_return'];
			$notify_url = $row['notify_url'];
			//$return_method = $row['return_method'];
			$use_sandbox = $row['use_sandbox'];
			$image_url = $row['image_url'];
			$paypal_cur = get_option('currency_symbol');
		}

		//Query Database for event and get variable
		$sql = "SELECT * FROM ".$events_detail_tbl." WHERE id='$event_id'";
		$result = mysql_query($sql);
		while($row = mysql_fetch_assoc($result)) {
			//$event_id = $row['id'];
			$event_name = $row['event_name'];
			$event_desc = $row['event_desc'];
			$event_description = $row['event_desc'];
			$event_identifier = $row['event_identifier'];
			$event_cost = $row['event_cost'];
			$active = $row['is_active'];
			$question1 = $row['question1'];
			$question2 = $row['question2'];
			$question3 = $row['question3'];
			$question4 = $row['question4'];
		}

		echo "<br><br><strong>Thank You ".$fname." ".$lname." for registering for ".$event_name."</strong><br><br>";

		if($amount_pd !="") {
			echo "<br><strong><u><i><font color='red' size='3'>Our records indicate you have paid ".$paypal_cur.$amount_pd."</font></u></i></strong><br><br>";
		}
		if($event_cost != "") {
			if($paypal_id !="") {
				//Payment Selection with data hidden - forwards to paypal
				?>
				<p align="left"><strong>Payment By Credit Card, Debit Card or Pay Pal Account<br>(a PayPal account is not required to pay by credit card).</strong></p>
				<p>Payment will be in the amount of <?php echo $paypal_cur.$event_cost;?>.</p>
				<p>PayPal Payments will be sent to: <?php echo $Organization?> (<?php echo $paypal_id?>)</p>
				<table width="500">
					<tr>
						<td align="center" valign="middle">
							<strong><?php echo $event_name." - ".$paypal_cur.$event_cost;?></strong>
							<?php
							if($use_sandbox == 1) {
								echo "<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post'>";
							}
							else {
								echo "<form action='https://www.paypal.com/cgi-bin/webscr' method='post'>";
							}
							?>
							Additional attendees?
							<select name="quantity" style="width:70px;margin-top:4px">
								<option value="1" selected="selected">None</option>
								<option value="2">1</option>
								<option value="3">2</option>
								<option value="4">3</option>
								<option value="5">4</option>
								<option value="6">5</option>
							</select> x 
							<?php echo $paypal_cur." ".$event_cost; ?>
							<?php if ($paypal_cur == "$" || $paypal_cur == "") {
								$paypal_cur ="EUR";
							}?>
							<br />
							<br />
							<input type="hidden" name="bn" value="AMPPFPWZ.301" />
							<input type="hidden" name="cmd" value="_ext-enter" />
							<input type="hidden" name="redirect_cmd" value="_xclick" />
							<input type="hidden" name="business" value="<?php echo $paypal_id;?>" />
							<input type="hidden" name="item_name" value="<?php echo $event_name." - ".$attendee_id." - ".$attendee_name;?>" />
							<input type="hidden" name="item_number" value="<?php echo $event_identifier;?>" />
							<input type="hidden" name="amount" value="<?php echo $event_cost;?>" />
							<!-- <input type="hidden" name="currency_code" value="<?php echo $paypal_cur;?>" />-->
							<input type="hidden" name="undefined_quantity" value="0" />
							<input type="hidden" name="custom" value="<?php echo $attendee_id;?>" />
							<input type="hidden" name="image_url" value="<?php echo $image_url;?>" />
							<input type="hidden" name="email" value="<?php echo $attendee_email;?>" />
							<input type="hidden" name="first_name" value="<?php echo $attendee_first;?>" />
							<input type="hidden" name="last_name" value="<?php echo $attendee_last;?>" />
							<input type="hidden" name="address1" value="<?php echo $attendee_address;?>" />
							<input type="hidden" name="address2" value="" />
							<input type="hidden" name="city" value="<?php echo $attendee_city;?>" />
							<input type="hidden" name="state" value="<?php echo $attendee_state;?>" />
							<input type="hidden" name="zip" value="<?php echo $attendee_zip;?>" />
							<input type="hidden" name="return" value="<?php echo get_option('siteurl')?>/?page_id=<?php echo $return_url;?>" />
							<input type="hidden" name="cancel_return" value="<?php echo get_option('siteurl')?>/?page_id=<?php echo $cancel_return;?>" />
							<input type="hidden" name="notify_url" value="<?php echo get_option('siteurl')?>/?page_id=<?php echo $notify_url?>&amp;id=<?php echo $attendee_id;?>&amp;event_id=<?php echo $event_id?>&amp;attendee_action=post_payment&amp;form_action=payment" />
							<input type="hidden" name="rm" value="2" />
							<input type="hidden" name="add" value="1" />
							<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" align='middle' name="submit" />
						</form>
					</td>
				</tr>
			</table>
			<?php
			}
		}
	}
}
?>
