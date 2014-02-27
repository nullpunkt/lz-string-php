<?php
require_once '../src/LZString.php';

$set = array();
$items = array('A', 'B', 'C', 'D', 'E', 'F');
$skip = 100;
$max = 1;
foreach($items as $a) {
    foreach($items as $b) {
        foreach($items as $c) {
            foreach($items as $d) {
                if($skip-->0)continue;
                if(!(count($set)<$max))
                    continue;
                $set[] = $a.$b.$c.$d;
            }   
        }   
    }    
}
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->


        <div class="container">
            <hr>
            <!-- Example row of columns -->
            <div class="row">
                <div class="col-lg-6">
                    <h2>compress</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Value</th>
                                <th>PHP</th>
                                <th>JS</th>
                                <th>md5(PHP)</th>
                                <th>md5(JS)</th>
                                <th>MATCH</th>
                            </tr>
                        </thead>
                        <tbody class="compress">
                            <?php
                                foreach($set as $value) {
                                    echo '
                                        <tr>
                                            <td class="value">'.$value.'</td>
                                            <td class="PHP">'.LZString::compress($value).'</td>
                                            <td class="JS"></td>
                                            <td class="JSMD5"></td>
                                            <td>4</td>
                                        </tr>
                                    ';
                                }
//                                            <td class="PHPMD5">'.substr(md5(LZString::compress($value)), 0, 4).'</td>
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-6">
                    <h2>compressToBase64</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Value</th>
                                <th>PHP</th>
                                <th>JS</th>
                                <th>md5(PHP)</th>
                                <th>md5(JS)</th>
                                <th>MATCH</th>
                            </tr>
                        </thead>
                        <tbody class="compressToBase64">
                            <?php
//                                foreach($set as $value) {
//                                    echo '
//                                        <tr>
//                                            <td class="value">'.$value.'</td>
//                                            <td class="PHP">'.LZString::compressToBase64($value).'</td>
//                                            <td class="JS"></td>
//                                            <td class="PHPMD5">'.substr(md5(LZString::compressToBase64($value)), 0, 4).'</td>
//                                            <td class="JSMD5"></td>
//                                            <td>4</td>
//                                        </tr>
//                                    ';
//                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <hr>
            </div>


        </div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
        <script src="js/vendor/bootstrap.min.js"></script>
        <!--<script src="js/vendor/lz-string-1.3.3.js"></script>-->
        <script src="js/vendor/lz-string-1.0.2.js"></script>
        <script src="js/vendor/md5.js"></script>
        <script src="js/main.js"></script>

    </body>
</html>
