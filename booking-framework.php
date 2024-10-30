<?php
/*
Plugin Name: PayPal Booking Framework
Plugin URI: http://www.blogtycoon.net/wordpress-plugins/booking-framework
Description: This plugin has been built to support courses and classes bookings. Payments (if available) are done via PayPal IPN. It sends the registrant to your PayPal payment page for online collection of event fees. Events are sorted by date and a short code is provided to display a single event on a page.
Version: 3.3.1
Author: Ciprian Popescu
Author URI: http://www.blogtycoon.net/

Copyright 2009, 2010, 2011 Ciprian Popescu (email: office@butterflymedia.ro)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301  USA
*/

//Define static variables
define('EVNT_RGR_PLUGINPATH', '/'.plugin_basename(dirname(__FILE__)).'/');
define('EVNT_RGR_PLUGINFULLURL', WP_PLUGIN_URL.EVNT_RGR_PLUGINPATH);

//Install/Update Tables when plugin is activated
require("includes/database_install.php"); 
register_activation_hook(__FILE__,'events_data_tables_install');
//Event questions/options
require("includes/event_form_config.php");

//Payment Page/PayPal Buttons - Used to display the payment options and the payment link in the email. Used with the {EVENTREGPAY} tag
require("includes/paypal.class.php");
require("includes/payment_page.php");

//Events Listing - Shows the events on your page. Used with the {EVENTREGIS} tag
require("includes/display_all_events.php");

//List Attendees - Used with the {EVENTATTENDEES} tag
require("includes/attendee_list.php");

//Widget - Display the list of events in your sidebar
require("includes/widget.php");

//Payment processing - Used for onsite payment processing. Used with the {EVENTPAYPALTXN} tag
require("includes/process_payments.php");

//Build the admin header for the plugin
require("includes/admin_header.php");
add_action('admin_head', 'admin_register_head');

//Event Registration Subpage 1 - Configure Organization
require("includes/organization_config_mnu.php");

//Event Registration Subpage 2 - Add/Delete/Edit Events
require("includes/date_selections.php");
require("includes/manage_events.php");

//Event Registration Subpage 4 - View Attendees
require("includes/event_registration_reports.php");
require("includes/edit_attendee_record.php");
require("includes/list_attendees.php");

//Event Registration Subpage 5 - Enter Attendee Payments
require("includes/list_attendee_payments.php");
require("includes/enter_attendee_payments.php");

//Event Registration Main Admin Page
function event_regis_main_mnu() {
	/*  The following functions are what I wish to add to the main menu page
		1. Display current count of attendees for active event (show event name, description and id)- shows by default
	*/
	organization_config_mnu();
}

function add_event_registration_menus() {
	add_menu_page('PayPal Booking Framework', 'Bookings', 'manage_options', __FILE__, 'event_regis_main_mnu', EVNT_RGR_PLUGINFULLURL.'images/paypal_16.png');
	add_submenu_page(__FILE__, 'General Settings', 'General Settings', 'manage_options',  __FILE__, 'event_regis_main_mnu');
//	add_submenu_page(__FILE__, 'Form Settings', 'Form Settings', 'manage_options', 'form', 'event_form_config');
	add_submenu_page(__FILE__, 'Add/View Bookings', 'Add/View Bookings', 'manage_options', 'events', 'event_regis_manage_events');
	add_submenu_page(__FILE__, 'View Attendees', 'View Attendees', 'manage_options', 'organization', 'event_registration_reports');
	add_submenu_page(__FILE__, 'Process Payments', 'Process Payments', 'manage_options', 'attendee', 'event_process_payments');
}

//ADMIN MENU
add_action('admin_menu', 'add_event_registration_menus');

// Enable the ability for the event_funct to be loaded from pages
add_filter('the_content','event_regis_insert');
add_filter('the_content','event_regis_attendees_insert');
add_filter('the_content','event_regis_pay_insert');
add_filter('the_content','event_paypal_txn_insert');

// Function to deal with loading the events into pages
function event_regis_insert($content) {
	if(preg_match('{EVENTREGIS}',$content)) {
		$content = str_replace('{EVENTREGIS}',event_regis_run(),$content);
	}
	return $content;
}
function event_regis_attendees_insert($content) {
	if(preg_match('{EVENTATTENDEES}',$content)) {
		$content = str_replace('{EVENTATTENDEES}',event_attendee_list_run(),$content);
	}
	return $content;
}		
function event_regis_pay_insert($content) {
	if(preg_match('{EVENTREGPAY}',$content)) {
		$content = str_replace('{EVENTREGPAY}',event_regis_pay(),$content);
	}
	return $content;
}
function event_paypal_txn_insert($content) {
	if(preg_match('{EVENTPAYPALTXN}',$content)) {
		$content = str_replace('{EVENTPAYPALTXN}',event_paypal_txn(),$content);
	}
	return $content;
}
		
