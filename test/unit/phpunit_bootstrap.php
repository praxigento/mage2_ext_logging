<?php
/**
 * Include composer autoloader.
 * Path is relative to "./work/vendor/<vendor>/<module>/test/unit/" folder.
 */
include_once(__DIR__ . '/../../../../autoload.php');
if(!defined('IS_M2_TESTS')) {
    define('IS_M2_TESTS', true);
}