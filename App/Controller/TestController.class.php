<?php
class TestController extends AppController
{
	public $msg;
	public function index()
	{
		$this->msg .= '测试一下下:index'."\n";
	}

	public function beforeFilter()
	{
		$this->msg = 'beforFilter:'."\n";
	}

	public function afterFilter()
	{
		$this->msg .= 'afterFilter:'."\n";
		$this->returnText($this->msg);
	}

	public function test()
	{
		$this->msg = 'test:'."\n";
	}

	public function hahaha()
	{
		$this->msg .= 'hahaha:'."\n";
	}
}