<?php
/**
 * File: config_common.php
 * User: karan.tuteja26@gmail.com
 * Description: Common config variables not related to environment
 */

define("LOG_PATH",__DIR__."/../../logs");
$protocol = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';

$base_url = ($_SERVER['SERVER_NAME'] == '') ? "localhost/":$_SERVER['SERVER_NAME'];
define(BASE_URL,$protocol.$base_url);
define(QUERY_OFFSET,0);
define(QUERY_LIMIT,2);


define(REDIS_SHOW_PREFIX,'showInfo|');
define(REDIS_DATE_PREFIX,'dateShow|');



/*
echo BASE_URL;
echo QUERY_LIMIT;
echo QUERY_OFFSET;
*/