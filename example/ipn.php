<?php
/**
 *  PHP-PayPal-IPN Example
 *
 *  This shows a basic example of how to use the IPNListener() PHP class to
 *  implement a PayPal Instant Payment Notification (IPN) listener script.
 *
 *  This package is available at GitHub:
 *  https://github.com/WadeShuler/PHP-PayPal-IPN/
 *
 *  @package    PHP-PayPal-IPN
 *  @link       https://github.com/WadeShuler/PHP-PayPal-IPN
 *  @forked     https://github.com/Quixotix/PHP-PayPal-IPN
 *  @author     Wade Shuler
 *  @copyright  Copyright (c) 2015, Wade Shuler
 *  @license    http://choosealicense.com/licenses/gpl-2.0/
 */

// include the IpnListener Class, unless it's in your autoload
require_once( dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'IpnListener.php');

use wadeshuler\paypalipn\IpnListener;

$listener = new IpnListener();
$listener->use_sandbox = true;      // Only needed for testing (sandbox), else omit or set false

if ($verified = $listener->processIpn())
{

    // Valid IPN
    /*
        1. Check that $_POST['payment_status'] is "Completed"
        2. Check that $_POST['txn_id'] has not been previously processed
        3. Check that $_POST['receiver_email'] is your Primary PayPal email
        4. Check that $_POST['payment_amount'] and $_POST['payment_currency'] are correct
    */
    $transactionRawData = $listener->getRawPostData();      // raw data from PHP input stream
    $transactionData = $listener->getPostData();            // POST data array

    // Feel free to modify path and filename. Make SURE THE DIRECTORY IS WRITEABLE!
    // For security reasons, you should use a path above/outside of your webroot
    file_put_contents('ipn_success.log', print_r($transactionData, true) . PHP_EOL, LOCK_EX | FILE_APPEND);

} else {

    // Invalid IPN
    $errors = $listener->getErrors();

    // Feel free to modify path and filename. Make SURE THE DIRECTORY IS WRITEABLE!
    // For security reasons, you should use a path above/outside of your webroot
    file_put_contents('ipn_errors.log', print_r($errors, true) . PHP_EOL, LOCK_EX | FILE_APPEND);

}
