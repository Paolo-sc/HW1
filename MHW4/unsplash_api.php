<?php
    $client_id = 'sx57f9jmcTI6C54BWjTw8_xlvzSDw2lkW5xyUCZ1gQc';
    //  orientation=landscape&
    $url = 'https://api.unsplash.com/search/photos?per_page=5&query=plumbing&orientation=landscape&client_id='.$client_id;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $res=curl_exec($ch);
    curl_close($ch);

    echo $res;
?>