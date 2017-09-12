<?php

    namespace houdunwang\core;

    class Boot
    {
        /*
         *  index.php已经调用run
         *  所以这里需要调用其他可以用的方法
         */
        public static function run ()
        {
            //3.运行抛出异常
            self ::handler ();
            //1.调用初始化框架
            self ::init ();
            //2.调用执行应用
            self ::appRun ();
        }

        private static function handler ()
        {
            $whoops = new \Whoops\Run;
            $whoops -> pushHandler (new \Whoops\Handler\PrettyPageHandler);
            $whoops -> register ();
        }

        /*
         *  执行应用
         *  $_GET['s']有三个参数
         *  ?s=/home/entry/index
         *  模块,控制器,方法
         */
        public
        static function appRun ()
        {
            //判断$_GET['s']是否有参数
            if ( isset($_GET[ 's' ]) ) {
                //1.用变量来接收字符串分割成数组的数据
                //2.exploae将$_GET['s']的字符串转成数组用"/"分割
                $info = explode ('/' , $_GET[ 's' ]);


                //1.用变量接收拼接的空间路径
                //2.\app\$_GET['s']的第0号数组下标\controller\$_GET['s']的第1号下标[并转成首字母大写]\
                $class = "\app\\{$info[0]}\controller\\" . ucfirst ($info[ 1 ]);

                //1.用变量接收$info变量$_GET数组第二个下标
                //2.获得的是方法名称
                $action = $info[ '2' ];

                //定义常量,并把$_GET['s']里面的0,1,2号下标元素赋予生命的常量,用于全局使用
                define ('MODULE' , $info[ 0 ]);
                define ('CONTROLLER' , $info[ 1 ]);
                define ('ACTION' , $info[ 2 ]);
            } else {
                //1.如果$_GET['s']没有参数会执行以下代码
                //2.默认调用app/home/controller/Entry里面的index方法
                $class  = "\app\home\controller\Entry";
                $action = "index";

                //定义常量,指定3个常量值
                define ('MODULE' , 'home');
                define ('CONTROLLER' , 'entry');
                define ('ACTION' , 'index');
            }
            //1.new $class-实例化上面$class接收拼成的重命名空间路径和文件
            //2.$action-获得$_GET['s']传到$action的字符串,并使用该方法
            echo call_user_func_array ([ new $class , $action ] , []);
        }

        /*
         *  初始化框架
         *  设置一些开头需要添加的语法
         */
        public
        static function init ()
        {
            //声明utf-8
            //1.如果没有设置头部utf-8头部,页面会出现乱码
            //2.所以必须在页面开头就先设置好utf-8
            header ('Content-type:text/html;charset=utf8');

            //1.设置时区
            //2.如果没有设置时区,php页面用到时间的时候会跟PRC对不上
            date_default_timezone_set ('PRC');

            //1.开启session
            //2.使用session必须开启,如果有session_id则不会在重复开启,如果没有开启session,页面无法储存类似登录的用户
            session_id () || session_start ();
        }

    }