//Shortcode
// [SINGLEEVENT single_event_id="your event id"]
function show_single_event($atts) {
	extract(shortcode_atts(array('single_event_id' => 'No ID Supplied'), $atts));
	$single_event_id = "{$single_event_id}";
	register_attendees($single_event_id);
}
add_shortcode('SINGLEEVENT', 'show_single_event');

//Run the program
function event_regis_run() {
	global $wpdb;
	$events_attendee_tbl = get_option('events_attendee_tbl');
	$events_detail_tbl = get_option('events_detail_tbl');
	$events_organization_tbl = get_option('events_organization_tbl');
	$events_listing_type = get_option('events_listing_type');

	$sql  = "SELECT * FROM ". $events_organization_tbl ." WHERE id='1'";
	$result = mysql_query($sql);
   	while($row = mysql_fetch_assoc($result)) {
		$events_listing_type = $row['events_listing_type'];
		$event_page_id = $row['event_page_id'];
	}

	if($events_listing_type == "") {
		echo "<br /><br /><strong>Please setup Organization in the Admin Panel!<br /><br /></strong>";
	}
	if($events_listing_type == 'single') {
		if($_REQUEST['regevent_action'] == "post_attendee") {add_attendees_to_db();}
		else if($_REQUEST['regevent_action'] == "pay") {event_regis_pay();} //Linked to from confirmation email
		else if($_REQUEST['regevent_action'] == "register") {register_attendees();}
		else if($_REQUEST['regevent_action'] == "paypal_txn") {event_regis_paypal_txn();} //Runs the paypal transaction
		else if($regevent_action == "process") {}
		else {register_attendees();}
	}

	if($events_listing_type == 'all'){
		if ($_REQUEST['regevent_action'] == "post_attendee"){add_attendees_to_db();}
		else if ($_REQUEST['regevent_action'] == "pay"){event_regis_pay();}
		else if ($_REQUEST['regevent_action'] == "register"){register_attendees();}
		else if ($_REQUEST['regevent_action'] == "paypal_txn"){process_paypal_txn();}
		else if ($regevent_action == "process"){}
		else {display_all_events();}
	}
}




