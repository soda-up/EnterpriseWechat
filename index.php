<?php
require_once __DIR__.'/vendor/autoload.php';//
require_once __DIR__.'/WechatConfig.php';
$department = new EnterpriseWechat\Resource\Tag(new WechatConfig());
$dps=$department->getItemsForTagId(2);var_dump($dps);
//var_dump($department->getUser('a97763299120032d51cd8dfe19400022'));