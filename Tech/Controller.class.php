<?php
class Controller
{
	public $components;
	public $requestInfo;
	public $param;

	public function beforeFilter()
	{

	}

	public function afterFilter()
	{

	}

	protected function returnText($text)
	{
		$this->components['WeiXin']->reply($this->components['WeiXin']->makeText($text));
	}
}