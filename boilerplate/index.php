<!doctype html>
<html class="no-js" lang="" data-ng-app="lzapp" ng-strict-di>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>lz-string-php boilerplate</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 50px;
            padding-bottom: 20px;
        }
    </style>
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap-theme.min.css">

</head>
<body>

<div class="container" data-ng-controller="LZStringCtrl as vm">
    <div class="row">
        <div class="col-md-12">
            <form class="form-inline">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" data-ng-model="vm.source">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" data-ng-click="vm.encode()">Encode!</button>
            </form>
        </div>
        <br>
        <br>
        <hr>
        <br>
        <div class="col-md-12">

            <table class="table table-condensed">
                <thead>
                <tr>
                    <th>Source</th>
                    <th>Compressed Base64 (JS)<br>Compressed Base64 (PHP)</th>
                    <th>Decompressed Base64 (JS)<br>Decompressed Base64 (PHP)</th>
                </tr>
                </thead>
                <tbody>
                <tr data-ng-repeat="row in vm.results | orderBy:'$index':true">
                    <td data-ng-bind="row.input"></td>
                    <td ng-class="{warning: row.compressed64!=row.compressed64php}">
                        <div data-ng-bind="row.compressed64"></div>
                        <div data-ng-bind="row.compressed64php"></div>
                    </td>
                    <td ng-class="{warning: row.decompressed64!=row.decompressed64php}">
                        <div data-ng-bind="row.decompressed64"></div>
                        <div data-ng-bind="row.decompressed64php"></div>
                    </td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>

<script src="bower_components/angular/angular.min.js"></script>
<script src="bower_components/lz-string/libs/lz-string.min.js"></script>
<script src="main.js"></script>
</body>
</html>

