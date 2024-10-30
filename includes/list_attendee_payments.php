<?php 
//Displays the list of attendees and the paymnts they have made
function list_attendee_payments(){
	
	//Displays attendee information from current active event.
	global $wpdb;
	$events_detail_tbl = get_option('events_detail_tbl');
	$events_attendee_tbl = get_option('events_attendee_tbl');
	$event_id = $_REQUEST['event_id'];
						
	$sql = "SELECT * FROM ". $events_detail_tbl . " WHERE id='$event_id'";
	$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc ($result)){
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
						
		$sql  = "SELECT * FROM " . $events_attendee_tbl . " WHERE event_id='$event_id'";
		$result = mysql_query($sql);
		echo "<br><strong>Current Active Event is: ".$event_name." - ".$event_identifier."</strong>";
?>
	<button style="background-color:lightgreen; width:200px; height: 40px;" onclick="window.location='<?php echo get_bloginfo('wpurl')."/wp-admin/admin.php?event_regis&id=".$event_id."&export=report&action=payment";?>'" > Export Event Payment List To Excel </button>
	<br>
	<hr>
	<table>
	  <thead>
		<tr>
		  <th width="15"></th>
		  <th>ID</th>
		  <th> Name </th>
		  <th>Email</th>
		  <th width='15'></th>
		  <th>Pay Status</th>
		  <th>TXN Type</th>
		  <th>TXN ID</th>
		  <th>Amount Pd</th>
		  <th>Quantity</th>
		  <th>Date Paid</th>
		  <th></th>
		</tr>
	  </thead>
	  <tbody>
		<?php while ($row = mysql_fetch_assoc ($result)){
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
										$payment_date = $row['payment_date'];
										$quantity = $row['quantity'];
										$event_id = $row['event_id'];
										$custom1 = $row['custom_1'];
										$custom2 = $row['custom_2'];
										$custom3 = $row['custom_3'];
	
										$custom4 = $row['custom_4'];
						?>
		<tr <?php if ($amount_pd == ""){ echo "class='not_paid'"; }else if ($payment_status == "Pending"){ echo "class='payment_pending'";}else if ($payment_status == "Completed"){ echo "class='payment_completed'";} ?>>
		  <td width="15" align="center"></td>
		  <td align="center"><?php echo $id?></td>
		  <td align="center"><?php echo $fname?>
			<?php echo $lname?></td>
		  <td align="center"><?php echo $email?></td>
		  <td width="15" align="center"></td>
		  <td align="center"><?php echo $payment_status?></td>
		  <td align="center"><?php echo $txn_type?></td>
		  <td align="center"><?php echo $txn_id?></td>
		  <td align="center"> $
			<?php echo $amount_pd?></td>
		  <td align="center"><?php echo $quantity?></td>
		  <td align="center"><?php echo $payment_date?></td>
		  <td align="center"><form name="form" method="post" action="<?php echo $_SERVER["REQUEST_URI"]?>">
			  <input type="hidden" name="event_id" value="<?php echo $event_id?>">
			  <input type="hidden" name="attendee_pay" value="paynow">
			  <input type="hidden" name="form_action" value="payment">
			  <input type="hidden" name="id" value="<?php echo $id?>">
			  <input style="font-size:10px" type="submit" value="ENTER PAYMENT">
			</form></td>
		</tr>
		<?	} ?>
	  </tbody>
	  <tfoot>
		<tr>
		  <td width="15" align="center"></td>
		  <td align="center"><strong>ID</strong></td>
		  <td align="center"><strong> Name </strong></td>
		  <td align="center"><strong>Email</strong></td>
		  <td width="15" align="center"></td>
		  <td align="center"><strong>Pay Status</strong></td>
		  <td align="center"><strong>TXN Type</strong></td>
		  <td align="center"><strong>TXN ID</strong></td>
		  <td align="center"><strong>Amount Pd</strong></td>
		  <td align="center"><strong>Quantity</strong></td>
		  <td align="center"><strong>Date Paid</strong></td>
		  <td align="center"></td>
		</tr>
	  </tfoot>
	</table>
<?
}//End function list_attendee_payments
?>