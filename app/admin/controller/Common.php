<?php

    namespace app\admin\controller;
    class Common
    {
        /*  [登录验证]
         *  if判断_SESSION是否存在有admin_id,如果没有则跳转到登录页面
         *  ?s=模型/控制器/方法
         *  构造方法
         */
        public function __construct ()
        {
            if ( !isset($_SESSION[ 'admin_id' ]) ) {
                header ('location:?s=admin/login/index');
                exit;
            }
        }
    }