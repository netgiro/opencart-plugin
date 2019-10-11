<?php

// Heading
$_['heading_title']      = 'Netgiro Post';

// Text
$_['text_payment']       = 'Payment';
$_['text_success']       = 'Success: You have modified the account details!';
$_['text_development']   = '<span style="color: green;">Ready</span>';
$_['text_yes']      	 = 'Yes';
$_['text_no']      		 = 'No';
$_['text_po_one']      	 = '1 - Pay in 14 days';
$_['text_po_two']      	 = '2 - Allow Installments';
$_['text_po_three']      = '3 - Allow Installments w/o interest';
$_['text_edit']      	 = 'Edit Payment';

$_['tab_debug']		   	 = 'Debug';
$_['tab_support']		 = 'Support';

// Entry
$_['entry_title']        = 'Title:';
$_['entry_status']       = 'Status:';
$_['entry_tax_class']    = 'Tax Class:';
$_['entry_geo_zone']     = 'Geo Zone:';
$_['entry_order_status'] = 'Successful Order Status:';
$_['entry_mid']          = 'Application ID:';
$_['entry_key']          = 'Secret Key:';
$_['entry_max_install']  = 'Max Number of Installments:';
$_['entry_payment_option']  = 'Payment Option:';
$_['entry_txntype']      = 'Txn Type:';
$_['entry_sort_order']   = 'Sort Order:';
$_['entry_test']         = 'Test Mode:';
$_['entry_debug']        = 'Debug Logging:';
$_['entry_total']   	 = 'Min Total:';
$_['entry_debug_file']   = 'Debug File:';

// Tooltip
$_['tooltip_title']        = 'The title shown on the Payment step during checkout';
$_['tooltip_status']       = 'Enable/Disable';
$_['tooltip_geo_zone']     = 'Allowed Geozones';
$_['tooltip_tax_class']    = 'Assigned Tax class';
$_['tooltip_order_status'] = 'The status of the order upon successful payment';
$_['tooltip_mid']          = 'Get this from the merchant provider';
$_['tooltip_key']          = 'Get this from the merchant provider';
$_['tooltip_max_install']  = 'Depends on Payment Options setting';
$_['tooltip_payment_option']  = 'Depends on Account setup';
$_['tooltip_txntype']      = 'AUTH - Authorization only.  Requires you to login and manually complete from your FMX account. AUTHPOST - Authorization and capture immediately';
$_['tooltip_sort_order']   = 'The sort order on the checkout payment step';
$_['tooltip_test']         = 'Whether or not to use the test server/mode';
$_['tooltip_debug']        = 'Logs transaction messages';
$_['tooltip_total']   	   = 'The min total the cart must be to show this payment option. Recommend set to 0.01 or higher';

// Help
$_['help_ajax']          = 'Pre-confirm the order upon clicking the confirmation button before sending to the gateway. This is only needed if there are server communication problems with the gateway and orders are being lost. By pre-confirming the order, the order starts in a pending state, then upon gateway return, the final order status is updated. (Recommended: DISABLED)';
$_['help_debug']         = 'Debug mode is used when there are payment or order update issues to help track down where the problem is. This usually includes saving logs or sending emails to the store email with extra information. (Recommended: DISABLED)';
$_['help_mid']           = 'Use "881E674F-7891-4C20-AFD8-56FE2624C4B5" in test mode.';
$_['help_key']           = 'Use "YCFd6hiA8lUjZejVcIf/LhRXO4wTDxY0JhOXvQZwnMSiNynSxmNIMjMf1HHwdV6cMN48NX3ZipA9q9hLPb9C1ZIzMH5dvELPAHceiu7LbZzmIAGeOf/OUaDrk2Zq2dbGacIAzU6yyk4KmOXRaSLi8KW8t3krdQSX7Ecm8Qunc/A=" in test mode.';
$_['help_max_install']   = 'Depends on Payment Options setting above. This parameter controls the maximum number of installments the user can choose to pay with. Please note that Netgíró determines the number of installments based on minimum monthly rate and other factors, so the actual number of installments offered to the user can be smaller than specified with this parameter, but it will never be bigger';
$_['help_payment_option']   = 'This depends on your netgiro account supporting payment installments. If your account does not support pay by installments, set this to 1.';
$_['help_debug'] 		 = '<b style="color:red;">Enable this setting when having issues and send log to Developer when contacting for support. The file is located at "system/logs/'.basename(__FILE__, '.php').'_debug.txt" accessible via FTP. You may also need to send this file to the gateway Tech support.</b>';

// Error
$_['error_permission']   = 'Warning: You do not have permission to modify this payment module!';
$_['error_fields']       = 'mid,key';
$_['error_mid']          = 'Field required!';
$_['error_key']          = 'Field required!';
$_['error_desc']         = 'Field required!';

?>