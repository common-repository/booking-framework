<?php
// Course Registration Subpage 2 - Add/Delete/Edit Courses
function event_regis_manage_events() {
?>
<div id="event_reg_theme" class="wrap">
<h2>Course Management</h2>

<?php 
//function to delete event
function delete_event(){
	global $wpdb;
	$events_detail_tbl = get_option('events_detail_tbl');

	if($_REQUEST['action'] == 'delete') {
		$id = $_REQUEST['id'];
		$sql = "DELETE FROM $events_detail_tbl WHERE id='$id'";
		$wpdb -> query($sql);
	}
}

// Adds an event to the event database
function add_event_funct_to_db() {
	global $wpdb;
	$events_detail_tbl = get_option('events_detail_tbl');

	if(isset($_POST['Submit'])) {
		if($_REQUEST['action'] == 'add') {
			$event_name =			htmlentities2($_REQUEST['event']);
			$event_identifier =		htmlentities2($_REQUEST['event_identifier']);
			$event_location =		htmlentities2($_REQUEST['event_location']); // added in 3.2 for courses searching
			$event_course =			htmlentities2($_REQUEST['event_course']); // added in 3.2 for courses searching
			$event_desc_new =		htmlentities2($_REQUEST['event_desc_new']); 
			$display_desc =			$_REQUEST['display_desc'];
			$reg_limit =			$_REQUEST['reg_limit'];
			$allow_multiple =		$_REQUEST['allow_multiple'];
			$event_cost =			$_REQUEST['cost'];
			$is_active =			$_REQUEST['is_active'];
			$start_month =			$_REQUEST['start_month'];
			$start_day =			$_REQUEST['start_day'];
			$start_year =			$_REQUEST['start_year'];
			$end_month =			$_REQUEST['end_month'];
			$end_day =				$_REQUEST['end_day'];
			$end_year =				$_REQUEST['end_year'];
			$start_time =			$_REQUEST['start_time'];
			$start_time_am_pm =		$_REQUEST['start_time_am_pm'];
			$start_time =			$start_time.' '.$start_time_am_pm;
			$end_time =				$_REQUEST['end_time'];
			$end_time_am_pm =		$_REQUEST['end_time_am_pm'];
			$end_time =				$end_time.' '.$end_time_am_pm;
			$question1 =			htmlentities2($_REQUEST['quest1']);
			$question2 =			htmlentities2($_REQUEST['quest2']);
			$question3 =			htmlentities2($_REQUEST['quest3']);
			$question4 =			htmlentities2($_REQUEST['quest4']);
			$conf_mail =			htmlentities2($_REQUEST['conf_mail']);
			$send_mail =			$_REQUEST['send_mail'];
			$use_coupon_code =		$_REQUEST['use_coupon_code'];
			$coupon_code =			$_REQUEST['coupon_code'];
			$coupon_code_price =	$_REQUEST['coupon_code_price'];

			//Build the start and end dates for sorting purposes
			if ($start_month == "Jan"){$month_no = '01';}
			if ($start_month == "Feb"){$month_no = '02';}
			if ($start_month == "Mar"){$month_no = '03';}
			if ($start_month == "Apr"){$month_no = '04';}
			if ($start_month == "May"){$month_no = '05';}
			if ($start_month == "Jun"){$month_no = '06';}
			if ($start_month == "Jul"){$month_no = '07';}
			if ($start_month == "Aug"){$month_no = '08';}
			if ($start_month == "Sep"){$month_no = '09';}
			if ($start_month == "Oct"){$month_no = '10';}
			if ($start_month == "Nov"){$month_no = '11';}
			if ($start_month == "Dec"){$month_no = '12';}
			$start_date = $start_year."-".$month_no."-".$start_day;

			if ($end_month == "Jan"){$end_month_no = '01';}
			if ($end_month == "Feb"){$end_month_no = '02';}
			if ($end_month == "Mar"){$end_month_no = '03';}
			if ($end_month == "Apr"){$end_month_no = '04';}
			if ($end_month == "May"){$end_month_no = '05';}
			if ($end_month == "Jun"){$end_month_no = '06';}
			if ($end_month == "Jul"){$end_month_no = '07';}
			if ($end_month == "Aug"){$end_month_no = '08';}
			if ($end_month == "Sep"){$end_month_no = '09';}
			if ($end_month == "Oct"){$end_month_no = '10';}
			if ($end_month == "Nov"){$end_month_no = '11';}
			if ($end_month == "Dec"){$end_month_no = '12';}
			$end_date = $end_year."-".$end_month_no."-".$end_day;

			// Post the new event into the database
			$sql = "INSERT INTO $events_detail_tbl (event_name, event_desc, event_location, event_course, display_desc, event_identifier, start_month, start_day, start_year, start_date, start_time, end_month, end_day, end_year, end_date, end_time, reg_limit, allow_multiple, event_cost, send_mail, is_active, question1, question2, question3, question4, conf_mail, use_coupon_code, coupon_code, coupon_code_price) VALUES('$event_name', '$event_desc_new', '$event_location', '$event_course', '$display_desc', '$event_identifier', '$start_month', '$start_day', '$start_year', '$start_date', '$start_time', '$end_month', '$end_day', '$end_year', '$end_date', '$end_time', '$reg_limit', '$allow_multiple', '$event_cost', '$send_mail', '$is_active', '$question1', '$question2', '$question3', '$question4', '$conf_mail', '$use_coupon_code', '$coupon_code', '$coupon_code_price')";
			$wpdb->query($sql);
		}
	}
	if($_REQUEST['action'] == 'update') {
		$id =$_REQUEST['id'];
		$event_name =				htmlentities2($_REQUEST['event']);
		$event_identifier =			$_REQUEST['event_identifier'];
		$event_location =			htmlentities2($_REQUEST['event_location']);
		$event_course =				htmlentities2($_REQUEST['event_course']);
		$event_desc =				htmlentities2($_REQUEST['event_desc']); 
		$display_desc =				$_REQUEST['display_desc'];
		$reg_limit =				$_REQUEST['reg_limit'];
		$allow_multiple =			$_REQUEST['allow_multiple'];
		$cost =						$_REQUEST['cost'];
		$is_active =				$_REQUEST['is_active'];
		$start_month =				$_REQUEST['start_month'];
		$start_day =				$_REQUEST['start_day'];
		$start_year =				$_REQUEST['start_year'];
		$end_month =				$_REQUEST['end_month'];
		$end_day =					$_REQUEST['end_day'];
		$end_year =					$_REQUEST['end_year'];
		$start_time =				$_REQUEST['start_time'];
		$start_time_am_pm =			$_REQUEST['start_time_am_pm'];
		$start_time =				$start_time.' '.$start_time_am_pm;
		$end_time =					$_REQUEST['end_time'];
		$end_time_am_pm =			$_REQUEST['end_time_am_pm'];
		$end_time =					$end_time.' '.$end_time_am_pm;
		$quest1 =					htmlentities2($_REQUEST['quest1']);
		$quest2 =					htmlentities2($_REQUEST['quest2']);
		$quest3 =					htmlentities2($_REQUEST['quest3']);
		$quest4 =					htmlentities2($_REQUEST['quest4']);
		$conf_mail =				htmlentities2($_REQUEST['conf_mail']);
		$send_mail =				$_REQUEST['send_mail'];
		$use_coupon_code =			$_REQUEST['use_coupon_code'];
		$coupon_code =				$_REQUEST['coupon_code'];
		$coupon_code_price =		$_REQUEST['coupon_code_price'];

		//Build the start and end dates for sorting purposes
		if ($start_month == "Jan"){$month_no = '01';}
		if ($start_month == "Feb"){$month_no = '02';}
		if ($start_month == "Mar"){$month_no = '03';}
		if ($start_month == "Apr"){$month_no = '04';}
		if ($start_month == "May"){$month_no = '05';}
		if ($start_month == "Jun"){$month_no = '06';}
		if ($start_month == "Jul"){$month_no = '07';}
		if ($start_month == "Aug"){$month_no = '08';}
		if ($start_month == "Sep"){$month_no = '09';}
		if ($start_month == "Oct"){$month_no = '10';}
		if ($start_month == "Nov"){$month_no = '11';}
		if ($start_month == "Dec"){$month_no = '12';}
		$start_date = $start_year."-".$month_no."-".$start_day;

		if ($end_month == "Jan"){$end_month_no = '01';}
		if ($end_month == "Feb"){$end_month_no = '02';}
		if ($end_month == "Mar"){$end_month_no = '03';}
		if ($end_month == "Apr"){$end_month_no = '04';}
		if ($end_month == "May"){$end_month_no = '05';}
		if ($end_month == "Jun"){$end_month_no = '06';}
		if ($end_month == "Jul"){$end_month_no = '07';}
		if ($end_month == "Aug"){$end_month_no = '08';}
		if ($end_month == "Sep"){$end_month_no = '09';}
		if ($end_month == "Oct"){$end_month_no = '10';}
		if ($end_month == "Nov"){$end_month_no = '11';}
		if ($end_month == "Dec"){$end_month_no = '12';}
		$end_date = $end_year."-".$end_month_no."-".$end_day;

		// Post the new event into the database
		$sql = "UPDATE $events_detail_tbl SET event_name='$event_name', event_identifier='$event_identifier', event_location='$event_location', event_course='$event_course', reg_limit='$reg_limit', allow_multiple='$allow_multiple', event_desc='$event_desc', display_desc='$display_desc', send_mail='$send_mail', event_cost='$cost', is_active='$is_active', start_month='$start_month', start_day='$start_day', start_year='$start_year', start_date='$start_date', end_month='$end_month', end_day='$end_day', end_year='$end_year', end_date='$end_date', start_time='$start_time', end_time='$end_time', question1='$quest1', question2='$quest2', question3='$quest3', question4='$quest4', conf_mail='$conf_mail', use_coupon_code='$use_coupon_code', coupon_code='$coupon_code', coupon_code_price='$coupon_code_price' WHERE id = $id";
		$wpdb -> query($sql);
	}

  	// function to display events
	function display_event_details($all = 0) {
		?>
		<h3>Current Events</h3>
		<?php
		global $wpdb;
		$events_organization_tbl = get_option('events_organization_tbl');
		$sql = "SELECT * FROM ".$events_organization_tbl." WHERE id='1'";
		$result = mysql_query($sql);
		while($row = mysql_fetch_assoc($result)) {
			$event_page_id = $row['event_page_id'];
		}

		$events_detail_tbl = get_option('events_detail_tbl');
		$curdate = date("Y-m-d");
		$sql = "SELECT * FROM $events_detail_tbl ORDER BY start_date ASC";
		$result = mysql_query($sql);

		while($row = mysql_fetch_assoc($result)) {
			$event_name =			$row['event_name'];
			$event_desc =			$row['event_desc'];
			$display_desc =			$row['display_desc'];
			$event_identifier =		$row['event_identifier'];
			$event_location =		$row['event_location'];
			$event_course =			$row['event_course'];
			$reg_limit =			$row['reg_limit'];
			$allow_multiple =		$row['allow_multiple'];
			$start_date =			$row['start_date'];
			$start_month =			$row['start_month'];
			$start_day =			$row['start_day'];
			$start_year =			$row['start_year'];
			$end_month =			$row['end_month'];
			$end_day =				$row['end_day'];
			$end_year =				$row['end_year'];
			$start_time =			$row['start_time'];
			$end_time =				$row['end_time'];
			$cost =					$row['event_cost'];
			$active =				$row['is_active'];
			$question1 =			$row['question1'];
			$question2 =			$row['question2'];
			$question3 =			$row['question3'];
			$question4 =			$row['question4'];
			$send_mail =			$row['send_mail'];
			$conf_mail =			$row['conf_mail'];
			$use_coupon_code =		$row['use_coupon_code'];
			$coupon_code =			$row['coupon_code'];
			$coupon_code_price =	$row['coupon_code_price'];

			$paypal_cur = get_option('paypal_cur');
			?>

<!-- // Event holder // -->
<div class="metabox-holder">
	<div class="postbox">
		<h3>
			<div style="float:right"><a href="#" onclick="document.getElementById('<?php echo $row['id'];?>').style.display = '';return false;">Show</a>/<a href="#" onclick="document.getElementById('<?php echo $row['id'];?>').style.display = 'none';return false;">Hide</a></div>
			<span><a href="<?php echo get_option('siteurl');?>/?page_id=<?php echo $event_page_id;?>&amp;regevent_action=register&amp;event_id=<?php echo $row['id'];?>" target="_blank"><?php echo $event_name;?></a> - <?php echo $start_date;?> | <?php echo $event_location;?> | <span style="font-weight: normal"><?php echo $cost.' '.$paypal_cur;?></span></span>
		</h3>
		<ul id="<?php echo $row['id'];?>" style="display:none">
			<li>
				<p class="greenbox">Add the following link to your <strong>bookings page</strong>:<br /><small><a href="<?php echo get_option('siteurl');?>/?page_id=<?php echo $event_page_id;?>&amp;regevent_action=register&amp;event_id=<?php echo $row['id'];?>" target="_blank"><?php echo get_option('siteurl');?>/?page_id=<?php echo $event_page_id;?>&amp;regevent_action=register&amp;event_id=<?php echo $row['id'];?></a></small></p>
				<p>
					<a href="<?php echo get_option('siteurl')?>/?page_id=<?php echo $event_page_id?>&amp;regevent_action=register&amp;event_id=<?php echo $row['id'];?>" target="_blank"><strong>Click here to make an offline booking</strong></a><a class="ev_reg-fancylink" href="#offline_info">?</a>
					<form name="form" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" style="display: inline">
						<input type="hidden" name="action" value="edit">
						<input type="hidden" name="id" value="<?php echo $row['id'];?>">
						<input type="submit" name="edit" value="Edit event" id="edit_event_setting" />
					</form>
					<form name="form" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" style="display: inline">
						<input type="hidden" name="action" value="delete">
						<input type="hidden" name="id" value="<?php echo $row['id'];?>">
						<input type="submit" name="delete" value="Delete event" id="delete_event" onclick="return confirm('Are you sure you want to delete <?php echo $row['event_name'];?>?')"/>
					</form>
				</p>
				<p>
					Cost: <?php echo $paypal_cur.''.$cost;?>, Attendees limit: <?php echo $reg_limit;?>,
					<?php
					if($allow_multiple=='Y') {echo ' Additional attendees <strong>allowed</strong>';}
					if($allow_multiple=='N') {echo ' Additional attendees <strong>not allowed</strong>';}
					?>
					<?php
					if($use_coupon_code=='Y') {
						echo ', Promo codes <strong>allowed.</strong>';
						echo ' <span class="description">Promo Code: '.$coupon_code.' (discount: '.$paypal_cur.''.$coupon_code_price.')</span>';
					}
					if($use_coupon_code=='N') {echo ', Promo codes <strong>not allowed</strong>.';}
					?>
					<br />
				</p>
				<p><strong>Start Date:</strong> <?php echo $start_month.' '.$start_day.', '.$start_year;?> - <strong>End Date:</strong> <?php echo $end_month.' '.$end_day.', '.$end_year?> <span class="description"><small>Start time: <?php echo $start_time;?> - End time: <?php echo $end_time;?></small></span></p>
				<p><strong>Event description:</strong></p>
				<?php
				if($display_desc == '') {echo '<p class="red_text"><strong><em>PLEASE UPDATE THIS EVENT</em></strong></p>';}
				echo htmlspecialchars_decode($event_desc);
				?>
				<hr />
				<p>
					<small>ID: <?php echo $event_identifier?> -
					<?php
					if($active == 'yes') {echo ' Event is <strong>active</strong>';}
					if($active == 'no') {echo ' Event is <strong>not active</strong>';}
					if($display_desc == 'Y') {echo ' and description is <strong>displayed</strong>.';}
					if($display_desc == 'N') {echo ' and description is <strong>not displayed</strong>.';}
					?></small>
				</p>
				<p><strong>Shortcode:</strong> [SINGLEEVENT single_event_id="<?php echo $event_identifier;?>"]</p>
			</li>
		</ul>
	</div>
</div>
<?php }}

//function to edit event
function edit_event(){
	global $wpdb;
	$events_detail_tbl = get_option('events_detail_tbl');
						
	$id=$_REQUEST['id'];
	//Query Database for Active event and get variable
	$sql  = "SELECT * FROM " . $events_detail_tbl . " WHERE id =".$id;
	$result = mysql_query($sql);
	while ($row = mysql_fetch_assoc ($result)){
		$id = $row['id'];
		$event_name = $row['event_name'];
		$event_desc = $row['event_desc'];
		$display_desc= $row['display_desc'];
		$event_description = $row['event_desc'];
		$event_identifier = $row['event_identifier'];
		$event_location = $row['event_location'];
		$event_course= $row['event_course'];
		$start_month =$row['start_month'];
		$start_day = $row['start_day'];
		$start_year = $row['start_year'];
		$end_month = $row['end_month'];
		$end_day = $row['end_day'];
		$end_year = $row['end_year'];
		$start_time = $row['start_time'];
		$end_time = $row['end_time'];
		$reg_limit = $row['reg_limit'];
		$allow_multiple = $row['allow_multiple'];
		$event_cost = $row['event_cost'];
		$active = $row['is_active'];
		$question1 = $row['question1'];
		$question2 = $row['question2'];
		$question3 = $row['question3'];
		$question4 = $row['question4'];
		$conf_mail=$row['conf_mail'];
		$send_mail=$row['send_mail'];
		$use_coupon_code=$row['use_coupon_code'];
		$coupon_code = $row['coupon_code'];
		$coupon_code_price = $row['coupon_code_price'];
		}
	update_option("current_event", $event_name);
?>
<!--Update event display-->
<div class="metabox-holder">
  <div class="postbox">
    <h3>Edit - <a href="<?php echo get_option('siteurl')?>/?page_id=<?php echo $event_page_id?>&regevent_action=register&event_id=<?php echo $row["id"]?>&name_of_event=<?php echo $event_name?>" target="_blank"><?php echo $event_name;?></a></h3>
    <form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
    <input type="hidden" name="action" value="update"> 
      <input type="hidden" name="id" value=<?php echo $id?>>
      <ul>
        <li><label for="event_name">Event Name:</label>
          <input type="text" name="event" size="25" value ="<?php echo $event_name;?>" /></li>
        <li>
          <label for="event_identifier">Unique ID For Event </label>
          <input type="text" name="event_identifier" value ="<?php echo $event_identifier;?>" /> <a class="ev_reg-fancylink" href="#unique_id_info">?</a></li>
        <li><label for="event_location">Event location:</label>
          <input type="text" name="event_location" size="25" value ="<?php echo $event_location;?>" /></li>
        <li><label for="event_course">Course:</label>
          <input type="text" name="event_course" size="25" value ="<?php echo $event_course;?>" /></li>
        <li><label>Do you want to display the event description on registration page?</label>
          <?php
		if ($display_desc ==""){
			echo "<input type='radio' name='display_desc' checked value='Y'>Yes";
			echo "<input type='radio' name='display_desc' value='N'>No";
		}
		if ($display_desc =="Y"){
			echo "<input type='radio' name='display_desc' checked value='Y'>Yes";
			echo "<input type='radio' name='display_desc' value='N'>No";
		}
		if ($display_desc =="N"){
			echo "<input type='radio' name='display_desc' value='Y'>Yes";
			echo "<input type='radio' name='display_desc' checked value='N'>No";
		}
?>
          </li>
        <li> <label>Event Description:</label> <br />
          <textarea rows="5" cols="125" name="event_desc" id="event_desc"  class="my_ed"><?php echo $event_desc?></textarea>
          <br />
          <script>myEdToolbar('event_desc'); </script>
          </li>
        
        <li><?php dateSelectionBox ( $start_month, $start_day, $start_year, $end_month, $end_day, $end_year ); ?></li>
        
        
        <li>Start Time:
          <select name="start_time">
            <option name="<?php echo $start_time;?>">
              <?php echo $start_time;?>
              </option>
            <option name="1:00">1:00</option>
            <option name="2:00">2:00</option>
            <option name="3:00">3:00</option>
            <option name="4:00">4:00</option>
            <option name="5:00">5:00</option>
            <option name="6:00">6:00</option>
            <option name="7:00">7:00</option>
            <option name="8:00">8:00</option>
            <option name="9:00">9:00</option>
            <option name="10:00">10:00</option>
            <option name="11:00">11:00</option>
            <option name="12:00">12:00</option>
            </select>
          <select name="start_time_am_pm">
            <option name="<?php echo $start_time_am_pm;?>">
              <?php echo $start_time_am_pm;?>
              </option>
            <option name="AM">AM</option>
            <option name="PM">PM</option>
            </select>
          -  End Time:
          <select name="end_time">
            <option name="<?php echo $end_time;?>">
              <?php echo $end_time;?>
              </option>
            <option name="1:00">1:00</option>
            <option name="2:00">2:00</option>
            <option name="3:00">3:00</option>
            <option name="4:00">4:00</option>
            <option name="5:00">5:00</option>
            <option name="6:00">6:00</option>
            <option name="7:00">7:00</option>
            <option name="8:00">8:00</option>
            <option name="9:00">9:00</option>
            <option name="10:00">10:00</option>
            <option name="11:00">11:00</option>
            <option name="12:00">12:00</option>
            </select>
          <select name="end_time_am_pm">
            <option name="<?php echo $end_time_am_pm;?>">
              <?php echo $end_time_am_pm;?>
              </option>
            <option name="AM">AM</option>
            <option name="PM">PM</option>
          </select></li>
        
        
        <li>Attendee Limit (leave blank for unlimited)
          <input name="reg_limit" size="10" value ="<?php echo $reg_limit;?>"></li>
        <li>Allow payment for more than one person at a time?<!-- (max # people 5)-->
			<?php
		if ($allow_multiple == "") {
			echo " <input type='radio' name='allow_multiple' checked value='Y'>Yes (Groups)";
			echo " <input type='radio' name='allow_multiple' value='N'>No (Couples)";
		}
		if ($allow_multiple == "Y") {
			echo " <input type='radio' name='allow_multiple' checked value='Y'>Yes (Groups)";
			echo " <INPUT type='radio' name='allow_multiple' value='N'>No (Couples)";
		}
		if ($allow_multiple == "N") {
			echo " <input type='radio' name='allow_multiple' value='Y'>Yes (Groups)";
			echo " <input type='radio' name='allow_multiple' checked VALUE='N'>No (Couples)";
		}
		?>
		</li>  
        <li>Cost of the Event (leave blank for free events, enter 2 place decimal i.e. 7.00)
          <input name="cost" size="10" value ="<?php echo $event_cost;?>"></li>
        <li>Allow promo codes for this event? 
          <?php
		if ($use_coupon_code ==""){
			echo "<input type='radio' name='use_coupon_code' checked value='Y'>Yes";
			echo "<input type='radio' name='use_coupon_code' value='N'>No";
		}
		if ($use_coupon_code =="Y"){
			echo "<input type='radio' name='use_coupon_code' checked value='Y'>Yes";
			echo "<input type='radio' name='use_coupon_code' value='N'>No";
		}
		if ($use_coupon_code =="N"){
			echo "<input type='radio' name='use_coupon_code' value='Y'>Yes";
			echo "<input type='radio' name='use_coupon_code' checked value='N'>No";
		}
?>
          <a class="ev_reg-fancylink" href="#coupon_code_info">?</a>
          </li>
        <li>Promo Code for Event (leave blank for no promo)
          <input name="coupon_code" size="20" value ="<?php echo $coupon_code;?>"></li>
        <li>Discount w/Promo Code (enter 2 place decimal i.e. 7.00.)
          -$<input name="coupon_code_price" size="10" value ="<?php echo $coupon_code_price;?>"></li>
        
        <li>Is this an active event?
          <select name="is_active">
            <option <?php if($active == "yes") echo 'selected="selected"'?>>yes</option>
            <option <?php if($active == "no") echo 'selected="selected"'?>>no</option>
            </select></li>
        <li> Send A Custom Cofiramtion Eamil For This Event?
          <?php
	if ($send_mail ==""){
		echo "<input type='radio' name='send_mail' checked value='Y'>Yes";
		echo "<input type='radio' name='send_mail' value='N'>No";}
	if ($send_mail =="Y"){
		echo "<input type='radio' name='send_mail' checked value='Y'>Yes";
		echo "<input type='radio' name='send_mail' value='N'>No";}
	if ($send_mail =="N"){
		echo "<input type='radio' name='send_mail' value='Y'>Yes";
		echo "<input type='radio' name='send_mail' checked value='N'>No";}
?>
          </li>
        <li>Custom Confirmation Email For This Event:<br />
          <textarea rows="5" cols="125" name="conf_mail" id="conf_mail"  class="my_ed"><?php echo $conf_mail?></textarea>
          <br />
          <script>myEdToolbar('conf_mail'); </script>
          </li>
        <li>
          <p>
            <input class="button-primary" type="submit" name="Submit" value="<?php _e('Update Event'); ?>" id="save_event_setting" />
            </p>
          </li>
        </ul>
      
    </form>
  </div>
</div>

<?php
	}
	if ( $_REQUEST['action'] == 'delete' ){delete_event();}
	if ( $_REQUEST['action'] == 'edit' ){edit_event();}
	
?>

<!--Add event display-->
<div class="metabox-holder">
	<div class="postbox">
		<h3>Add An Event</h3>
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
				<input type="hidden" name="action" value="add" />
				<ul>
					<li><input name="event" size="25" id="event" /> <label for="event">Event name</label></li>
					<li><input name="event_identifier" id="event_identifier" size="25" /> <label for="event_identifier">Unique ID for event</label> <a class="ev_reg-fancylink" href="#unique_id_info">?</a></li>
					<li><input name="event_location" size="25" id="event_location" /> <label for="event_location">Event location</label> <em>(mandatory for course search feature)</em></li>
					<li><input name="event_course" size="25" id="event_course" /> <label for="event_course">Course</label> <em>(mandatory for course search feature)</em></li>
					<li>
						Display event description on registration page?
						<?php
						if($display_desc == '') {
							echo '<input type="radio" name="display_desc" id="display_desc_y" checked="checked" value="Y" /> <label for="display_desc_y">Yes</label> ';
							echo '<input type="radio" name="display_desc" id="display_desc_n" value="N" /> <label for="display_desc_n">No</label>';
						}
						if($display_desc == 'Y') {
							echo '<input type="radio" name="display_desc" id="display_desc_y" checked="checked" value="Y" /> <label for="display_desc_y">Yes</label> ';
							echo '<input type="radio" name="display_desc" id="display_desc_n" value="N" /> <label for="display_desc_n">No</label>';
						}
						if($display_desc == 'N') {
							echo '<input type="radio" name="display_desc" id="display_desc_y" value="Y"> <label for="display_desc_y">Yes</label> ';
							echo '<input type="radio" name="display_desc" id="display_desc_n" checked value="N"> <label for="display_desc_n">No</label>';
						}
						?>
					</li>
					<li>
						Event description<br />
						<textarea rows="5" cols="125" name="event_desc_new" id="event_desc_new" class="my_ed"></textarea><br />
						<script type="text/javascript">myEdToolbar('event_desc_new');</script>
					</li>
					<li><input name="reg_limit" id="reg_limit" size="15" /> <label for="reg_limit">Attendee limit (leave blank for unlimited attendees)</label></li>
					<li>
						Allow payment for more than one person at a time? <!-- (max # people 5)-->
						<input type="radio" name="allow_multiple" id="allow_multiple_y" checked="checked" value="Y" /> <label for="allow_multiple_y">Yes (Groups)</label> 
						<input type="radio" name="allow_multiple" id="allow_multiple_n" value="N" /> <label for="allow_multiple_n"> No (Couples)</label>
					</li>
					<li><input name="cost" id="cost" size="10" /> <label for="cost">Event cost (leave blank for free events, enter 2 place decimal i.e. 7.00)</label></li>
					<li>
						Allow promo codes for this event? 
						<?php
						if($use_coupon_code == '') {
							echo "<input type='radio' name='use_coupon_code' checked value='Y'>Yes";
							echo "<input type='radio' name='use_coupon_code' value='N'>No";
						}
						if ($use_coupon_code =="Y"){
							echo "<input type='radio' name='use_coupon_code' checked value='Y'>Yes";
							echo "<input type='radio' name='use_coupon_code' value='N'>No";
						}
						if ($use_coupon_code =="N"){
							echo "<input type='radio' name='use_coupon_code' value='Y'>Yes";
							echo "<input type='radio' name='use_coupon_code' checked value='N'>No";
						}
						?>
 <a class="ev_reg-fancylink" href="#coupon_code_info">?</a> </li>
    <li>Promo Code <input name="coupon_code" size="20" > </li>
    <li>Discount w/Promo Code(enter 2 place decimal i.e. 7.00.)
      -$<input name="coupon_code_price" size="10" ></li>
    <li><?php dateSelectionBox ();?>
    Start Time:
    <select name="start_time">
      <option name="01:00">01:00</option>
      <option name="02:00">02:00</option>
      <option name="03:00">03:00</option>
      <option name="04:00">04:00</option>
      <option name="05:00">05:00</option>
      <option name="06:00">06:00</option>
      <option name="07:00">07:00</option>
      <option name="08:00">08:00</option>
      <option name="09:00">09:00</option>
      <option name="10:00">10:00</option>
      <option name="11:00">11:00</option>
      <option name="12:00">12:00</option>
    </select>
    <select name="start_time_am_pm">
      <option name="AM">AM</option>
      <option name="PM">PM</option>
    </select>
    -  End Time:
    <select name="end_time">
      <option name="01:00">01:00</option>
      <option name="02:00">02:00</option>
      <option name="03:00">03:00</option>
      <option name="04:00">04:00</option>
      <option name="05:00">05:00</option>
      <option name="06:00">06:00</option>
      <option name="07:00">07:00</option>
      <option name="08:00">08:00</option>
      <option name="09:00">09:00</option>
      <option name="10:00">10:00</option>
      <option name="11:00">11:00</option>
      <option name="12:00">12:00</option>
    </select>
    <select name="end_time_am_pm">
      <option name="AM">AM</option>
      <option name="PM">PM</option>
    </select>
   </li>
    <li> Is this event active?
      <select name="is_active">
        <option>yes</option>
        <option>no</option>
      </select></li>
    
   <li> Do you want to send a custom confirmation email?
    <?php									
		echo "<input type='radio' name='send_mail' checked value='Y'>Yes";
		echo "<input type='radio' name='send_mail' value='N'>No";
	?></li>
    <li>Custom Confirmation Email For This Event: <br />
    <textarea rows='4' cols='125' name='conf_mail' id="conf_mail_new"  class="my_ed"><p>***This is an automated response - Do Not Reply***</p>
<p>Thank you [fname] [lname] for registering for [event].</p>
<p> We hope that you will find this event both informative and enjoyable. Should have any questions, please contact [contact].</p>

<p>If you have not done so already, please submit your payment in the amount of [cost].</p>
<p>Click here to reveiw your payment information [payment_url].</p>
<p>Thank You.</p></textarea>
      <br />
      <script>myEdToolbar('conf_mail_new'); </script>
</li>   
    
    <li>
    <p>
            <input class="button-primary" type="submit" name="Submit" value="<?php _e('Add New Event'); ?>" id="add_new_event" />
            </p>
    </li></ul>
  </form>
	</div>
</div>
<?php }

	add_event_funct_to_db();
	display_event_details();
?>
</div>
<div id="offline_info" style="display:none">
<h2>More Info</h2>
      <p>Click this link to be taken to the registration page.</p>
      <p>After completing registration, come back here and edit your payment details.</p>
</div>
<div id="coupon_code_info" style="display:none">
<h2>More Info</h2>
      <p>This is used to apply discounts to events.</p>
      <p>A coupon or promo code could can be anything you want. For example: If you supplied a promo like "PROMO50" and entered 50.00 into the "Price w/Promo Code" field your event will cost $50.00. </p>
</div>
<div id="unique_id_info" style="display:none">
      <h2>More Info</h2>
      <p>This should be a unique ID for the event. Example: "Event1" or "My Event" (without qoutes.)</p>
      <p>The unique ID can also be used in individual pages using the [SINGLEEVENT single_event_id="Unique Event ID"] shortcode.</p>
    </div>
<?php
}
?>