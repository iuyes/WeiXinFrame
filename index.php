<?php

include('Tech/Tech.php');
define('ROOT', dirname(__FILE__));
define('DEBUG', true);
$components = initialize();

$components['WeiXin']->getMsg();
$type = $components['WeiXin']->msgtype; //消息类型

write_log($type);

include(ROOT . '/Tech/' . ucfirst($type) . 'Dispenser.php');

dispense($components);


