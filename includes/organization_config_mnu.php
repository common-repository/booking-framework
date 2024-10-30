<?php
// Booking Framework Subpage 1 - Configure Organization
function organization_config_mnu() {
	global $wpdb;
	$events_attendee_tbl = get_option('events_attendee_tbl');
	$events_detail_tbl = get_option('events_detail_tbl');
    $events_organization_tbl = get_option('events_organization_tbl');

	if(isset($_POST['Submit'])) {
		$org_id			= $_POST['org_id'];
		$org_name		= $_POST['org_name'];
		$org_street1 	= $_POST['org_street1'];
		$org_street2 	= $_POST['org_street2'];
		$org_city		= $_POST['org_city'];
		$org_state		= $_POST['org_state'];
		$org_zip			= $_POST['org_zip'];
		$email			= $_POST['email'];
		$paypal_id		= $_POST['paypal_id'];
		$paypal_cur  	= $_POST['currency_format'];
		$event_page_id 	= $_POST['event_page_id'];
		$return_url 		= $_POST['return_url'];
		$cancel_return 	= $_POST['cancel_return'];
		$notify_url 		= $_POST['notify_url'];
		$use_sandbox 	= $_POST['use_sandbox'];
		$image_url 		= $_POST['image_url'];
		$events_listing_type = $_POST['events_listing_type'];
		$default_mail 	= $_POST['default_mail'];
		$payment_subject = htmlentities2($_POST['payment_subject']);
		$payment_message = htmlentities2($_POST['payment_message']);
		$message = htmlentities2($_POST['message']);

		$sql = "UPDATE ".$events_organization_tbl." SET organization='$org_name', organization_street1='$org_street1', organization_street2='$org_street2', organization_city='$org_city', organization_state='$org_state', organization_zip='$org_zip', contact_email='$email', paypal_id='$paypal_id', currency_format='$paypal_cur', events_listing_type='$events_listing_type', event_page_id = '$event_page_id', return_url = '$return_url', cancel_return = '$cancel_return', notify_url = '$notify_url', use_sandbox = '$use_sandbox', image_url = '$image_url', default_mail='$default_mail', payment_subject='$payment_subject', payment_message='$payment_message', message='$message' WHERE id ='1'";
		$wpdb->query($sql);

		// create option for PayPal ID
		$option_name = 'paypal_id';
		$newvalue = $paypal_id;
		if(get_option($option_name) != $newvalue) {
			update_option($option_name, $newvalue);
		}
		else {
			add_option($option_name, $newvalue);
		}
		$option_name = 'events_listing_type';
		$newvalue = $events_listing_type;
		if(get_option($option_name) != $newvalue) {
			update_option($option_name, $newvalue);
		}
		else {
			add_option($option_name, $newvalue);
		}
		$option_name = 'return_url';
		$newvalue = $return_url;
		if(get_option($option_name) != $newvalue) {
			update_option($option_name, $newvalue);
		}
		else {
			add_option($option_name, $newvalue);
		}  
		$option_name = 'event_page_id';
		$newvalue = $event_page_id;
		if(get_option($option_name) != $newvalue) {
			update_option($option_name, $newvalue);
		}
		else {
			add_option($option_name, $newvalue);
		}
		$option_name = 'cancel_return';
		$newvalue = $cancel_return;
		if(get_option($option_name) != $newvalue) {
			update_option($option_name, $newvalue);
		}
		else {
			add_option($option_name, $newvalue);
		}
		$option_name = 'notify_url';
		$newvalue = $notify_url;
		if(get_option($option_name) != $newvalue) {
			update_option($option_name, $newvalue);
		}
		else {
			add_option($option_name, $newvalue);
		}
		$option_name = 'use_sandbox';
		$newvalue = $use_sandbox;
		if(get_option($option_name) != $newvalue) {
			update_option($option_name, $newvalue);
		}
		else {
			$deprecated  =' ';
			$autoload = 'yes';
			add_option($option_name, $newvalue);
		}
		$option_name = 'image_url';
		$newvalue = $image_url;
		if(get_option($option_name) != $newvalue) {
			update_option($option_name, $newvalue);
		}
		else {
			add_option($option_name, $newvalue);
		}

		// create option for registrar
		$option_name = 'registrar';
		$newvalue = $email;
		if(get_option($option_name) != $newvalue) {
			update_option($option_name, $newvalue);
		}
		else {
			add_option($option_name, $newvalue);
		}
		$option_name = 'paypal_cur';
		$newvalue = $paypal_cur;
		if(get_option($option_name) != $newvalue) {
			update_option($option_name, $newvalue);
		}
		else {
			add_option($option_name, $newvalue);
		}

		switch($paypal_cur) {
			case 'USD':
			case 'HKD':
			case 'NZD':
			case 'SGD':
				$currency_symbol = '$';
			break;
			case 'AUD':
				$currency_symbol = 'A $';
			break;
			case 'GBP':
				$currency_symbol = '&pound;';
			break;
			case 'CAD':
				$currency_symbol = 'C $';
			break;
			case 'EUR':
				$currency_symbol = '&euro;';
			break;
			case 'JPY':
				$currency_symbol = '&yen;';
			break;
			default:
				$currency_symbol = '$';
			break;
		}

		$option_name = 'currency_symbol' ;
		$newvalue = $currency_symbol;
		if(get_option($option_name) != $newvalue) {
			update_option($option_name, $newvalue);
		}
		else {
			add_option($option_name, $newvalue);
		}
	}

	$sql = "SELECT * FROM ".$events_organization_tbl." WHERE id='1'";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)) {
		$org_id 				= $row['id'];
		$Organization 			= $row['organization'];
		$Organization_street1 	= $row['organization_street1'];
		$Organization_street2 	= $row['organization_street2'];
		$Organization_city 		= $row['organization_city'];
		$Organization_state 	= $row['organization_state'];
		$Organization_zip 		= $row['organization_zip'];
		$contact 				= $row['contact_email'];
		$registrar 				= $row['contact_email'];
		$paypal_id 				= $row['paypal_id'];
		$paypal_cur 			= $row['currency_format'];
		$event_page_id 			= $row['event_page_id'];
		$return_url 			= $row['return_url'];
		$cancel_return 			= $row['cancel_return'];
		$notify_url 			= $row['notify_url'];
		$use_sandbox 			= $row['use_sandbox'];
		$image_url 				= $row['image_url'];
		$events_listing_type 	= $row['events_listing_type'];
		$default_mail 			= $row['default_mail'];
		$payment_subject 		= $row['payment_subject'];
		$payment_message 		= $row['payment_message'];
		$message 				= $row['message'];
	}
