<?php

// Initial code from https://inchoo.net/magento/delete-spam-customer-accounts-magento/

// Include Magento autoloader file
include_once 'app/Mage.php';
 
// Initialize Magento on our script
Mage::app();

$customers = Mage::getModel(“customer/customer”)
    ->getCollection()
    ->addAttributeToFilter(’email’, array(‘like’ => ‘%qq.com’));

foreach ($customers as $customer) {
    
    // Try to load addresses for each account
    $customerAddresses = $customer->getAddresses();
    if ($customerAddresses) {
        continue;
    }

    // Check if there are any orders for each account.
    $customerOrders = Mage::getModel('sales/order')
        ->getCollection()
        ->addAttributeToFilter('customer_id', $customer->getId())
        ->load();
    if ($customerOrders->count()) {
        continue;
    }

    // Delete customer
    $customer->delete();
}