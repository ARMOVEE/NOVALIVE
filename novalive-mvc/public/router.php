<?php
// Router untuk php -S localhost:8000 -t public public/router.php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$path = __DIR__ . $uri;
if ($uri !== '/' && is_file($path)) {
    return false; // sajikan file statis (css/gambar) apa adanya
}
$_GET['url'] = ltrim($uri, '/');
require __DIR__ . '/index.php';