?>

<div id="configure_organization_form" class="wrap">
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
		<div id="icon-options-general" class="icon32"><br /></div>
		<h2>General Settings</h2>
		<p>Use this page to set up and configure your organization information and your PayPal options.</p>
	    <h3>General Information</h3>
		<ul>
			<li>
				<input name="org_name" id="org_name" size="30" value="<?php echo $Organization;?>" /> <label for="org_name"><span class="description">Organization name <strong>*</strong></span></label>
			</li>
			<li>
				<input name="org_street1" id="org_street1" size="30" value="<?php echo $Organization_street1;?>" /> <label for="org_street1"><span class="description">Organization street 1 <strong>*</strong></span></label>
			</li>
			<li>
				<input name="org_street2" id="org_street2" size="30" value="<?php echo $Organization_street2?>"> <label for="org_street2"><span class="description">Organization street 2</span></label>
			</li>
			<li>
				<input name="org_city" id="org_city" size="30" value="<?php echo $Organization_city?>"> <label for="org_city"><span class="description">Organization city <strong>*</strong></span></label>
			</li>
			<li>
				<input name="org_state" id="org_state" size="3" value="<?php echo $Organization_state?>"> <label for="org_state"><span class="description">Organization state/Country code</span></label>
			</li>
			<li>
				<input name="org_zip" id="org_zip" size="10" value="<?php echo $Organization_zip?>"> <label for="org_zip"><span class="description">Organization zip code</span></label>
			</li>
			<li>
				<input name="email" id="email" size="30" value="<?php echo $contact?>"> <label for="email"><span class="description">Primary contact email <strong>*</strong></span></label>
			</li>
		</ul>
		<p><em>All fields marked by <strong>*</strong> are mandatory for a correct functionality.</em></p>
		<h3>Main Events Page</h3>
		<p>
			<label for="event_page_id">Main registration page: </label>
			<select name="event_page_id">
				<option value="0">Main page</option>
				<?php parent_dropdown($default = $event_page_id);?>
			</select>
			<br />
			<span class="description"><small>This is the page that displays your events. This page should contain the <strong>{EVENTREGIS}</strong> shortcode.</small></span>
		</p>
		<p>
			<label for="events_listing_type">Do you want to show a single event or all events on the registration page? <strong>*</strong> </label>
			<select name="events_listing_type">
				<option value="<?php echo $events_listing_type;?>"><?php echo $events_listing_type;?></option>
				<option value="single">Single event</option>
				<option value="all">All events</option>
			</select>
			<br />
			<span class="description"><small>If set to &quot;Single event&quot;, only one event will be displayed on the page.</small></span><br />
			<span class="description red_text"><small><strong>Note:</strong> Setting this option to &quot;Single event&quot; will disable the &quot;Booking Framework Widget&quot; and {SINGLEEVENT} shortcode functionality.</small></span>
		</p>
		<div id="icon-options-general" class="icon32"><br /></div>
		<h2>PayPal Settings</h2>
		<h3>PayPal Email</h3>
		<p>
			<input name="paypal_id" id="paypal_id" size="30" value="<?php echo $paypal_id?>"> <label for="paypal_id"><span class="description">Your PayPal email address. Leave blank if you do not want to accept PayPal.</span></label>
		</p>
		<h3>PayPal Currency</h3>
		<p>
			<select name="currency_format" id="currency_format">
				<option value="<?php echo $paypal_cur;?>"><?php echo $paypal_cur;?></option>
				<option value="USD">U.S. Dollars ($)</option>
				<option value="AUD">Australian Dollars (A $)</option>
				<option value="GBP">Pounds Sterling (&pound;)</option>
				<option value="CAD">Canadian Dollars (C $)</option>
				<option value="CZK">Czech Koruna</option>
				<option value="DKK">Danish Krone</option>
				<option value="EUR">Euros (&euro;)</option>
				<option value="HKD">Hong Kong Dollar ($)</option>
				<option value="HUF">Hungarian Forint</option>
				<option value="ILS">Israeli Shekel</option>
				<option value="JPY">Yen (&yen;)</option>
				<option value="MXN">Mexican Peso</option>
				<option value="NZD">New Zealand Dollar ($)</option>
				<option value="NOK">Norwegian Krone</option>
				<option value="PLN">Polish Zloty</option>
				<option value="SEK">Swedish Krona</option>
				<option value="BRL">Brazilian Real (only for Brazilian users)</option>
			</select> 
			<label for="currency_format"><a href="https://www.paypal.com/cgi-bin/webscr?cmd=p/sell/mc/mc_receive-outside||https://www.paypal.com/cgi-bin/webscr?cmd=p/sell/mc/mc_convert-outside||https://www.paypal.com/cgi-bin/webscr?cmd=p/sell/mc/mc_wa-outside" title="Receiving Money||Currency Conversion||Website Payments" target="_blank" rel="external">PayPal currency</a> for your country</label><br />
			<span class="description"><small>You can accept payments on your website in any of the currencies supported by PayPal through the Multiple Currencies feature.</small></span>
		</p>
		<h3>Custom PayPal Settings</h3>
		<p>
			<input name="image_url" id="image_url" size="30" value="<?php echo $image_url;?>" /> <label for="image_url">Image URL (used for your personal logo on the PayPal page)</label><br />
			<span class="description"><small>The URL of the 150x50-pixel image displayed as your logo in the upper left corner of the PayPal checkout pages.</small></span><br />
			<span class="description"><small>Default - Your business name, if you have a Business account, or your email address, if you have Premier or Personal account.</small></span>
		</p>
		<p>
			<select name="return_url" id="return_url">
				<option value="0">Main page</option>
				<?php parent_dropdown($default = $return_url);?>
			</select> 
			<label for="return_url"><a href="https://www.paypal.com/cgi-bin/webscr?cmd=p/mer/express_return_summary-outside" title="Auto Return Overview" target="_blank">Auto Return URL</a> (used for return to make payments)</label><br />
			<span class="description"><small>The URL to which the payer's browser is redirected after completing the payment. For example, a URL on your site that displays a &quot;Thank you for your payment&quot; page.</small></span><br />
			<span class="description"><small>This page should contain the <strong>{EVENTREGPAY}</strong> shortcode.</small></span><br />
			<span class="description red_text"><small><strong>Note:</strong> This page should be hidden from your navigation menu.</small></span>
		</p>
		<p>
			<select name="cancel_return" id="cancel_return">
				<option value="0">Main page</option>
				<?php parent_dropdown($default = $cancel_return);?>
			</select> 
			<label for="cancel_return"><span class="description">Cancel Return URL (used for cancelled payments)</span></label><br />
			<span class="description"><small>A URL to which the payer's browser is redirected if payment is cancelled. For example, a URL on your website that displays a &quot;Payment Cancelled&quot; page.</small></span><br />
			<span class="description"><small>This should be a page on your website that contains a cancelled message. No short tags are needed.</small></span><br />
			<span class="description red_text"><small><strong>Note:</strong> This page should be hidden from your navigation menu.</small></span>
		</p>
		<p>
			<select name="notify_url" id="notify_url">
				<option value="0">Main page</option>
				<?php parent_dropdown($default = $notify_url);?>
			</select> 
			<label for="notify_url">Notify URL (used to process payments)</label><br />
			<span class="description"><small>The URL to which PayPal posts information about the transaction, in the form of Instant Payment Notification messages.</small></span><br />
			<span class="description"><small>This page should contain the <strong>{EVENTPAYPALTXN}</strong> shortcode.</small></span><br />
			<span class="description red_text"><small><strong>Note:</strong> This page should be hidden from your navigation menu.</small></span>
		</p>
		<h3>Debugging/Testing</h3>
		<p>
			<label for="use_sandbox">Use the debugging feature and the <a href="https://developer.paypal.com/devscr?cmd=_home||https://cms.paypal.com/us/cgi-bin/?&amp;cmd=_render-content&amp;content_ID=developer/howto_testing_sandbox||https://cms.paypal.com/us/cgi-bin/?&amp;cmd=_render-content&amp;content_ID=developer/howto_testing_sandbox_get_started" title="PayPal Sandbox Login||Sandbox Tutorial||Getting Started with PayPal Sandbox" target="_blank">PayPal Sandbox</a>? </label>
			<?php
			if($use_sandbox == '1') {
				echo '<input name="use_sandbox" id="use_sandbox" type="checkbox" value="1" checked="checked" />';
			}
			else {
				echo '<input name="use_sandbox" id="use_sandbox" type="checkbox" value="1" />';
			}
			?><br />
			<span class="description"><small>In addition to using the PayPal Sandbox fetaure. The debugging feature will also output the form variables to the payment page, send an email to the admin that contains the all PayPal variables.</small></span><br />
			<span class="description"><small>The PayPal Sandbox is a testing environment that is a duplicate of the live PayPal site, except that no real money changes hands. The Sandbox allows you to test your entire integration before submitting transactions to the live PayPal environment. Create and manage test accounts, and view emails and API credentials for those test accounts.</small></span>
		</p>
		<div id="icon-options-general" class="icon32"><br /></div>
		<h2>Email Settings</h2>
		<p>
			Do you want to send payment confirmation emails?
			<?php
			if($default_mail == '') {
				echo '<input type="radio" name="default_mail" id="default_mail_y" value="Y" /> <label for="default_mail_y">Yes</label>';
				echo '<input type="radio" name="default_mail" id="default_mail_n" value="N" /> <label for="default_mail_n">No</label>';
			}
			if($default_mail == 'Y') {
				echo '<input type="radio" name="default_mail" id="default_mail_y" checked="checked" value="Y" /> <label for="default_mail_y">Yes</label>';
				echo '<input type="radio" name="default_mail" id="default_mail_n" value="N" /> <label for="default_mail_n">No</label>';
			}
			if ($default_mail =="N"){
				echo '<input type="radio" name="default_mail" id="default_mail_y" value="Y" /> <label for="default_mail_y">Yes</label>';
				echo '<input type="radio" name="default_mail" id="default_mail_n" checked="checked" value="N" /> <label for="default_mail_n">No</label>';
			}
			?><br />
			<span class="description"><small>(This option must be enabled to send custom mails in events)</small></span>
		</p>
		<h3>Payment Confirmation Email</h3>
		<p><label for="payment_subject">Email subject:</label><br /><input name="payment_subject" id="payment_subject" type="text" value="<?php echo $payment_subject;?>" /></p>
		<p><label for="payment_message">Email body:</label><br /><textarea rows="5" cols="125" name="payment_message" id="payment_message" class="my_ed"><?php echo $payment_message;?></textarea><br /><script type="text/javascript">myEdToolbar('payment_message');</script></p>
		<h3>Default Registration Confirmation Email</h3>
		<p><label for="success_message">Email body:<br /><textarea rows="5" cols="125" name="message" id="success_message" class="my_ed"><?php echo $message?></textarea><br /><script type="text/javascript">myEdToolbar('success_message');</script></p>
		<p>
			<input type="hidden" value="<?php echo $org_id;?>" name="org_id" />
			<input type="hidden" name="update_org" value="update" />
			<input class="button-primary" type="submit" name="Submit" value="Save Options" id="save_organization_setting" />
		</p>
	</form>
</div>
<?php }?>
