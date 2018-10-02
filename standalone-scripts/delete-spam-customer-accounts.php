<?php

// Initial code from https://inchoo.net/magento/delete-spam-customer-accounts-magento/

// Include Magento autoloader file
include_once 'app/Mage.php';
 
// Initialize Magento on our script
Mage::app();

$customers = Mage::getModel("customer/customer")
    ->getCollection()
    ->addAttributeToSelect('*')
    ->addAttributeToFilter(
        array(
            array('attribute' => 'email', 'like' => '%qq.com')
        )
    );

foreach ($customers as $customer) {
    
    // Uncomment if you want to try to load addresses for each account
    /* $customerAddresses = $customer->getAddresses();
    if ($customerAddresses) {
        continue;
    } */

    // Uncomment if you want to check if there are any orders for each account.
    /* $customerOrders = Mage::getModel('sales/order')
        ->getCollection()
        ->addAttributeToFilter('customer_id', $customer->getId())
        ->load();
    if ($customerOrders->count()) {
        continue;
    } */

    // Delete customer
    Mage::register('isSecureArea', true);
    Mage::log('Deleting customer: ' . $customer->getName(), null, 'delete-spam.log');
    $customer->delete();
    Mage::unregister('isSecureArea');
}