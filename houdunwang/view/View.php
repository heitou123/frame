<?php
/*
 *  跳板借助
 *  如果在调用其他方法的时候,且调不到
 *  则需要借助View这个类的自动加载
 */
namespace houdunwang\view;
class View
{
	/*
	 *	当调用不存在的方法时候触发__call普通方法
	 *  $name = 不存在的方法名
	 *  $arguments	方法参数
	 *  运行流程
	 *  1.将$name不存在的方法名,传到用静态变量调用的parseAction里面
	 *  2.parseAction的方法会自动实例化Base里面的方法
	 *  3.实例化之后app/home/controller/Entry就可以调用houdunwang/view里的方法
	 */
	public function __call ($name, $arguments)
	{
		return self::parseAction ($name, $arguments);
	}
	/*
	 *	当调用不存在的方法时候触发__callStatic静态方法
	 *  $name = 不存在的方法名
	 *  $arguments	方法参数
	 *  运行流程
	 *  1.将$name不存在的方法名,传到用静态变量调用的parseAction里面
	 *  2.parseAction的方法会自动实例化Base里面的方法
	 *  3.实例化之后app/home/controller/Entry就可以调用houdunwang/view里的方法
	 */
	public static function __callStatic ($name, $arguments)
	{
		return self::parseAction ($name, $arguments);
	}
	
	/*
	 *  实例化调用这个parseAction传过来的$name的类
	 */
	public static function parseAction ($name, $arguments)
	{
		return call_user_func_array ([new Base, $name], $arguments);
	}
}