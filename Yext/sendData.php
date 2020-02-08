<?php

include_once('../includes/db_config.php');
$receivedData = json_decode(file_get_contents('php://input'),true);
$result = [];

if(!empty($receivedData['profile']) && !empty($receivedData['listing'])) {
    $businessInfo = $receivedData['profile'];
    $listing = $receivedData['listing'];

    $insertionData = [
        'name' => !empty($businessInfo['name']) ? $businessInfo['name'] : null,
        'street' => !empty($businessInfo['street']) ? $businessInfo['street'] : null,
        'city' => !empty($businessInfo['city']) ? $businessInfo['city'] : null,
        'state' => !empty($businessInfo['state']) ? $businessInfo['state'] : null,
        'phone' => !empty($businessInfo['phone']) ? $businessInfo['phone'] : null,
        'result' => !empty($businessInfo['Optimization']) ? $businessInfo['Optimization'] : null,
        'optimization_rate' => !empty($businessInfo['Optimization_rate']) ? $businessInfo['Optimization_rate'] : null,
        'created_at' => $db->now()
    ];

    $insert = $db->insert('yext_business', $insertionData);

    if(empty($insert)){
        $result['error'] = 'failed to insert profile data';
        error_log('[ yext ] ' . $db->getLastError() . ' | ' . $db->getLastQuery());
    }
    else {
        $result['msg'] = 'Profile insertion success';
        $insertList = "";

        foreach($listing as $list) {
            $insertListing = [
                'name' => !empty($list['name']) ? $list['name'] : null,
                'address' => !empty($list['address']) ? $list['address'] : null,
                'phone' => !empty($list['phone']) ? $list['phone'] : null,
                'logo' => !empty($list['logo']) ? $list['logo'] : null,
                'profile_link' => !empty($list['profileLink']) ? $list['profileLink'] : null,
                'listing_on' => !empty($list['listingOn']) ? $list['listingOn'] : null,
                'status' => !empty($list['status']) ? $list['status'] : null,
                'filter_id' => $insert,
                'created_at' => $db->now()
            ];

            $insertList = $db->insert('yext_business_listing', $insertListing);
        }

        if(empty($insertList)){
            $result['error'] = 'failed to insert listing data';
            error_log('[ yext ] ' . $db->getLastError() . ' | ' . $db->getLastQuery());
        }
        else {
            $result['msg'] = 'Listing insertion success';
        }
    }
}


header('content-type: application/json');
header('Access-Control-Allow-Origin: *');
echo json_encode($result);