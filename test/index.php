<?php
require_once '../src/LZString.php';

$rows = 5;
$digits = 5;
$set = array();
for ($i = 0; $i < $rows; $i++) {
    $str = '';
    for ($j = 0; $j < $digits; $j++)
        $str.=LZString::utf8_chr(rand(34, 123));
    $set[] = $str;
}
$test = array_key_exists('testString', $_POST) ? $_POST['testString'] : 'Test this text';
$testPHP = LZString::compressToBase64($test);

//$set = array(1,2,3,4,5,6,7,8,9);
//$set = array('fqYW{');
//$set = array('/L>[s');
//$set = array('["806","805"]');
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
            <div class="row">
                <div class="col-lg-4">
                    <h2>Test String</h2>
                    <form class="form-inline" role="form" method="POST">
                        <div class="form-group">
                            <label class="sr-only" for="testString">String to Test</label>
                            <input class="form-control" name="testString" style="width: 300px;" id="testString" placeholder="String to Test" value="<?php echo $test; ?>">
                        </div>
                        <button type="submit" class="btn btn-default">Test</button>
                    </form>
                </div>
                <div class="col-lg-8">
                    <h2>Result</h2>
                    <div class="row">
                        <div class="col-lg-3">
                            PHP compressToBase64
                        </div>
                        <div class="col-lg-5">
                            <code id="testCompressToBase64PHP">
                                <?php echo $testPHP; ?>
                            </code>
                        </div>    
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-3">
                            JS compressToBase64
                        </div>
                        <div class="col-lg-5">
                            <code id="testCompressToBase64JS">
                            </code>
                        </div>    
                    </div>
                </div>
            </div>

            <!-- Example row of columns -->
            <div class="row">
                <div class="col-lg-6">
                    <h2>compress</h2>
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>Value</th>
                                <th>PHP</th>
                                <th>JS</th>
                                <th>MATCH</th>
                            </tr>
                        </thead>
                        <tbody class="compress">
                            <?php
                            foreach ($set as $value) {
                                echo '
                                        <tr>
                                            <td class="value">' . $value . '</td>
                                            <td class="PHP">' . LZString::compress($value) . '</td>
                                            <td class="JS"></td>
                                            <td class="equals"></td>
                                        </tr>
                                    ';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-6">
                    <h2>compressToBase64</h2>
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>Value</th>
                                <th>PHP</th>
                                <th>JS</th>
                                <th>MATCH</th>
                            </tr>
                        </thead>
                        <tbody class="compressToBase64">
                            <?php
                            foreach ($set as $value) {
                                echo '
                                        <tr>
                                            <td class="value">' . $value . '</td>
                                            <td class="PHP">' . LZString::compressToBase64($value) . '</td>
                                            <td class="JS"></td>
                                            <td class="equals"></td>
                                        </tr>
                                    ';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <hr>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <h2>decompress</h2>
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>Value</th>
                                <th>Compressed</th>
                                <th>PHP</th>
                                <th>JS</th>
                                <th>MATCH</th>
                            </tr>
                        </thead>
                        <tbody class="decompress">
                            <?php
                            foreach ($set as $value) {
                                echo '
                                        <tr>
                                            <td class="value">' . $value . '</td>
                                            <td class="compressed">' . LZString::compress($value) . '</td>
                                            <td class="PHP">' . LZString::decompress(LZString::compress($value)) . '</td>
                                            <td class="JS"></td>
                                            <td class="equals"></td>
                                        </tr>
                                    ';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-6">
                    <h2>decompressFromBase64</h2>
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>Value</th>
                                <th>Compressed</th>
                                <th>PHP</th>
                                <th>JS</th>
                                <th>MATCH</th>
                            </tr>
                        </thead>
                        <tbody class="decompressFromBase64">
                            <?php
                            foreach ($set as $value) {
                                echo '
                                        <tr>
                                            <td class="value">' . $value . '</td>
                                            <td class="compressed">' . LZString::compressToBase64($value) . '</td>
                                            <td class="PHP">' . LZString::decompressFromBase64(LZString::compressToBase64($value)) . '</td>
                                            <td class="JS"></td>
                                            <td class="equals"></td>
                                        </tr>
                                    ';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <hr>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <h2>php/js fromCharCode</h2>
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>Number</th>
                                <th>PHP</th>
                                <th>PHPLength</th>
                                <th>JS</th>
                                <th>JSLength</th>
                                <th>Match</th>
                            </tr>
                        </thead>
                        <tbody class="fromCharCode">
                            <?php
                            for ($i = 0; $i < 10; $i++) {
                                $j = rand(0, 100000);
                                $fromCharCode = LZString::fromCharCode($j);
                                echo '
                                        <tr>
                                            <td class="value">' . $j . '</td>
                                            <td class="PHP">' . $fromCharCode . '</td>
                                            <td class="PHPL">' . mb_strlen($fromCharCode, 'UTF-8') . '</td>
                                            <td class="JS"></td>
                                            <td class="JSL"></td>
                                            <td class="equals"></td>
                                            <td class="equalsL"></td>
                                        </tr>
                                    ';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-6">
                    <h2>php fromCharCode/charCodeAt</h2>
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>Number</th>
                                <th>fromCharCode</th>
                                <th>charCodeAt</th>
                                <th>Match</th>
                            </tr>
                        </thead>
                        <tbody class="phpFromCharCode">
                            <?php
                            for ($i = 0; $i < 10; $i++) {
                                $j = rand(0, 100000);
                                $fromCharCode = LZString::fromCharCode($j);
                                $charCodeAt = LZString::charCodeAt($fromCharCode, 0);
                                echo '
                                        <tr>
                                            <td class="NUMBER">' . $j . '</td>
                                            <td class="fromCharCode">' . $fromCharCode . '</td>
                                            <td class="charCodeAt">' . $charCodeAt . '</td>
                                            <td class="equals ' . ($j === $charCodeAt ? 'success' : 'danger') . '">' . ($j === $charCodeAt ? 'true' : 'false') . '</td>
                                        </tr>
                                    ';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>


        </div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
        <script src="js/vendor/bootstrap.min.js"></script>
        <script src="js/vendor/lz-string-1.3.3.js"></script>
        <!--<script src="js/vendor/lz-string-1.0.2.js"></script>-->
        <script src="js/vendor/md5.js"></script>
        <script src="js/main.js"></script>

    </body>
</html>