function register_attendees($single_event_id = "null"){
	
	global $wpdb;
			
	$paypal_cur = get_option('paypal_cur');
	$event_id = $_REQUEST['event_id'];
	
	$events_listing_type = get_option('events_listing_type');
			   
	$events_attendee_tbl = get_option('events_attendee_tbl');
	$events_detail_tbl = get_option('events_detail_tbl');
	$events_organization_tbl = get_option('events_organization_tbl');
	$events_listing_type = get_option('events_listing_type');

	if ($single_event_id != "null"){
		$single_event_id = $single_event_id;
		$sql  = "SELECT * FROM " . $events_detail_tbl . " WHERE event_identifier = '$single_event_id'";
				$result = mysql_query($sql);
				while ($row = mysql_fetch_assoc ($result))
					{
				$event_id = $row['id'];
					}
	}

	$sql  = "SELECT * FROM ". $events_organization_tbl ." WHERE id='1'";
	$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc ($result))
			{
				$events_listing_type =$row['events_listing_type'];
				$event_page_id =$row['event_page_id'];
			}

	//Query Database for Active event and get variable
	if ($events_listing_type == 'single'){$sql  = "SELECT * FROM " . $events_detail_tbl . " WHERE is_active='yes' AND start_date >= '".date ( 'Y-m-j' )."' ORDER BY date(start_date)";}
	else {$sql  = "SELECT * FROM " . $events_detail_tbl . " WHERE id = $event_id";}
	$result = mysql_query($sql);
	while ($row = mysql_fetch_assoc ($result))
		{
			$event_id = $row['id'];
			$event_name = $row['event_name'];
			$event_desc = $row['event_desc'];
			$display_desc = $row['display_desc'];
			$event_description = $row['event_desc'];
			$event_identifier = $row['event_identifier'];
			$event_cost = $row['event_cost'];
			$use_coupon_code = $row['use_coupon_code'];
			$active = $row['is_active'];
			$question1 = $row['question1'];
			$question2 = $row['question2'];
			$question3 = $row['question3'];
			$question4 = $row['question4'];
			$reg_limit = $row['reg_limit'];
			$allow_multiple = $row ['allow_multiple'];
			$start_month =$row['start_month'];
 			$start_day = $row['start_day'];
 			$start_year = $row['start_year'];
 			$end_month = $row['end_month'];
 			$end_day = $row['end_day'];
 			$end_year = $row['end_year'];
 			$start_time = $row['start_time'];
 			$end_time = $row['end_time'];
			$start_date = $start_month." ".$start_day.", ".$start_year;
			$end_date = $end_month." ".$end_day.", ".$end_year;
		}

	update_option("current_event", $event_name);

	//Query Database for Event Organization Info to email registrant BHC
	$events_organization_tbl = get_option('events_organization_tbl');
	$sql  = "SELECT * FROM ". $events_organization_tbl ." WHERE id='1'";
	$result = mysql_query($sql);

		while ($row = mysql_fetch_assoc ($result))
			{
	  			$org_id =$row['id'];
				$Organization =$row['organization'];
				$Organization_street1 =$row['organization_street1'];
				$Organization_street2=$row['organization_street2'];
				$Organization_city =$row['organization_city'];
				$Organization_state=$row['organization_state'];
				$Organization_zip =$row['organization_zip'];
				$contact =$row['contact_email'];
 				$registrar = $row['contact_email'];
				$paypal_id =$row['paypal_id'];
				$paypal_cur =$row['currency_format'];
				$events_listing_type =$row['events_listing_type'];
				$message =$row['message'];
			}
				
	/*//get attendee count	
	$events_attendee_tbl = get_option('events_attendee_tbl');
	$sql  = "SELECT * FROM " . $events_attendee_tbl . " WHERE event_id='$event_id'";
	$result = mysql_query($sql);
	$num_rows = mysql_num_rows($result);*/
	
	//get attendee count	
	$events_attendee_tbl = get_option ( 'events_attendee_tbl' );

	$sql= "SELECT SUM(quantity) FROM " . $events_attendee_tbl . " WHERE event_id='$event_id'";
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)){
		$num =  $row['SUM(quantity)'];
		};

	echo '<p><a href="javascript:history.back(-1)">&raquo; Go back</a></p>';

	echo '<div id="steps" style="margin:0 10px 10px"><span style="font-weight: bold; color: #E50278; font-size: 10pt">Step 1: Registration</span> | <span style="font-weight: normal; color: #999; font-size: 8pt">Step 2: Payment</span></div>';

	if ($reg_limit == "" or $reg_limit >= "$num") {
//		echo "<p><strong>".$event_name."</strong><br />Start Date: ".$start_date." - Start Time: ".$start_time."</p>";
		echo "<p><strong>".$event_name."</strong><br />".$start_date."</p>";
		if ($display_desc == "Y"){
			echo "<p><strong>Description:</strong><br />".htmlspecialchars_decode($event_desc)."</p>"; 
		}

		/*
		if ($event_cost != ""){			
			echo "<p><strong>Cost ".get_option('currency_symbol').$event_cost."</strong></p>";
		}
		*/

	//JavaScript for Registration Form Validation ?>
	<SCRIPT>
        function echeck(str) {
            var at="@"
            var dot="."
            var lat=str.indexOf(at)
            var lstr=str.length
            var ldot=str.indexOf(dot)
            if (str.indexOf(at)==-1){
                alert("Invalid E-mail ID")
                return false
            }
        
            if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
                alert("Invalid E-mail ID")
                return false
            }
        
            if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
                alert("Invalid E-mail ID")
                return false
            }
        
            if (str.indexOf(at,(lat+1))!=-1){
                alert("Invalid E-mail ID")
                return false
            }
        
            if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
                alert("Invalid E-mail ID")
                return false
            }
        
            if (str.indexOf(dot,(lat+2))==-1){
                alert("Invalid E-mail ID")
                return false
            }
                
            if (str.indexOf(" ")!=-1){
                alert("Invalid E-mail ID")
                return false
            }
        return true					
        }
    
        function validateForm(form) { 
    
            if (form.fname.value == "") { alert("Please enter your first name."); 
                form.fname.focus( ); 
                return false; 
             }
            if (form.lname.value == "") { alert("Please enter your last name."); 
                form.lname.focus( ); 
                return false; 
            }
            
            if ((form.email.value==null)||(form.email.value=="")){
                alert("Please Enter your Email address")
                form.email.focus()
                return false
            }
            if (echeck(form.email.value)==false){
                form.email.value=""
                form.email.focus()
                return false
            }
        
            if (form.email.value == "") { alert("Please enter your email address."); 
                form.email.focus( ); 
                return false; 
            }
        
            if (form.phone.value == "") { alert("Please enter your phone number."); 
                form.phone.focus( ); 
                return false; 
            }
            if (form.address.value == "") { alert("Please enter your address."); 
                form.address.focus( ); 
                return false; 
            }
            if (form.city.value == "") { alert("Please enter your city."); 
                form.city.focus( ); 
                return false; 
            }   
//            if (form.state.value == "") { alert("Please enter your state."); 
//                form.state.focus( ); 
//                return false; 
//            }
//            if (form.zip.value == "") { alert("Please enter your zip code."); //CIPRIAN - removed, no zippie in IE
//                form.zip.focus( ); 
//                return false; 
//            }
           
            function trim(s) {
                if (s) {
                return s.replace(/^\s*|\s*$/g,"");
            }
        return null;
        }
    
        //alert("your trying to submit");
            var inputs = $A(form.getElementsByTagName("input"));
            var msg = "";
            var radioChecks = $H();
            inputs.each( function(e) {
                var value = e.value ? trim(e.value) : null;
                if (e.type == "text" && e.title && !value && e.className == "r") {
                    msg += "\n " + e.title;
                }
                if ((e.type == "radio" || e.type == "checkbox") && e.className == "r") {
                    var name = e.name;
                    if (e.type == "checkbox") name = name.substr(0, name.lastIndexOf("-"));
                    if (e.checked == false && ((!radioChecks[name]) || (radioChecks[name] && radioChecks[name] != 1))) {
                        radioChecks[name] = e;
                    } else {
                        radioChecks[name] = 1;
                    }
                }
            });
            radioChecks.each( function(e) {
                if (typeof(e) == "object" && e.value != 1) {
                    msg += "\n " + e.value.title;
                }
            });
            if (msg.length > 0) {
                msg = "The following fields need to be completed before you can submit.\n\n" + msg;
                alert(msg);
                return false;
            }
            return true;     
       
        }
    </SCRIPT>

