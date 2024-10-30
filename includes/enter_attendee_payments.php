<?php function enter_attendee_payments(){
		global $wpdb;
		$events_detail_tbl = get_option('events_detail_tbl');
		$events_attendee_tbl = get_option('events_attendee_tbl');
		$event_id = $_REQUEST['event_id'];
		$today = date("m-d-Y");
			
		if ( $_REQUEST['form_action'] == 'payment' ){
		if ( $_REQUEST['attendee_action'] == 'post_payment' ){
			$id = $_REQUEST['id'];
			$payment_status = $_REQUEST['payment_status'];
			$txn_type = $_REQUEST['txn_type'];
			$txn_id = $_REQUEST['txn_id'];
			$quantity = $_REQUEST['quantity'];
			$amount_pd = $_REQUEST['amount_pd'];
			$payment_date = $_REQUEST['payment_date'];
				
			$sql="UPDATE ". $events_attendee_tbl . " SET payment_status = '$payment_status', txn_type = '$txn_type', txn_id = '$txn_id', amount_pd = '$amount_pd', quantity = '$quantity', payment_date ='$payment_date' WHERE id ='$id'";
			$wpdb->query($sql);
			//	Send Payment Recieved Email
		if ($_REQUEST['send_payment_rec'] == "send_message"){
			$sql  = "SELECT * FROM " . $events_attendee_tbl . " WHERE id ='$id'";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc ($result))
				{
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
				}					
			$events_organization_tbl = get_option('events_organization_tbl');
			$sql  = "SELECT * FROM ". $events_organization_tbl ." WHERE id='1'";
			$result = mysql_query($sql); 
				while ($row = mysql_fetch_assoc ($result))
					{
						$return_url = $row['return_url'];
					}
			$payment_link = $return_url."?id=".$id;
			//$payment_link = get_option('siteurl') . "/?page_id=" . $return_url . "&id=".$id;
			$subject = "Event Payment Received";
			$distro=$email;
			$message=("***This Is An Automated Response*** \r\n\nThank You $fname $lname.  We have received a payment in the amount of $".$amount_pd." for your event registration.");
			wp_mail($distro, $subject, $message); 
			echo "<br>Payment Received notification sent to $fname $lname.<br>";
			
		}
						
				
		}else {
			$id = $_REQUEST['id'];
			$sql  = "SELECT * FROM " . $events_attendee_tbl . " WHERE id ='$id'";
			$result = mysql_query($sql);
			while ($row = mysql_fetch_assoc ($result))
				{
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
			}
						
		echo "<form method='post' action='".$_SERVER['REQUEST_URI']."'>";
		echo "<br><br>Payment For: ".$fname." ".$lname."<br>";
		?>
		<input type="text" name="payment_status" size="45" value ="<?php echo $payment_status;?>" />
		<br />
		Transaction Type:
		<input type="text" name="txn_type" size="45" value ="<?php echo $txn_type;?>" />
		<br />
		Transaction ID:
		<input type="text" name="txn_id" size="45" value ="<?php echo $txn_id;?>" />
		<br />
		Amount Paid:
		<input type="text" name="amount_pd" size="45" value ="<?php echo $amount_pd;?>" />
		<br />
		How Many People:
		<input type="text" name="quantity" size="45" value ="<?php echo $quantity;?>" />
		<br />
		Date Paid:
		<input type="text" name="payment_date" size="45" value ="<?php if ($payment_date !=""){echo $payment_date;} if ($payment_date ==""){echo $today;} ?>" />
		<br />
		Do you want to send a payment recieved notice to registrant?
		<input type="radio" name="send_payment_rec" checked value="send_message">
		Yes
		<input type="radio" name="send_payment_rec" value="N">
		No<br>
		<input type="hidden" name="id" value="<?php echo $id?>">
		<input type="hidden" name="form_action" value="payment">
		<input type="hidden" name="attendee_pay" value="paynow">
		<input type="hidden" name="event_id" value="<?php echo $event_id?>">
		<input type="hidden" name="attendee_action" value="post_payment">
		<br>
		<br>
		<input type="submit" name="Submit" value="POST PAYMENT">
		</form>
		<hr>
		<hr>
		<br>
<?php
				}
		}
	}//End function enter_attendee_payments
	
	?>