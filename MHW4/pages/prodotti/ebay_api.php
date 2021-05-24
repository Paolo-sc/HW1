<?php   
    header('Content-Type: application/json'); 

    $apiid ='';
    $apisecret = '';

    $ebay_endpoint = 'https://api.ebay.com/buy/browse/v1/';


    //ACCESS TOKEN
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://corsproxy.paolosc.workers.dev/corsproxy/?apiurl=https://api.ebay.com/identity/v1/oauth2/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    # Eseguo la POST
    curl_setopt($ch, CURLOPT_POST, 1);
    # Setto body e header della POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials&scope=https://api.ebay.com/oauth/api_scope'); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic '.base64_encode($apiid.':'.$apisecret))); 
    $token=json_decode(curl_exec($ch), true);
    curl_close($ch);

    //QUERY
    header('Content-Type: application/json');
    $query = urlencode($_GET["q"]);
    $query2 = urlencode($_GET["limit"]);
    $url = 'https://api.ebay.com/buy/browse/v1/item_summary/search?q='.$query.'&limit='.$query2;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    # Imposto il token
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token['access_token'],'X-EBAY-C-MARKETPLACE-ID: EBAY_IT')); 
    $res=curl_exec($ch);
    curl_close($ch);

    echo $res;
?>