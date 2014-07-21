<?php

function dispense($components)
{
	$msgTypeMapConf = include(ROOT . '/App/Conf/TypeConfig.conf');
	$subDispenserName = ucfirst(strtolower($components['WeiXin']->msg['Event']));
	include(ROOT . '/Tech/' . 'Event' . $subDispenserName . 'Dispenser.php');
	dispense($components);
	// if(isset($msgTypeMapConf['Location']))
	// {
	// 	include(ROOT . '/App/Controller/' . $msgTypeMapConf['Location']['Controller'] . 'Controller.class.php');
	// 	$className = $msgTypeMapConf['Location']['Controller'] . 'Controller';
	// 	$c = new $className;
	// 	$c->requestInfo['Msg'] = $components['WeiXin']->msg;
	// 	$c->requestInfo['MsgType'] = $components['WeiXin']->msgtype;
	// 	$c->components = $components;
	// 	$c->param = $msgTypeMapConf['Location'];
	// 	$c->beforeFilter();
	// 	$c->$msgTypeMapConf['Location']['Action']();
	// 	$c->afterFilter();
	// }
}