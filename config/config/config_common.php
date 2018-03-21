<?php
/**
 * File: config_common.php
 * User: karan.tuteja26@gmail.com
 * Description: Common config variables not related to environment
 */

define("LOG_PATH",__DIR__."/../../logs");
$protocol = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';

$base_url = ($_SERVER['SERVER_NAME'] == '') ? "localhost/":$_SERVER['SERVER_NAME'];
define('BASE_URL',$protocol.$base_url);
define('QUERY_OFFSET',0);
define('QUERY_LIMIT',2);


define('REDIS_SHOW_PREFIX','showInfo|');
define('REDIS_DATE_PREFIX','dateShow|');
define('REDIS_ORDER_PREFIX','orderInfo|');

define('TICKET_SELLING_START_THRESHOLD',40);

define('TOTAL_SHOWS',100);
define('BIG_HALL_SHOWS',60);
define('SMALL_HALL_SHOWS',40);

define('BIG_HALL_ATTENDANCE',100);
define('SMALL_HALL_ATTENDANCE',60);

define('BIG_HALL_DAY_TICKETS',10);
define('SMALL_HALL_DAY_TICKETS',5);

define('FULL_PRICE_SHOWS',80);
define('REDUCED_PRICE_SHOWS',20);
define('REDUCE_BY_FACTOR',.8);

define('LATEST_ORDER_ID','orderID');

define('DATA_FOUND',200);
define('BAD_REQUEST',400);
define('DATA_ERROR',200);