<?php

namespace houdunwang\view;

class Base
{
    /*
     *  声明属性
     *  $data=[]空数据
     *  $file=用来接收拼接好的页面php文件
     */
    protected $data = [];
    protected $file;

    /*  [拼接路径作用]
     *  显示模板
     *  这里需要借助houdunwang/core/Boot.php里面的三个常量所接收的$_GET['s']参数
     *  将$_['s']所接的参数拼接成要显示模板的路径
     */
    public function make ()
    {
        //printT(MODULE);
        //printT(CONTROLLER);
        //printT(ACTION);
        //include "../app/home/view/entry/index.php";
        $this -> file = '../app/' . MODULE . '/view/' . strtolower (CONTROLLER) . '/' . ACTION . '.php';
        return $this;
    }

    /*
     *  $var形参调用传递过来的值
     *  app/home/conroller/Entry页面调用with方法
     *  需要return $this 不然这里调用方法会报错找不到obj对象
     */
    public function with ( $var )
    {
        $this -> data = $var;
        return $this;
    }

    /*  [运行拼接好的模板作用]
     *  __toString需要houdunwang/core/Boot.php的appRun方法echo 输出对象得时候才会触发
     *  extract(把数据转成变量字符串形式)
     *  [text] => houdunwang转成$text="houdunwang"
     *  include再引入$file拼接好的php路径模板
     *  必须return一个字符串
     */
    public function __toString ()
    {
        //echo 1;
        //printT($this->data);
        extract ($this -> data);
        include $this -> file;
        // printT($this -> file);die;
        return '';
    }
}