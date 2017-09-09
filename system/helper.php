<?php
/*
 * 助手函数
 */

/*
 * 定义常量判断是否为post请求
 */
define ('IS_POST' , $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ? true : false);

/*
 * 检测请求是否为ajax请求
 */
if ( isset($_SERVER[ 'HTTP_X_REQUESTED_WITH' ]) && $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] == 'XMLHttpRequest' ) {
    //是异步请求
    define ('IS_AJAX' , true);
} else {
    define ('IS_AJAX' , false);
}

//声明一个函数，打印
//用parint_r()
if ( !function_exists ('printT') ) {
    function printT ( $var )
    {
        echo '<pre style="background: #ccc;padding: 8px;border-radius: 5px">';
        echo '<div style="text-align:center;font-size:16px;color:#ff6700;font-weight:bold;">用“print_r”打印</div>';
        //print_r打印函数，不显示数据类型
        if ( is_null ($var) ) {
            var_dump ($var);
        } elseif ( is_bool ($var) ) {
            var_dump ($var);
        } else {
            print_r ($var);
        }
        echo '</pre>';
    }
}

//声明一个函数，打印
//用var_dump()
if ( !function_exists ('dumpT') ) {
    function dumpT ( $var )
    {
        echo '<pre style="background: #ccc;padding: 8px;border-radius: 5px">';
        echo '<div style="text-align:center;font-size:16px;color:#ff6700;font-weight:bold;">用“var_dump”打印</div>';
        //print_r打印函数，不显示数据类型
        if ( is_null ($var) ) {
            var_dump ($var);
        } elseif ( is_bool ($var) ) {
            var_dump ($var);
        } else {
            print_r ($var);
        }
        echo '</pre>';
    }
}

if ( !function_exists ('c') ) {
    /*
     *  读取配置项的c函数
     *  调用c函数传进来的参数让$var接收
     *  将接收来的参数转成数组,用"."进行切割
     *  用$data变量接收数组的0号下标的配置文件的数组
     *  将判断完的数组1号下标返回出去
     */
    function c ( $var )
    {
        $info = explode ('.' , $var);
        $data = include "../system/config/" . $info[ 0 ] . ".php";
        return isset($data[ $info[ 1 ] ]) ? $data[ $info[ 1 ] ] : null;
    }
}
