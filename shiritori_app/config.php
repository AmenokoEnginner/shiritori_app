<?php
session_start();
require_once(__DIR__ . '/function.php');
require_once(__DIR__ . '/Shiritori.php');
require_once('/home/config/shiritori_db.php');
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);
