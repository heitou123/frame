<?php

namespace houdunwang\core;
class Controller
{
	//定义跳转地址属性,
	private $url = "window.history.back()";
	
	/*
	 *  提示消息
	 *  调用message这个方法->message('提示信息');
	 *  $message会接收传过来的内容
	 *  可以将接收过来的值,用$message写到引入的message.php页面中
	 */
	public function message ($message)
	{
		include './view/message.php';
		exit;
	}
	
	/*
	 *  string $url 是调用传进来的url
	 *  如果$url位空,则默认使用$url声明好的属性的值
	 *  return $this是为了调用方法的时候需要接收到的是个对象,得把$this->url值返回出去
	 */
	public function setRedirect ($url = '')
	{
		if (empty($url)) {
			$this->url = 'window.history.back()';
		} else {
			$this->url = 'location.href="$url"';
		}
		return $this;
	}
}