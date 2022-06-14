<?php

require __DIR__.'/../vendor/autoload.php';

///// ACCOUNT ////////////
$username = '';
$password = '';
//////////////////////////

/////////// API //////////
$debug = false;
$accessKey = 'YOUR_ACCESS_KEY';
$proxy = 'http://user:pass@proxy:port';
///////////////////////////
$tiktok = new \TikTokAPI\TikTok($debug);
$tiktok->setAccessKey($accessKey);
$tiktok->setProxy($proxy);

try {
    $loginResponse = $tiktok->login($username, $password);
} catch (\TikTokAPI\Exception\AccesskeyException $e) {
    echo 'Invalid or expired access key.';
    exit();
} catch (\Exception $e) {
    echo sprintf('Oops, something went wrong: %s', $e->getMessage());
}
