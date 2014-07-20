<?php

function load($key, $mapConf)
{
	if(isset($mapConf[$key][0]))
	{
		include(ROOT . '/App/Controller/' . $mapConf[$key][0] . 'Controller.class.php');
		$className = $mapConf[$key][0] . 'Controller';
		return array(new $className, $mapConf[$key][1], $mapConf[$key][0]);
	}
	include(ROOT . '/App/Controller/KeyWordNotFoundController.class.php');
	return array(new KeyWordNotFoundController(), 'index', 'KeyWordNotFound');
}

function flipArray($arr)
{
	$map = array();
	foreach ($arr as $className => $value)
	{
		foreach ($value as $funcName => $keyWords)
		{
			if(is_array($keyWords))
			{
				foreach ($keyWords as $keyWord)
					$map[$keyWord] = array($className, $funcName);
			}
			else if (is_integer($funcName))
			{
				$map[$keyWords] = array($className, $keyWords);
			}
			else
				$map[$keyWords] = array($className, $funcName);
		}
	}
	return $map;
}

function loadMapConf($cache)
{
	$map;
	if(DEBUG)
	{
		$arr = include(ROOT . '/App/Conf/MapConfig.conf');
		$map = flipArray($arr);
		$cache->set('mapConf', $map, 0);
		return $map;
	}
	else
	{
		if($cache->exist('mapConf'))
			return $cache->get('mapConf');
		$arr = include(ROOT . '/App/Conf/MapConfig.conf');
		$map = flipArray($arr);
		$cache->set('mapConf', $map, 0);
		return $map;
	}
	
}


function dispense($components)
{
	$map = loadMapConf($components['Cache']);
	$conetent = $components['WeiXin']->msg['Content'];
	$key = strtok($conetent, ' ');
	$cf = load($key, $map);
	$cf[0]->requestInfo['Msg'] = $components['WeiXin']->msg;
	$cf[0]->requestInfo['MsgType'] = $components['WeiXin']->msgtype;
	$cf[0]->components = $components;
	$cf[0]->param = array('Controller' => $cf[2], 'Action' => $cf[1]);
	$cf[0]->beforeFilter();
	$cf[0]->$cf[1]();
	$cf[0]->afterFilter();
}