=== DB Toolkit ===
Contributors: Desertsnowman
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=PA8U68HRBXTEU&lc=ZA&item_name=dbtoolkit%20development&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted
Tags: interface, database, tables, database tables, application development, app engine, database interface toolkit, DBT0309821
Requires at least: 3.1
Tested up to: 3.5.2
Stable tag: 0.3.3

Build additional Content Management Structures and Database Applications right into your WordPress Site.

== Description ==

DB-Toolkit is a plugin that enables you to build additional Content Management Structures and Database Applications right into your website.

You can build Capture Forms, Reports, your own plugins, Image Galleries, Sliders, Databases, staff management, Hotel Booking systems... Any Data based application.

By defining the kinds of data you are wanting to work with, you can create an almost endless range of content, manageable from its own interfaces and screens. Controlled by both backend (administrators) and frontend (public users) interfaces and forms.

DB-Toolkit is not a simple plugin and has a steep learning curve, but the results are very rewarding.

There will be a support area coming soon. I'll update with a URL soon.


Some Features

* Field-by-field data type handling makes data management very powerful and flexible.
* Data exporting in PDF and CSV.
* Create API's to connect to your data. This allows you to build mobile apps that feed from your content.
* Multi Interface Layouts using clusters.
* Build custom content managers, like galleries, contact lists, application forms, employee databases... and so on.
* Import data from an XML or CSV source
* Visually build forms that capture data to a database existing or not.

== Installation ==

1. Upload the plugin folder 'db-toolkit' to your `/wp-content/plugins/` folder
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Select DB Toolkit from the menu group

== Frequently Asked Questions ==

Q: Is there any Documentation?
A: Nope. I am working on it.

Q: When will you have an RC release?
A: Well you can technically use it now, however some things are still a little iffy (like cloned linking links) But it should mostly work on up to 3rd level interfaces.


== Screenshots ==

1. Build Database Management Interfaces and Viewers.
2. Interface and Application Management Screen.
3. An Interface built in DB-Toolkit to manage data.
4. Interface Config Screen (Lots of Options!).

== Changelog ==

= 0.3.3 =
* Mostly a maintenance update.
* Fixed many bugs that made it break in 3.5.1
* Made some improvements to have version 0.4 (Some uge changes in that one)

= 0.3.2.026 =
* Fixed a bug in the file fieldtype that echoed out the file field title.
* Updated jQuery UI to use internal version
* Fixed a few display issues for WordPress 3.5

