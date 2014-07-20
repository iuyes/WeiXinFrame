<?php
class MCache
{
	private $mmc;
	public function __construct()	//构造函数，链接memcache
	{
		$this->mmc = memcache_init();
	}
	
	public function __destruct()	//析构，关闭memcache
	{
	}
	
	public function set($name, $value, $timeout)	//添加缓冲，成功返回true， 失败返回false
	{ 
		return memcache_set($this->mmc, $name, $value, 0, $timeout);
	}
	
	public function get($name)			//获取缓存数据
	{
		return memcache_get($this->mmc, $name);
	}
	
	public function delete($name)		//删除单个缓存
	{
		return memcache_set($this->mmc, $name, FALSE, 0, 1);
	}
	
	public function exist($name)		//判断是否存在缓存
	{
		return memcache_get($this->mmc, $name) != FALSE;
	}
	
	public function clear()			//清空所有缓存
	{
		return memcache_flush($this->mmc);
	}
}


?>