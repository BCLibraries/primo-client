<?php

require_once "vendor/autoload.php";

$config = [
    'apikey' => 'l8xx9114294ac4a8475397f047cf792463cd',
    'gateway' => 'https://api-na.hosted.exlibrisgroup.com',
    'vid' => 'bclib',
    'tab' => 'VIDEO',
    'scope' => 'MyInstitution'
];

$primo = \BCLib\PrimoClient\PrimoClient::build(
    $config['gateway'],
    $config['apikey'],
    $config['tab'],
    $config['vid'],
    $config['scope'],
    '01BC_INST'
);

$response = $primo->search('otello');
var_dump($response);