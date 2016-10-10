<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$config['api_server_payment'] = Array(
    'server' => "http://42.112.31.98/test/interface",
    'api_key' => '1zxomlBXXhORz9NliHuAt1JhBAekfFiUsQBsJSGc',
    'api_name' => 'key',
    'http_user' => 'backend',
    'http_pass' => 'backend.topica.',
    'http_auth' => 'basic',
);
$config['api_server_LMS'] = Array(
    'server' => "http://localhost/topica.tpe/webservice/rest/server.php",
    'api_key' => 'cbb62f3072143a940d860a338427839a',
);

//$config['api_server_LMS'] = Array(
//    'server' => "http://beta.tpe.topica.vn/webservice/rest/server.php",
//    'api_key' => 'cbb62f3072143a940d860a338427839a',
//);
$config['api_server_smsservice_th'] = array(
    'server' => 'http://smsservice.topicanative.asia',
    'http_auth' => 'basic',
    'api_name' => 'key',
    'http_user' => 'marketing.smsth',
    'http_pass' => 'marketing.smsth@topica.',
    'api_key' => 'RIsXdogqZxcyhOifjTJotuc3oJtMMO6GPYU1hxGD'
);