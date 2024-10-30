=== PayPal Booking Framework ===
Contributors: butterflymedia
Tags: booking, course, class, event management, event registration, paypal event registration, paypal ipn, framework
Requires at least: 2.9.2
Tested up to: 3.1
Stable tag: 3.3.1

== Description ==

This plugin has been built to support courses and classes bookings. Payments (if available) are done via PayPal IPN. It sends the registrant to your PayPal payment page for online collection of event fees. Events are sorted by date and a short code is provided to display a single event on a page.

== Installation ==

Upload and activate the plugin, then read the instructions on the main page.

== Changelog ==

= 3.3.1 =
* FIX: Removed an extra form tag causing an error

= 3.3 =
* Added GPL 3 license file
* Added a missing database table declaration
* Removed a duplicate declaration in XLS export
* Removed several useless variables
* Removed all shorthand PHP tags
* Rewrote a WordPress function (better handled by the engine)
* Fixed a typo 
* Various maintenance actions

= 3.1.1b =
* Fixed a missing parenthesis
* Various user interface tweaks
* Added event management UI boxes with visibility toggling
* Implemented a form framework for ease of access to all functions

= 3.1b =
* XHTML validation
* Cleaned up widget code
* Starting implementing additional features and cleaning code up

= 3.0 =
* Added more features, removed PHP short tags
* Removed buttons styles and integrated WordPress standard styles

= 2.0 =
* Skipped

= 1.0 =
* A bit of clean-up, corrected typos and source formatting
* Replaces US states with countries

= 2.0.6	=
* Updated the coupon code system to use price discounts instead of redclaring the price of the event.
* Re-added the multiple registrants options.
* All PayPal IPN information is now being recorded to the database. Will add a reporting feature for that in a later release.
* Fixed the Excel export function to work when the plugin is installed in a Wordpress installation that is on not in the root directory.
* Fixed issue with past events still showing. When displaying all the events, the list will only show events that are in the future (start date is later than the current day)

= 2.0.5	=
* Changed database price fields to use decimal(7,2) instead of VARCHAR (45)
* Added a PayPal transactions table to allow for recording of all transaction information
* Fixed some date sorting issues in the query code

= 2.0.4	=
* Small fix that was causing an error on install

= 2.0.3	=
* Added a <br /> between Submit button and the "(Only click the Submit Button Once)" text

= 2.0.2	=
* Updated PayPal IPN Class to work with the Sandbox tools
* Added better support for international currencies

= 2.0.1	=
* Removed the PayPal "return_method" option. Changed the script to use the POST "return_method" in order for it to work with the new PayPal class.

= 2.0 working release =
* Added on page help documentation via AJAX popups and added a code editor for the events and email fields

= 1.6 Beta release =
* Fixed some bugs with storing data

= 1.4 =
* Added a widget to display your upcoming events in your sidebar
* Added shortcode functionality - [SINGLEEVENT single_event_id="Unique Event ID"]
* Added coupon code functionality
* Added ability to link to individual events
* Fixed a problem iwth currency codes outside of the USA
* Added ordering of the events and added a date dropdown function **Will make this a javascript date picker in a later version

= 1.3 =
* Fixed/removed some incorrect HTML tags and removed the leading 0 from the times

= 1.2 =
* Fixed the $_GET/$_POST feature to actually use both standards
