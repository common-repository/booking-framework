<?php
function edit_attendee_record(){
	global $wpdb;
	$events_detail_tbl = get_option('events_detail_tbl');
	$events_attendee_tbl = get_option('events_attendee_tbl');
	if ($_REQUEST['event_id'] !=""){$view_event = $_REQUEST['event_id'];}
	if ($_REQUEST['view_event'] !=""){$view_event = $_REQUEST['view_event'];}
	if ( $_REQUEST['form_action'] == 'edit_attendee' ){
				if ( $_REQUEST['attendee_action'] == 'delete_attendee' ){
					$id = $_REQUEST['id'];
					$sql= " DELETE FROM ". $events_attendee_tbl . " WHERE id ='$id'";
					$wpdb->query($sql);
				
				}
				else if ( $_REQUEST['attendee_action'] == 'update_attendee' ){
					$id = $_REQUEST['id'];
											   
					$regisration_id=$row['id'];
					$fname = $_POST['fname'];
					$lname = $_POST['lname'];
					$address = $_POST['address'];
					$city = $_POST['city'];
					$state = $_POST['state'];
					$zip = $_POST['zip'];
					$phone = $_POST['phone'];
					$email = $_POST['email'];
					$hear = $_POST['hear'];
					$event_id=$_POST['event_id'];
					$payment = $_POST['payment'];
					$custom_1 =$_POST['custom_1'];
					$custom_2 =$_POST['custom_2'];
					$custom_3 =$_POST['custom_3'];
					$custom_4 =$_POST['custom_4'];
					$sql="UPDATE ". $events_attendee_tbl . " SET fname='$fname', lname='$lname', address='$address', city='$city', state='$state', zip='$zip', phone='$phone', email='$email', payment='$payment', hear='$hear', custom_1='$custom_1', custom_2='$custom_2', custom_3='$custom_3', custom_4='$custom_4' WHERE id ='$id'";
					$wpdb->query($sql);
					echo "basic is added <br>";
									
					// Insert Extra From Post Here
					$events_question_tbl = get_option('events_question_tbl');
					$events_answer_tbl = get_option('events_answer_tbl');
					$reg_id = $id;
					$wpdb->query("DELETE FROM $events_answer_tbl where registration_id = '$reg_id'");

					$questions = $wpdb->get_results("SELECT * from `$events_question_tbl` where event_id = '$event_id'");
	
						if ($questions) {
							foreach ($questions as $question) {
								switch ($question->question_type) {
									case "TEXT":
									case "TEXTAREA":
									case "SINGLE":
										$post_val = $_POST[$question->question_type . '-' . $question->id];
										$wpdb->query("INSERT into $events_answer_tbl (registration_id, question_id, answer) values ('$reg_id', '$question->id', '$post_val')");
									break;
										case "MULTIPLE":
											$values = explode(",", $question->response);
											$value_string = '';
											foreach ($values as $key => $value) {
												$post_val = $_POST[$question->question_type . '-' . $question->id . '-' . $key];
												if ($key > 0 && !empty($post_val)){
													$value_string .= ',';
													$value_string .= $post_val;
												}
											}
									$wpdb->query("INSERT into $events_answer_tbl (registration_id, question_id, answer) values ('$reg_id', '$question->id', '$value_string')");
									break;
								}
							}
						}			
					
				}
				else{
					$sql = "SELECT * FROM ". $events_detail_tbl . " WHERE id='".$view_event."'";
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
					   			     	
					$id = $_REQUEST['id'];
					$sql  = "SELECT * FROM " . $events_attendee_tbl . " WHERE id ='$id'";
					$result = mysql_query($sql);
						while ($row = mysql_fetch_assoc ($result)){
							$id = $row['id'];
							$regisration_id=$row['id']; //TYPO?
							$lname = $row['lname'];
							$fname = $row['fname'];
							$address = $row['address'];
							$city = $row['city'];
							$state = $row['state'];
							$zip = $row['zip'];
							$email = $row['email'];
							$hear = $row['hear'];
							$payment = $row['payment'];
							$phone = $row['phone'];
							$quantity = $row['quantity']; //CIPRIAN
							$date = $row['date'];
							$payment_status = $row['payment_status'];
							$txn_type = $row['txn_type'];
							$txn_id = $row['txn_id'];
							$amount_pd = $row['amount_pd'];
							$quantity = $row['quantity'];
							$payment_date = $row['payment_date'];
							$event_id = $row['event_id'];
							$custom_1 = $row['custom_1'];
							$custom_2 = $row['custom_2'];
							$custom_3 = $row['custom_3'];
							$custom_4 = $row['custom_4'];
						}
?>
<div style="border: 5px solid #6F3; padding: 8px; background-color: #FFC">
<h3 class="title">View/Edit record for <?php echo $fname.', '.$lname;?></h3>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
<strong>Total Attendees:</strong> <?php echo $quantity;?><br /><br />
<input maxlength="45" size="30" name="fname" value ="<?php echo $fname;?>" /> <span class="description">First name</span><br />
<input maxlength="45" size="30" name="lname" value ="<?php echo $lname;?>" /> <span class="description">Last name</span><br />
<input maxlength="45" size="50" name="email" value ="<?php echo $email;?>" /> <span class="description">Email</span><br />
<input maxlength="45" size="50" name="address" value ="<?php echo $address;?>" /> <span class="description">Address</span><br />
<br />
<input maxlength="20" size="20" name="city" value ="<?php echo $city;?>"> <span class="description">City</span><br />
<input maxlength="30" size="20" name="state" value ="<?php echo $state;?>"> <span class="description">Country</span><br />
<input maxlength="15" size="20" name="phone" value ="<?php echo $phone;?>"> <span class="description">Phone</span><br />
<br />
<strong>Type of payment: </strong>
<select size="1" name="payment">
	<option value="<?php echo $payment;?>" selected><?php echo $payment;?></option>
	<option value="Paypal">Credit Card or Paypal</option>
	<option value="Cash">Cash</option>
	<option value="Check">Check</option>
</select>
<br />
<?php
$events_question_tbl = get_option('events_question_tbl');
$events_answer_tbl = get_option('events_answer_tbl');
$questions = $wpdb->get_results("SELECT * from $events_question_tbl where event_id = '$event_id' order by sequence");		
if($questions) {
	for($i = 0; $i < count($questions); $i++) {
		echo "<p><strong>".$questions[$i]->question."</strong><br>";

		$question_id = $questions[$i]->id;
		$query  = "SELECT * FROM $events_answer_tbl WHERE registration_id = '$id' AND question_id = '$question_id'";
		$result = mysql_query($query) or die('Error : ' . mysql_error());
		while ($row = mysql_fetch_assoc ($result)) {
			$answers = $row['answer'];
		}

		event_form_build($questions[$i], $answers);
		echo "</p>";
	} 
}
echo "<input type='hidden' name='id' value='".$id."' />";
echo "<input type='hidden' name='event_id' value='".$event_id."' />";
echo "<input type='hidden' name='display_action' value='view_list' />";
echo "<input type='hidden' name='view_event' value='".$view_event."' />";
echo "<input type='hidden' name='form_action' value='edit_attendee' />";
echo "<input type='hidden' name='attendee_action' value='update_attendee' />";
?>
<br />
<input class="button" type="submit" name="Submit" value="Update">
</form>
</div>
<?php
}
}
}
?>
