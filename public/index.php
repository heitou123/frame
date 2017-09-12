<?php
    /*
     *  入口文件
     */
//加载composer的autoload文件
    require_once '../vendor/autoload.php';
//调用app/houdunwang/core/Boot.php应用run的方法

//1.此处会报错,需要在composer.json文件中添加
//	"require": {},
//    "autoload":{
//	"files":[
    //引入助手文件
//		"system/helper.php"
//	],
//        "psr-4":{
    //会自动加载houdunwang这个目录的所有class类
//		"houdunwang\\":"houdunwang\\"
//        }
//    }

//2.每次composer.json修改需要composer dump执行一次,否者不会生效
    \houdunwang\core\Boot ::run ();
