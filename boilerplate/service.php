<?php
require_once '../vendor/autoload.php';

$log = new \Monolog\Logger('name');
$log->pushHandler(new \Monolog\Handler\StreamHandler(getcwd().'/log/service.log'));
$request = json_decode(file_get_contents('php://input'), true);



$log->debug('Request: '.json_encode($request));

$compressed64 = \LZCompressor\LZString::compressToBase64($request['str']);
$decompressed64 = \LZCompressor\LZString::decompressFromBase64($request['com64']);

//$decompressed64 = '';

$result = [
    'compressed64php' => $compressed64,
    'decompressed64php' => $decompressed64
];

$log->debug('Result: '.json_encode($result));

header('Content-type: text/application');
echo json_encode($result);