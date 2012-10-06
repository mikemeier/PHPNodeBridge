<?php

use mikemeier\PHPNodeBridge\Bridge;

/* @var $bridge Bridge */
$bridge = require '../app/service.php';

$response = $bridge->process((array)@$_POST);

echo $response;