= 0.3.2.025 =
* Fixed a bug in the linked tables. multi select should now work. If you get an error, add a new entry to force reload.
* Front end pagination fixed.
* File fieldtype now has multiple output types.
* File fieldtype now allows for Amazon S3 uploading (beta).
* Image fieldtype no displays correctly. (may still have problems but we'll see how it goes.)


= 0.3.2.024 =
* Fixed a bug that prevented a form from capturing a new entry if the return value is set as a get. Ye I know- strange right?
* Fixed the loading icon on the form load dialog. Finally.
* Made the overlay for dialogs black. looks way better.
* moved the Close button away from the save and changed it to Cancel.

= 0.3.2.023 =
* fixed a bug in the filtered join that killed it.
* Added div wrapper default in the template tab.
* Fixed the prev and next arrows in template mode.

= 0.3.2.022 =
* fixed a bug that prevented filters and sorting from working on occasion.

= 0.3.2.021 =
* fixed an error when updating that merged the math and file fieldtypes. no ide what what went wrong.

= 0.3.2.020 =
* Bug fixes an preparations for a special future update.
* Added in a Star Rating fieldtype.

= 0.3.2.019 =
* Fixed a bug that prevented the dynamic add of join table fields.
* Finally added Checkbox type joins (still beta and experimental though).

= 0.3.2.018 =
* Fixed a few small bugs that where annoying me.
* Added a new tab to edit interface to allow for custom WHERE statements in the final query.

= 0.3.2.017 =
* Fixed a bug that broke an interface ig you tried to group a virtual field that is set as a join. (ye its complicated.)
* Fixed a bug that made template mode duplicate the custom scripts in the output buffer.

= 0.3.2.016 =
* Fixed a major bug that ran a blank query 4 times for every entry in template mode. (nasty I know, so sorry) resulting in huge wast of resource time.
* Allowed items to hold a 0 as a value.
* removed legacy code from view output that added compatibility for version 0.1. so no upgrading from v0.1 to 0.3 will no longer have working templates.

= 0.3.2.015 =
* Fixed a bug that prevented custom scripts to reload.
* Fixed a bug that broke the export buttons from working in frontend.
* Fixed a bug that broke the redirect pass back from formmode to list mode.
* Added an = and != selector to the join table fieldtype WHERE filter.
* Added in filter targeting and redirect.

= 0.3.2.014 =
* Added title as placeholder to the fieldsetup box (only textfields are supported).
* Added the ability to set the Selected Filter Item to optional. This allows you to render the interface without the value but filter if its present.
* Fixed a bug in the PDF export that removed the first entry.
* Fixed a concat bug with linked fields not spacing the additional fields correctly.

= 0.3.2.013 =
* Bug fixes all over the place. mostly minor though.

= 0.3.2.011 =
* Fixed a bug in the image field that would give you an image does not exist notice when the file does exist.
* Added in another level of validation for interfaces in form mode.

= 0.3.2.010 =
* Fixed a bug in the image field that would give you an image does not exist notice when the file does exist.
* fixed a type that prevented activation on some configurations. thanks to regex on the forum for finding this. sigh!

= 0.3.2.009 =
* Fixed a bug that made a selected item filtered list lose its selected item filter when an item is deleted from the list. yes it was hard to say.

= 0.3.2.008 =
* Fixed a bug that prevented un binding an interface to a page.

= 0.3.2.007 =
* Fixed the bug I caused by fixing a bug with redirects. long day indeed.

= 0.3.2.006 =
* Allowed the redirect to work on sub page interface includes. (don't ask its complicated);

= 0.3.2.005 =
* Missed the if docked redirect option. Fixed now!

= 0.3.2.004 =
* Fixed a bug that made redirects on dashboard items fail.
* Fixed a bug that gave permission denied messages on landing pages that are in the menu app menu items.

= 0.3.2.003 =
* Small update to fix a bug with the selected item filter that prevented the capturing of the value

= 0.3.2.002 =
* Changed the form layout to use PHP-Scaffold and applied Twitters Bootstrap form and layout classes for improved forms.
* Changed the formlayout builder to allow sorting of rows.
* Added a form field width setting in the fieldtype config (little gear icon to the right on the field panel).
* Added an HTML form element (still playing with it to make it easier to use).
* Added a numerical fieldtype for capturing numbers. the filter is a ranged filter.
* Removed inline styles from the toolbar and filters panel for better custom styling.
* Removed uniform styles in favour of twitters bootstrap as a permanent addition.
* Seperated admin javascript functions from frontend inclusion.
* Added Use Ajax filters for faster searching in interfaces.
* Added Toolbar Templates finally.
* Removed TimThumb completely in favour of the built in resizing features.
* Uploaded images are now resized and saved to the server as actual image files.
* Uploaded images now retain their original file name (sanitized).
* Some bug fixes.
* NOTE: Some forms using the form layout may need to be reset. With this update some thing may work differently. please post to support forums if you have any problems.


= 0.3.1.031 =
* Set the interface to reset its state on save, this should solve the problems with sessions when changing the sort.
* Added the current url query to be passed back to the ajax form submissions and refreshes. This will improve the ajax interactions and maintain filtered states.
* Altered the CSS a little on the toolbar. This is so that it will be easier to customise the styling for front end.
* Altered the custom CSS loader only to load the themes toolbar, forms and table css on front end and not backend. looked messy!

= 0.3.1.030 =
* Minor bug fixes that would otherwise be not noticed.

= 0.3.1.029 =
* Really fixed the encoding to utf-8 for API output on json.

= 0.3.1.028 =
* Added a new FieldType - Tagging! pretty cool but is beta, please tell me what you think and what changes if you need any.
* Added sort field to the join table fieldtype - you can now specify the field and direction to sort the joined table results by.
* Fixed encoding to utf-8 for API output on both json and xml.
* Fixed the on/off toggle bug that allowed you to toggle entries when editing is disabled
* Added the ability to add custom CSS classes to list table, toolbar, filterbar and filter button bar for better customization.

= 0.3.1.027 =
* Added form processing to the toggle fieldtype. Data changed will new be processed using normal form processors.

= 0.3.1.026 =
* Fixed alignments on the toggle fieldtypes.

= 0.3.1.025 =
* Adjusted the output scripts from some fieldtypes to only post if being used.

= 0.3.1.024 =
* Made uniform an option in General Settings as it was causing problems with older installations.
* Fixed some minor javascript bugs

= 0.3.1.023 =
* Did some form updates. All forms are now styled with uniform to make it look better.

= 0.3.1.021 =
* Fixed a bug that prevented naming a field 'order' and setting it as the sort field.

= 0.3.1.020 =
* Fixed a bug that prevented the use of databases with a dash - the database name.

= 0.3.1.019 =
* Fixed a queryfilter problem with enum fieldtypes.

= 0.3.1.018 =
* Fixed the search method in API mode.
* made preparations for a new table selector.

= 0.3.1.017 =
* Performance update for ignored field. (It actually ignores the field now ).

= 0.3.1.016 =
* Bugfixes around the way custom scripts are loaded. moved them to a per page area instead for better stability.
* additional Nav fixes
* additional toolbar fixes
* fixed a bug that prevented the removal of a custom shortcode

= 0.3.1.015 =
* Bugfixes, url mapping corrected.
* Added in Page binding!

= 0.3.1.010 =
* Fixed a small error notification bug in menu list.

= 0.3.1.009 =
* Fixed the admin bar "access denied" bug.

= 0.3.1.008 =
* disabled ajax uploading on non-ajax forms. was causing problems. Will be making a more cleaner fix soon.

= 0.3.1.007 =
* Added permissions setting to interface list so you can see each interfaces permission without needing to load it.
* Fixed some bugs that was causing annoying issues with form rendering.

= 0.3.1.006 =
* Fixed a bug that cause a header error when there are no widgets that contain interfaces.

= 0.3.1.005 =
* Added in the WordPress User Registration form processor. (beta)

= 0.3.1.004 =
* fixed a security problem that could allow a user to edit an entry belonging to someone else.
* Fixed a bug on edit forms that broke international characters.

= 0.3.1.003 =
* fixed a bug with filters not showing on some fields.

= 0.3.1.000 =
* fixed a bug with the close filter toggle dying off in conflict to another plugin.

= 0.3.1.000 =
* Major Update with the fieldtypes that solved a lot of internal problems.
* Internal Style Update to better suit WordPress 3.3
* Lots of bugfixes
* Made changes for something BIG!

= 0.3.0.148 =
* Fixed a bug with the custom shortcode not accepting additional arguments.

= 0.3.0.146 =
* Bug Fixes!

= 0.3.0.146 =
* Changed List template tab to "Templates".
* Moved "Use List Template" to within the list template tab".
* Set Field Templates to always on. So now they will wrap fields in both list view and template view".

= 0.3.0.145 =
* Fixed a bug that prevented dialogs in the wysiwyg from from receiving text inputs.
* Fixed a bug with the SELECT fieldType from working in checkbox mode.

= 0.3.0.143 =
* Fixed a clash with another plugin that made the filters button disappear when clicked.

= 0.3.0.142 =
* Fixed a bug in the .dbt export that didn't include filterlocks in the exported file.

== Upgrade Notice ==

Simply overwrite the existing folder with the new one.

