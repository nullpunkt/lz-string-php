<?php
require_once '../../src/LZString.php';
header('Content-Type: application/json; charset=utf-8');
echo json_encode(array('w'=>LZString::compress($_GET['w'])));