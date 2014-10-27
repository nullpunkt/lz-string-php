<?php
require_once '../../src/LZString.new.php';

switch($_GET['a']) {
    case 'b64':
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array(
            'wc' => LZBase64::compress($_GET['w']),
            'wu' => LZBase64::uncompress(LZBase64::compress($_GET['w'])),
        ));
        break;
    case 'com':
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array(
            'wc' => LZString::compress($_GET['w']),
            'wu' => LZString::uncompress(LZString::compress($_GET['w'])),
        ));
        break;
}