<?php
if($allow_multiple == "Y")
	echo '<p align="left"><strong><em>Prices: 1 person &euro;140, 2 people &euro;230, 3+ &euro;110 each</strong></em></p>';
if($allow_multiple == "N")
	echo '<p align="left"><strong><em>Prices: 1 person &euro;120, 2 people &euro;170</em></strong></p>';
?>


	<form method="post" action="<?php echo get_option('siteurl')?>/?page_id=<?php echo $event_page_id?>" onSubmit="return validateForm(this)">
        <p align="left"><strong>First Name:<br />
          <input tabIndex="1" maxLength="40" size="47" name="fname">
          </strong></p>
        <p align="left"><strong>Last Name:<br />
          <input tabIndex="2" maxLength="40" size="47" name="lname">
          </strong></p>
        <p align="left"><strong>Email:<br />
          <input tabIndex="3" maxLength="40" size="47" name="email">
          </strong></p>
        <p align="left"><strong>Phone:<br />
          <input tabIndex="4" maxLength="20" size="25" name="phone">
          </strong></p>
        <p align="left"><strong>Address:<br />
          <input tabIndex="5" maxLength="35" size="49" name="address">
          </strong></p>
        <p align="left"><strong>City:<br />
          <input tabIndex="6" maxLength="25" size="35" name="city">
          </strong></p>
<!--
        <p align="left"><strong>State:</strong><br />
          <select tabindex="7" name="state" size="1">
<option value="">Select Country</option> 
<option value="Ireland" selected="selected">Ireland</option> 
</select>
          </select>
        </p>
        <p align="left"><strong>Zip:<br />
          <input tabIndex="8" maxLength="10" size="15" name="zip">
          </strong></p>
-->
<?php 
		if ($event_cost != ""){ 
			if ($paypal_id != ""){ ?>
				<input type="hidden" name="payment" value="Paypal">
<?php 		}
		} else {
?>        <input type="hidden" name="payment" value="free event">
<?php 		}
			
//This is the Form
$events_question_tbl = get_option('events_question_tbl');
$questions = $wpdb->get_results("SELECT * from `$events_question_tbl` where event_id = '$event_id' order by sequence");
if($questions) {
	foreach($questions as $question) {
		echo "<p align='left'><strong>".$question->question."<br>";
		event_form_build($question);
		echo "</strong></p>";
	 }
 }
if($allow_multiple == "Y") { // Y = groups // ?>
	<p align="left">
		<strong>How many places do you want to book?</strong>
		<select name="num_people" style="width:70px;margin-top:4px">
			<option value="1" selected="selected">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
		</select>		
	</p>
<?php } if($allow_multiple == "N") { // N = couples // ?>
	<p align="left">
		<strong>How many places do you want to book?</strong>
		<select name="num_people" style="width:70px;margin-top:4px">
			<option value="1" selected="selected">1</option>
			<option value="2">2</option>
		</select>		
	</p>
<?php
}	
if ($use_coupon_code == "Y"){ ?>
	<p align="left"><strong>Do you have a promo code?<br />
		<input tabIndex="9" maxLength="25" size="35" name="coupon_code">
	</strong></p>
	<?php 	} ?>
        <input type="hidden" name="regevent_action" value="post_attendee">
        <input type="hidden" name="event_id" value="<?php echo $event_id;?>">
        <p align="center">
		<input type="submit" name="Submit" value="Go to step 2"><br />
		<font color="#FF0000"><strong>(Only click the button once)</strong></font>
		</form>
