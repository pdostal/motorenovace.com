<?php
// Stand-in for the .htaccess rewrite when running on PHP's built-in server.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (preg_match('~^/([A-Za-z0-9]+)\.html$~', $path, $m)) {
  $_GET['page'] = $m[1];
  require $_SERVER['DOCUMENT_ROOT'] . '/index.php';
  return true;
}
return false;
