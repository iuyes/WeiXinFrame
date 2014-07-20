<?php

function write_log($log)
{
	sae_set_display_errors(false); //关闭信息输出
	sae_debug($log); //记录日志
	sae_set_display_errors(true); //记录日志后再打开信息输出，否则会阻止正常的错误信息的显示
}

function initialize()
{
	$appConf = include(ROOT . '/App/Conf/AppConfig.conf');
	include(ROOT . '/Components/Cache/' . $appConf['Cache'] . '.class.php');
	include(ROOT . '/Components/WeiXin/WeiXin.class.php');
	include(ROOT . '/Tech/Controller.class.php');
	$components['Cache'] = new $appConf['Cache'];
	$components['WeiXin'] = new WeiXin($appConf['WeiXin']['Token']);
	return $components;
}

function __autoload($className)
{
	$floderType = substr($className, strlen($className) - 2);
	if($floderType == 'er')
		include(ROOT . '/App/Controller/' . $className . '.class.php');
}