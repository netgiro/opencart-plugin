==============================================================
Netgiro Payment Extension Installation:
==============================================================

- Unzip and upload all folders and files in the Netgiro plugin to the 'upload' directory of your OpenCart installation.
The Admin and catalog folders of the module will be merged with admin and catalog folders of opencart.
- From the Opencart administration menu, go to 'Extensions and filter on Payment'.
- Click install for Netgiro post module
- After successful install click 'Edit'

==============================================================
Edit to configure access to test or production servers of Netgiro.
(It's recommended to start with a test configuration.)
==============================================================
Status = Enabled
Test Mode = YES (Netgiro test server connection.)
Application ID* for Test Mode = 881E674F-7891-4C20-AFD8-56FE2624C4B5
Secret Key* for Test Mode = YCFd6hiA8lUjZejVcIf/LhRXO4wTDxY0JhOXvQZwnMSiNynSxmNIMjMf1HHwdV6cMN48NX3ZipA9q9hLPb9C1ZIzMH5dvELPAHceiu7LbZzmIAGeOf/OUaDrk2Zq2dbGacIAzU6yyk4KmOXRaSLi8KW8t3krdQSX7Ecm8Qunc/A=
Succesful Order Status = Complete (Note: set the Succesful Order Status to what is right for the store's process in handling orders, i.e. Complete, Pending or Processed)

* Contact Netgiro for your store's application id and secret key.

For troubleshooting enable Debug Logging. The file is located at "storage/logs/payment_netgiro_post_debug.log".

==============================================================
Netgiro Test User (To use when in Test Mode)
==============================================================
Customer Id = 1111111119
Password = meerko1
