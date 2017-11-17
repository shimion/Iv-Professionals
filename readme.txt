=== Ultimate Appointment Scheduling ===
Contributors: Rustaurius, EtoileWebDesign
Tags: appointment booking, appointment scheduling, meeting booking, booking, consultation, dates, session, quote, woocommerce appointments, scheduling, woocommerce services
Requires at least: 3.9
Tested up to: 4.9
License: GPLv3
License URI:http://www.gnu.org/licenses/gpl-3.0.html

A plugin that lets you set up locations, service, availability, etc. and book appointments through your website


== Description == 

Set up scheduling for customers through your website. Set up locations, services, providers for those services and let your clients start booking their appointments online today! Great for businesses that need to set up one-on-one or one-to-many services, such as exercise classes or corporate training sessions. Also works for scheduling phone calls, if your business calls to give free quotes, an overview of your services, etc.

`
[ultimate-appointment-calendar]
[ultimate-appointment-dropdown]
`

Simply insert either of the shortcodes above to into any page to display an appointment scheduling form.

Plugin is still in beta, so please report any bugs you might find in the support forum! Big update coming in March!

= Key Features =

* Create locations with different opening hours
* Accept mandatory or optional payments before appointments via PayPal
* Create services that cost different amounts and take different amounts of time
* Dynamically updated appointment schedules, so that it's impossible to double book
* Labelling and styling options to fit perfectly with your business and website
* Options to set a minimum and maximum number of days before an appointment that a service can be booked
* Set the amount of time between appointments
* Send automatic emails to clients, the site admin and/or the service provider when an appointment is successfully created
* Set up automated reminder emails that will go out to your clients a certain number of days or hours before their appointments
* Require appointment confirmation
* Require login to WordPress, Front-End Only Users, Facebook or Twitter before being a to create an appointment, to prevent spam

= Shortcodes =

* [ultimate-appointment-calendar]: display a calendar that with available appointment times that users can click to select an appointment
* [ultimate-appointment-dropdown]: display a set of dropdown menus to find appointment times and schedule an appointment

= Translations =

* German (Thanks to <a href='http://wordpress.org/support/profile/bkleine'>bkleine</a>)


--------------------------------------------------------------

== Frequently Asked Questions ==

= How do I get an appointment scheduler to show up on my page? =

Try adding the shortcode [ultimate-appointment-dropdown] to whatever page you'd like to display it on. 

= What are the current UASP shortcodes? =

* [ultimate-appointment-dropdown]: display a set of dropdown menus to find appointment times and schedule an appointment

== Screenshots ==

1. Sample appointment scheduling start page
2. Sample appointment scheduling appointment selected
3. The "Locations" admin tab
4. The "Services" admin tab
5. The "Service Providers" admin tab
6. The "Options" admin tab

== Changelog ==
= 0.26 =
- For sites where payment is set to "Required", appointments that aren't paid within the next 20 minutes will be automatically deleted
- Fixed an error where PayPal payments weren't working on some sites

= 0.25 =
- Fixed an error where some styling options weren't saving or weren't being applied

= 0.24 =
- Added nonces to a few forms that were missing them
- Fixed a number of deleting/saving errors

= 0.23 =
- Added in option to set the default location, service, service provider and date via URL parameters
- Added in 2 new labelling options

= 0.22 =
- Added in an optional captcha field to the tracking form

= 0.21 =
- HUGE update, so please be careful updating if you use the plugin on a live site
- Added in a new shortcode, 'ultimate-appointment-calendar', which displays a calendar with free appointment times that visitors can select by clicking
- Added in WooCommerce integration as a payment option, where users will be taken to the WC checkout page to pay after selecting their appointment
- Added in an 'Emails' tab on the 'Settings' page, where you can now customize all of the emails sent out by the plugin (when appointments are booked, for admins, appointment reminders)
- Improved the look of the email sent out, allowing the use of section breaks, appointment information and buttons with links attached to them
- Fixed a number of small bugs

= 0.20 =
- Fixed an error with client and service provider emails not being sent out
- Fixed a typo on the options page

= 0.19 =
- Updated the text domain of the plugin, to use the improved WordPress standard

= 0.18 =
- Fixed an error where exceptions couldn't be created

= 0.17 =
- Fixed an error where some new appointments weren't being saved

= 0.16 =
- Added in an appointment's details to the reminder emails

= 0.15 =
- Added in an "edit-appointment" shortcode
- Added options to specify the minimum and maximum numbers of days in advance an appointment can be scheduled
- You can now click anywhere in the appointment date box to bring up the date selector
- Clarified the "Service Provider" hour field instructions in the back-end
- Added an attribute, redirect_page, to specify a page to redirect visitors to after they successfully book an appointment

= 0.14 =
- Replaced "Client" with the client's name in client notification emails
- Fixed an error where appointments with no scheduled time could still be created in the back-end

= 0.13 =
- Added an option to require certain information on the sign up form
- Appointment sign up form can no longer be submitted without a time being selected
- Added an option to specify how far apart the appointments should be (in minutes) on the sign up form
- Fixed an error where the "Set Access Role" option was disabled

= 0.12 =
- Added an option to send an appointment email to a client on sign up
- Added an option to send a notification email to the service provider on sign up
- Added an option to set what user role should have access to the "Appointments" menu

= 0.11 =
- Fixed a problem where service providers would show up for locations where they weren't working

= 0.10 =
- Fixed a couple of errors, including one where service providers weren't updated based on the service selected

= 0.9 =
- Added in an option, "Login Required", that forces visitors to log in through one of the selected services before being able to schedule an appointment
- Added in the login options necessary for the "Login Required" option to work smoothly
- Fixed an error where service providers couldn't be edited after they were created

= 0.8 =
- Fixed a bug where the "Services" dropdown wasn't activiated until there was more than 1 location

= 0.7 =
- Some small CSS changes

= 0.6 =
- Big overhaul of the CSS to display the appointment scheduler, so that there's no text overlap any more
- Added in a number of labelling and styling options, to make it easier to customize the plugin

= 0.5 =
- Fixed a couple of UI elements in the back-end, to make it easier to create appointments from the admin interface

= 0.4 =
- Small visual fixes, let us know in the support forum if there are other features you'd like to see or if there are features that aren't working for you!

= 0.3 =
- Added a tonne of new features and fixed many little errors including:
- PayPal integration so that appointments can be paid for online
- Email reminders and appointment confirmation
- Fixed bugs with creating exceptions, editing appointments and more

= 0.2 =
- Big styling update so that the dropdowns look much more acceptable

= 0.1 =
- Initial beta version. Please make comments/suggestions in the "Support" forum.

== Upgrade Notice ==

