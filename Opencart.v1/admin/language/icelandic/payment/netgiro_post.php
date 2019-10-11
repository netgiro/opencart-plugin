<?php
// Heading
$_['heading_title']      = 'Netgiro Post (iFrame)';

// Text
$_['text_payment']       = 'Payment';
$_['text_success']       = 'Success: You have modified the account details!';
$_['text_development']   = '<span style="color: green;">Ready</span>';
$_['text_yes']      	 = 'Yes';
$_['text_no']      		 = 'No';
$_['text_po_one']      	 = '1 - Pay in 14 days';
$_['text_po_two']      	 = '2 - Allow Installments';
$_['text_po_three']      = '3 - Allow Installments w/o interest';

// Entry
$_['entry_status']       = 'Status:';
$_['entry_geo_zone']     = 'Geo Zone:';
$_['entry_order_status'] = 'Successful Order Status:<br/><span class="help">The status of the order upon successful payment.</span>';
$_['entry_mid']          = 'Application ID:<br/><span class="help">Get this from the merchant provider.</span>';
$_['entry_key']          = 'Secret Key:<br/><span class="help">Get this from the merchant provider.</span>';
$_['entry_max_install']  = 'Max Number of Installments:<br/><span class="help">Depends on Payment Options setting above. This parameter controls the maximum number of installments the user can choose to pay with. Please note that Netgíró determines the number of installments based on minimum monthly rate and other factors, so the actual number of installments offered to the user can be smaller than specified with this parameter, but it will never be bigger</span>';
$_['entry_payment_option']  = 'Payment Option:<br/><span class="help">This depends on your netgiro account supporting payment installments. If your account does not support pay by installments, set this to 1.</span>';
$_['entry_txntype']      = 'Txn Type:<br/><span class="help">AUTH - Authorization only.  Requires you to login and manually complete from your FMX account. AUTHPOST - Authorization and capture immediately</span>';
$_['entry_sort_order']   = 'Sort Order:';
$_['entry_test']         = 'Test Mode:';
$_['entry_ajax']         = 'Ajax Pre-Confirm';
$_['entry_debug']        = 'Debug Logging:<br/><span class="help">Logs transaction messages to "system/logs/'.basename(__FILE__, '.php').'_debug.txt" file (in ftp) for troubleshooting. Please send the logged messaging when contacting the developer for help.</span>';
$_['entry_total']   	 = 'Min Total:<br /><span class="help">The min total the cart must be to show this payment option. Recommend set to 0.01 or higher.</span>';
$_['entry_title']        = 'Title:<br/><span class="help">The title shown on the Payment step during checkout</span>';

// Help
$_['help_ajax']          = 'Pre-confirm the order upon clicking the confirmation button before sending to the gateway. This is only needed if there are server communication problems with the gateway and orders are being lost. By pre-confirming the order, the order starts in a pending state, then upon gateway return, the final order status is updated. (Recommended: DISABLED)';
$_['help_debug']         = 'Debug mode is used when there are payment or order update issues to help track down where the problem is. This usually includes saving logs or sending emails to the store email with extra information. (Recommended: DISABLED)';
$_['help_mid']           = 'Use "881E674F-7891-4C20-AFD8-56FE2624C4B5" in test mode.';
$_['help_key']           = 'Use "YCFd6hiA8lUjZejVcIf/LhRXO4wTDxY0JhOXvQZwnMSiNynSxmNIMjMf1HHwdV6cMN48NX3ZipA9q9hLPb9C1ZIzMH5dvELPAHceiu7LbZzmIAGeOf/OUaDrk2Zq2dbGacIAzU6yyk4KmOXRaSLi8KW8t3krdQSX7Ecm8Qunc/A=" in test mode.';

// Error
$_['error_permission']   = 'Warning: You do not have permission to modify this payment module!';
$_['error_mid']          = 'Field required!';
$_['error_key']          = 'Field required!';
$_['error_desc']         = 'Field required!';

?>