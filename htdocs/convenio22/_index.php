<?php 
  isset($_SERVER['HTTP_X_REQUEST_URI']) ? ($_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REQUEST_URI']) : '';
  isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ? ($_SERVER['REQUEST_SCHEME'] = $_SERVER['HTTP_X_FORWARDED_PROTO']) : '';
  isset($_SERVER['HTTP_X_FORWARDED_PORT']) ? ($_SERVER['SERVER_PORT'] = $_SERVER['HTTP_X_FORWARDED_PORT']) : '';

define('RUNNING_FROM_ROOT', true);
include 'public/index.php';