<?php 	}else {?>
			<p><font color="#FF0000"><strong>We are sorry but this event has reached the maximum number of attendees!</strong></font></p>
			<p><strong>Please check back in the event someone cancels.</strong></p>
			<p>Current Number of Attendees: <?php echo $num?></p>
<?php 		}
}//End function register_attendees()

function event_form_build(&$question, $answer="") {
	$required = '';
	if ($question->required == "Y") {
		$required = ' class="r"';
	}
	switch ($question->question_type) {
		case "TEXT":
			echo "<input type=\"text\"$required id=\"TEXT-$question->id\"  name=\"TEXT-$question->id\" size=\"40\" title=\"$question->question\" value=\"$answer\" />\n";
			break;

		case "TEXTAREA":
			echo "<textarea id=\"TEXTAREA-$question->id\"$required name=\"TEXTAREA-$question->id\" title=\"$question->question\" cols=\"30\" rows=\"5\">$answer</textarea>\n";
			break;

		case "SINGLE":
			$values = explode(",", $question->response);
			$answers = explode(",", $answer);

			foreach ($values as $key => $value) {
				$checked = in_array($value, $answers)? " checked=\"checked\"": "";
				echo "<label><input id=\"MULTIPLE-$question->id-$key\"$required name=\"SINGLE-$question->id\" title=\"$question->question\" type=\"radio\" value=\"$value\"$checked /> $value</label><br/>\n";
			}
			break;

		case "MULTIPLE":
			$values = explode(",", $question->response);
			$answers = explode(",", $answer);
			foreach ($values as $key => $value) {
				$checked = in_array($value, $answers)? " checked=\"checked\"": "";
				echo "<label><input type=\"checkbox\"$required id=\"MULTIPLE-$question->id-$key\" name=\"MULTIPLE-$question->id-$key\" title=\"$question->question\" value=\"$value\"$checked /> $value</label><br/>\n";
			}
			break;
			
		case "DROPDOWN":
			$values = explode(",", $question->response);
			$answers = $answer;
			echo "<select name=\"DROPDOWN-$question->id-$key\" id=\"DROPDOWN-$question->id-$key\" title=\"$question->question\" /><br>";
			foreach ($values as $key => $value) {
				$checked = in_array($value, $answers)? " selected =\" selected\"": "";
				echo "<option value=\"$value\" selected=\"$checked\" /> $value</option><br/>\n";
			}
			echo "</select>";
			break;	
			

		default:
			break;
	}
}


