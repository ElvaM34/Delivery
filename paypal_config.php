<?php
define('PAYPAL_CLIENT_ID', 'AXs8pcnEn7pHHbyuqkbAdTDz8L7TrarhX3pOV-hrz3-eBhIcQGgwFa_kntf6_xtWMLnHNEuifeQhM_yL');
define('PAYPAL_SECRET', 'EHlyqSYMHE1K4QeFCB7RI85QGYqQ-9iNRetxxFKyv_AcO-SrkPK8i1pFGpM-KlShaOSAh45chMG72QHb');
define('PAYPAL_API_URL', 'https://api.sandbox.paypal.com/v1/oauth2/token'); 

function getAccessToken() {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, PAYPAL_API_URL . "/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_USERPWD, PAYPAL_CLIENT_ID . ":" . PAYPAL_SECRET);  
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials"); 
    
    $headers = [
        "Accept: application/json",
        "Content-Type: application/x-www-form-urlencoded"
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        die("Error: " . curl_error($ch)); 
    }
    
    curl_close($ch);
    
    echo "<pre>";
    print_r($response); 
    echo "</pre>";

    $jsonResponse = json_decode($response);
    
    if (isset($jsonResponse->access_token)) {
        return $jsonResponse->access_token;
    } else {
        die("Error: No se pudo obtener el token de acceso de PayPal.");
    }
}
?>
