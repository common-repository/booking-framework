<?php 
//Events Liting - Shows the events on your page. Used with the {EVENTREGIS} tag
function display_all_events(){
				global $wpdb;
				$events_detail_tbl = get_option('events_detail_tbl');
				$events_attendee_tbl = get_option('events_attendee_tbl');
				$events_organization_tbl = get_option('events_organization_tbl');
				$curdate = date("Y-m-d");
				$paypal_cur = get_option('paypal_cur');
				$sql  = "SELECT * FROM ". $events_organization_tbl ." WHERE id='1'";
				$result = mysql_query($sql);
					while ($row = mysql_fetch_assoc ($result))
					{
						$event_page_id =$row['event_page_id'];
					}
				$sql = "SELECT * FROM ". $events_detail_tbl . " WHERE is_active = 'yes' AND start_date >= '".date ( 'Y-m-j' )."' ORDER BY date(start_date)";

					    $result = mysql_query ($sql);
					       		while ($row = mysql_fetch_assoc ($result))
					       		{
					       			    $event_id = $row['id'];
										$event_name=$row['event_name'];
					       			    $event_identifier=$row['event_identifier'];
					       			    $event_cost=$row['event_cost'];
					       			    $active=$row['is_active'];
										$start_month=$row['start_month'];
										$start_day=$row['start_day'];
										$start_year=$row['start_year'];
										$reg_limit=$row['reg_limit'];

										$sql2= "SELECT SUM(quantity) FROM " . $events_attendee_tbl . " WHERE event_id='$event_id'";
										$result2 = mysql_query($sql2);
								
										while($row = mysql_fetch_array($result2)){
											$num =  $row['SUM(quantity)'];
										}
										
										if ($reg_limit != ""){$available_spaces = $reg_limit - $num;}
										if ($reg_limit == ""){$available_spaces = "Unlimited";}
		
		?>
<div id="event-<?php echo $event_id?>"> 
<h3 class="event_title"><a href="<?php echo get_option('siteurl')?>/?page_id=<?php echo $event_page_id?>&regevent_action=register&event_id=<?php echo $event_id?>"><?php echo $event_name?></a></h3>
Date: <?php echo $start_month?> <?php echo $start_day?>, <?php echo $start_year?>
<br />
<?php if ($event_cost == '' || $event_cost == ' '){ ?>
Free Event
<?php }else{?>
Cost: <?php echo get_option('currency_symbol')?><?php echo $event_cost?>
<?php }?>
<br />
Spaces Available: <?php echo $available_spaces?><br />
<a style="font-size:14px;" href="<?php echo get_option('siteurl')?>/?page_id=<?php echo $event_page_id?>&regevent_action=register&event_id=<?php echo $event_id?>">Register Online</a></p>
</div>
<?
								}
	}
?>