function add_attendees_to_db(){
			 global $wpdb;
			 $current_event = get_option('current_event');
			 $registrar = get_option('registrar');
			 $events_attendee_tbl = get_option('events_attendee_tbl');

			   $fname = $_POST['fname'];
			   $lname = $_POST['lname'];
			   $address = $_POST['address'];
			   $city = $_POST['city'];
			   $state = $_POST['state'];
			   $zip = $_POST['zip'];
			   $phone = $_POST['phone'];
			   $email = $_POST['email'];
			   $hear = $_POST['hear'];
			   $num_people = $_POST ['num_people'];
			   $event_id=$_POST['event_id'];
			   $payment = $_POST['payment'];
			   $custom_1 =$_POST['custom_1'];
			   $custom_2 =$_POST['custom_2'];
			   $custom_3 =$_POST['custom_3'];
			   $custom_4 =$_POST['custom_4'];
               update_option("attendee_first", $fname);
			   update_option("attendee_last", $lname);
			   update_option("attendee_name", $fname." ".$lname);
			   update_option("attendee_email", $email);
			   update_option("attendee_address", $address);
			   update_option("attendee_city", $city);
			   update_option("attendee_state", $state);
			   update_option("attendee_zip", $zip);
			   update_option("num_people", $num_people);
			


			$sql = "INSERT INTO ".$events_attendee_tbl." (lname ,fname ,address ,city ,state ,zip ,email ,phone ,hear, quantity, payment, event_id, custom_1, custom_2, custom_3, custom_4 ) VALUES ('$lname', '$fname', '$address', '$city', '$state', '$zip', '$email', '$phone', '$hear','$num_people', '$payment',  '$event_id', '$custom_1', '$custom_2', '$custom_3', '$custom4')"; 
			
			$wpdb->query($sql);
			
			// Insert Extra From Post Here
			$events_question_tbl = get_option('events_question_tbl');
			$events_answer_tbl = get_option('events_answer_tbl');
			$reg_id = $wpdb->get_var("SELECT LAST_INSERT_ID()");
			
				$questions = $wpdb->get_results("SELECT * from `$events_question_tbl` where event_id = '$event_id'");
				if ($questions) {
					foreach ($questions as $question) {
						switch ($question->question_type) {
							case "TEXT":
							case "TEXTAREA":
							case "SINGLE":
								$post_val = $_POST[$question->question_type . '-' . $question->id];
								$wpdb->query("INSERT into `$events_answer_tbl` (registration_id, question_id, answer)
									values ('$reg_id', '$question->id', '$post_val')");
								break;
							case "MULTIPLE":
								$values = explode(",", $question->response);
								$value_string = '';
								foreach ($values as $key => $value) {
									$post_val = $_POST[$question->question_type . '-' . $question->id . '-' . $key];
									if ($key > 0 && !empty($post_val))
										$value_string .= ',';
									$value_string .= $post_val;
								}
								$wpdb->query("INSERT into `$events_answer_tbl` (registration_id, question_id, answer)
									values ('$reg_id', '$question->id', '$value_string')");
								break;
						}
						
					}
				}		 
			
			
					//Query Database for Event Organization Info to email registrant
					$events_organization_tbl = get_option('events_organization_tbl');
					$sql  = "SELECT * FROM ". $events_organization_tbl ." WHERE id='1'";
						  // $sql  = "SELECT * FROM wp_events_organization WHERE id='1'"; 
						   
					$result = mysql_query($sql); 
					while ($row = mysql_fetch_assoc ($result)){
						$org_id =$row['id'];
						$Organization =$row['organization'];
						$Organization_street1 =$row['organization_street1'];
						$Organization_street2=$row['organization_street2'];
						$Organization_city =$row['organization_city'];
						$Organization_state=$row['organization_state'];
						$Organization_zip =$row['organization_zip'];
						$contact =$row['contact_email'];
						$registrar = $row['contact_email'];
						$paypal_id =$row['paypal_id'];
						$paypal_cur =$row['currency_format'];
						$return_url = $row['return_url'];
						$cancel_return = $row['cancel_return'];
						$notify_url = $row['notify_url'];
						$events_listing_type =$row['events_listing_type'];
						$default_mail=$row['default_mail'];
						$conf_message =$row['message'];
					}
			
					$events_detail_tbl = get_option('events_detail_tbl');
					
					
					$sql = "SELECT * FROM ". $events_detail_tbl ." WHERE id='".$event_id."'";
					$result = mysql_query($sql);
					while ($row = mysql_fetch_assoc ($result)){
						$event_name=$row['event_name'];
						$event_desc=$row['event_desc']; // BHC
						$display_desc=$row['display_desc'];
						$event_identifier=$row['event_identifier'];
						$reg_limit = $row['reg_limit'];
						$cost=$row['event_cost'];
						$start_month =$row['start_month'];
						$start_day = $row['start_day'];
						$start_year = $row['start_year'];
						$end_month = $row['end_month'];
						$end_day = $row['end_day'];
						$end_year = $row['end_year'];
						$start_time = $row['start_time'];
						$end_time = $row['end_time'];
						$active=$row['is_active'];
						$question1= $row['question1'];
						$question2= $row['question2'];
						$question3= $row['question3'];
						$question4= $row['question4'];
						$send_mail= $row['send_mail'];
						$conf_mail= $row['conf_mail'];
						$start_date = $start_month." ".$start_day.", ".$start_year;
						$end_date = $end_month." ".$end_day.", ".$end_year;
					}
			
						   
					// Email Confirmation to Registrar
			
						$event_name = $current_event;
			
						$distro=$registrar;
						$message=("$fname $lname has signed up on-line for $event_name.\n\nDate: $start_day.$start_month.$start_year\n\nEmail address is  $email.");
						
						wp_mail($distro, $event_name, $message); 
						
						//Email Confirmation to Attendee
						$query  = "SELECT * FROM $events_attendee_tbl WHERE fname='$fname' AND lname='$lname' AND email='$email'";
						$result = mysql_query($query) or die('Error : ' . mysql_error());
						while ($row = mysql_fetch_assoc ($result)){
								$id = $row['id'];
						}
			
					   
				$payment_link = get_option('siteurl') . "/?page_id=" . $return_url . "&id=".$id;
				
						//Email Confirmation to Attendee
				$SearchValues = array(
						"[fname]",
						"[lname]",
						"[phone]",
						"[event]",
						"[description]",
						"[cost]",
						"[qst1]",
						"[qst2]",
						"[qst3]",
						"[qst4]",
						"[contact]",
						"[company]",
						"[co_add1]",
						"[co_add2]",
						"[co_city]",
						"[co_state]",
						"[co_zip]",
						"[payment_url]",
						"[start_date]",
						"[start_time]",
						"[end_date]",
						"[end_time]");
						
				$ReplaceValues = array(
						$fname,
						$lname,
						$phone,
						$event_name,
						$event_desc,		
						$cost,
						$question1,
						$question2,
						$question3,
						$question4,
						$contact,
						$Organization,
						$Organization_street1,
						$Organization_street2,
						$Organization_city,
						$Organization_state,
						$Organization_zip,
						$payment_link,
						$start_date,
						$start_time,
						$end_date,
						$end_time);
				

			$custom = str_replace($SearchValues, $ReplaceValues, $conf_mail);
			$default_replaced = str_replace($SearchValues, $ReplaceValues, $conf_message);			
			
			$distro="$email";
			
			$headers = "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n"; 
			$message_top = "<html><body>"; 
			$message_bottom = "</html></body>";
			$email_body = $message_top.$custom.$message_bottom;
			//wp_mail($payer_email,$subject,$body,$headers);
						
			if ($default_mail =='Y'){ if($send_mail == 'Y'){ wp_mail($distro, $event_name, html_entity_decode($email_body), $headers);}}
			
			if ($default_mail =='Y'){ if($send_mail == 'N'){ wp_mail($distro, $event_name, $default_replaced);}}


		//Get registrars id from the data table and assign to a session variable for PayPal.

			$query  = "SELECT * FROM $events_attendee_tbl WHERE fname='$fname' AND lname='$lname' AND email='$email'";
	   		$result = mysql_query($query) or die('Error : ' . mysql_error());
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
				$amount_pd = $row['amount_pd'];
				$payment_date = $row['payment_date'];
				$event_id = $row['event_id'];
				$custom1 = $row['custom_1'];
				$custom2 = $row['custom_2'];
				$custom3 = $row['custom_3'];
				$custom4 = $row['custom_4'];
				}

			update_option("attendee_id", $id);

			//Send screen confirmation & forward to paypal if selected.
			
			if ($cost== '' || $cost== ' '){
				$event_message = '<p>This is a free event. Details have been sent to your email.</p>';
			}else{
				$event_message = '<p>Payment must be made to complete registration. Please click the button below to pay for your registration.</p>';
			}
?>
<div id="steps" style="margin:0 10px 10px"><span style="font-weight: normal; color: #999; font-size: 8pt">Step 1: Registration</span> | <span style="font-weight: bold; color: #E50278; font-size: 10pt">Step 2: Payment</span></div>

			<p>Your Registration data has been added to our records.</p>
            <?php echo $event_message?>
<?php			
			events_payment_page($event_id);}


function view_attendee_list(){
	//Displays attendee information from current active event.
	global $wpdb;
	$events_detail_tbl = get_option('events_detail_tbl');
	$events_attendee_tbl = get_option('events_attendee_tbl');


	$sql = "SELECT * FROM ". $events_detail_tbl . " WHERE is_active='yes'";
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

	echo "<table>";
	while ($row = mysql_fetch_assoc ($result)){
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
		$amount_pd = $row['amount_pd'];
		$payment_date = $row['payment_date'];
		$event_id = $row['event_id'];
		$custom1 = $row['custom_1'];
		$custom2 = $row['custom_2'];
		$custom3 = $row['custom_3'];
		$custom4 = $row['custom_4'];


		echo "<tr><td align='left'>".$lname.", ".$fname."</td><td>".$email."</td><td>".$phone."</td>";
		echo "<td>";
		echo "<form name='form' method='post' action='".$_SERVER['REQUEST_URI']."'>";
		echo "<input type='hidden' name='attendee_action' value='edit'>";
		echo "<input type='hidden' name='attendee_id' value='".$id."'>";
		echo "<input type='SUBMIT' value='EDIT'></form>";
		echo "</td></tr>";
	}
	echo "</table>";}

function event_process_payments(){
	global $wpdb;
	$events_detail_tbl = get_option('events_detail_tbl');		
	$sql = "SELECT * FROM ". $events_detail_tbl;
	echo "<p align='center'><p align='left'>SELECT EVENT TO ENTER ATTENDEE PAYMENTS:</p><table width = '400'>";
	$result = mysql_query($sql);
	while ($row = mysql_fetch_assoc ($result)){
		$event_id = $row['id'];
		$event_name=$row['event_name'];
	
		echo "<tr><td width='25'></td><td><form name='form' method='post' action='".$_SERVER['REQUEST_URI']."'>";
		echo "<input type='hidden' name='event_id' value='".$row['id']."'>";
		echo "<input type='hidden' name='attendee_pay' value='paynow'>";
		echo "<input type='SUBMIT' value='".$event_name."'></form></td><tr>";
	}
	echo "</table>";
	
	if ($_REQUEST['attendee_pay'] == "paynow"){
		enter_attendee_payments();
		list_attendee_payments();
	}
}//End function event_process_payments


function event_offline_booking(){ //CHIP
	offline_booking();

}//End function event_offline_booking


function attendee_display_edit(){
	edit_attendee_record();
	event_list_attendees();
}

//Export data to Excel file
if(isset($_REQUEST['export'])) {
	switch($_REQUEST['export']) {
		case 'report';
			global $wpdb;
			$id = $_REQUEST['id'];
			$today = date('Y-m-d_Hi',time());

			$events_answer_tbl = get_option('events_answer_tbl');
			$events_question_tbl = get_option('events_question_tbl');
			$events_detail_tbl = get_option('events_detail_tbl');
			$current_event = get_option('current_event');
			$events_attendee_tbl = get_option('events_attendee_tbl');
			$sql = "SELECT * FROM ".$events_detail_tbl." WHERE id='$id'";
			$result = mysql_query($sql);
			list($event_id, $event_name, $event_description, $event_identifier, $event_cost, $is_active) = mysql_fetch_array($result, MYSQL_NUM);

		switch($_REQUEST['action']) {
			case 'excel';
				$st = '';
				$et = "\t";
				$s = $et.$st;

				$basic_header = array('Reg ID', 'Last Name', 'First Name', 'Email', 'Address', 'City', 'State', 'Zip', 'Phone', 'Payment Method', 'Reg Date');
				$question_sequence = array();
				$questions = $wpdb->get_results("SELECT question, sequence FROM ".$events_question_tbl." WHERE event_id = '$event_id' ORDER BY sequence");
				foreach($questions as $question) {
					array_push($basic_header, $question->question);
					array_push($question_sequence, $question->sequence);
				}
				$participants = $wpdb->get_results("SELECT * FROM $events_attendee_tbl WHERE event_id = '$event_id'");
				$filename = $event_name.'-Attendees_'.$today.'.xls';

				header('Content-Type: application/ms-excel');
				header('Content-Disposition: attachment; filename="'.$filename.'"');
				header('Pragma: no-cache'); 
				header('Expires: 0');

				//echo header
				echo implode($s, $basic_header).$et."\r\n";

				//echo data
				if($participants) {
					foreach($participants as $participant) {
						echo $participant->id.$s.$participant->lname.$s.$participant->fname.$s.$participant->email.$s.$participant->address.$s.$participant->city.$s.$participant->state.$s.$participant->zip.$s.$participant->phone.$s.$participant->payment.$s.$participant->date;
						$answers = $wpdb->get_results("SELECT a.answer FROM ".$events_answer_tbl." a JOIN ".$events_question_tbl." q ON q.id = a.question_id WHERE registration_id = '$participant->id' ORDER BY q.sequence");
						foreach($answers as $answer) {
							echo $s.$answer->answer;
						}
						echo $et."\r\n";
					}
				}
				else {
					echo 'No participant data has been collected.';
				}
				exit;
			break;

			case 'payment';
				$st = '';
				$et = "\t";
				$s = $et.$st;

				$basic_header = array('Reg ID', 'Last Name', 'First Name', 'Email', 'Phone', 'Payment Method', 'Reg Date', 'Pay Status', 'Type of Payment', 'Transaction ID', 'Payment', 'Date Paid');
				$question_sequence = array();
				$participants = $wpdb->get_results("SELECT * FROM $events_attendee_tbl WHERE event_id = '$event_id'");
				$filename = $event_name.'-Payments_'.$today.'.xls';

				header('Content-Type: application/ms-excel');
				header('Content-Disposition: attachment; filename="'.$filename.'"');
				header('Pragma: no-cache'); 
				header('Expires: 0');

				//echo header
				echo implode($s, $basic_header).$et."\r\n";

				//echo data
				if($participants) {
					foreach($participants as $participant) {
						echo $participant->id.$s.$participant->lname.$s.$participant->fname.$s.$participant->email.$s.$participant->phone.$s.$participant->payment.$s.$participant->date.$s.$participant->payment_status.$s.$participant->txn_type.$s.$participant->txn_id.$s.$participant->amount_pd.$s.$participant->quantity.$s.$participant->payment_date;
						echo $et."\r\n";
					}
				}
				else {
					echo 'No participant data has been collected.';
				}
				exit;
			break;

			default:
				echo 'This is not a valid selection!';
			break;
		}
		default:
		break;
	}
}
?>
