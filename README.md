# mailchimp-bulk-delete
A simple php script to bulk delete MailChimp list members from an CSV MailChimp Segment Export

Setup:
- set 'key' to your API key (generate in MailChimp WebSite)
- set the url to point to the you Mail Chimp Data Center - the 'us2' part can change, the 'api.mailchimp.com/3.0/' is constant. You can find this by looking at the URL when you login to MC (look in the URL in your browser)
- set the $ListID in the code to default to one of your lists or you can set this on the command line when you call the script. You get the list ID from "Lists -> Settings (select from drop down on right of the list) -> Unique List ID on the screen that is displayed.

Usage:
php mc_delete_bulk.php -f"<CSV_filename>" -l"<listID_to_delete_from>"
php mc_delete_bulk.php -f"<CSV_filename>"

The code is written to use the first column of any CSV file as the email address to be deleted. So although this works with MC export files, you can of course use any CSV with the email address as the first field.
