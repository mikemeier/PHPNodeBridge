<?php

use mikemeier\PHPNodeBridge\Bridge;

/* @var $bridge Bridge */
$bridge = require '../app/service.php';

$message = new Message();

$bridge->sendMessageToUsers($message, $bridge->getUserContainer()->getAll());