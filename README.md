lz-string-php
=============

PHP Class implementation of LZ-String javascript
Based on the LZ-String javascript found here: http://pieroxy.net/blog/pages/lz-string/index.html
If you plan to exchange your data via AJAX use the base64 methods hence this is HTTP save.

## Usage
```php
<?php
\LZCompressor\LZString::compressToBase64($rawstr);
```

## Installation

### Composer
```cmd
composer require nullpunkt/lz-string-php
```

### Changelog
- 2016-02-25 Added v1.0.0 to packagist/composer nullpunkt/lz-string-php
- 2016-02-04 Overhaul and refactor
- 2014-03-12 Small Bugfix added (Thanks to Filipe)
- 2014-05-09 Added support for special chars like é,È, ... [Thanks to @carlholmberg]
