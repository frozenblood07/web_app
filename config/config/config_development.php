<?php
/**
 * File: config_development.php
 * User: karan.tuteja26@gmail.com
 * Description: Config variables related to dev environment
 */

define('ENVIRONMENT', "development");
define('ERROR_LEVEL',E_ERROR);
define('LOG_LEVEL', \Psr\Log\LogLevel::DEBUG);
define('DATA_FILE_PATH',__DIR__ . "/../../data/");