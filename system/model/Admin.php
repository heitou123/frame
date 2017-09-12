<?php

    namespace system\model;

    use houdunwang\model\Model;

    class Admin extends Model
    {
        /*
         *  [登录管理登录页面]
         */
        public function login ( $data )
        {
            // 调用这个方法,将传的数据用变量接收
            $admin_username = $data[ 'admin_username' ];
            $admin_password = $data[ 'admin_password' ];
            $captcha        = $data[ 'captcha' ];
            // printT($admin_username);
            // printT($admin_password);
            // printT($captcha);
            // 数据验证
            // return ['code'=>0,'msg'=>'请输入用户名'];
            // code表示成功还是失败的标识 1代表成功,0代表失败
            // msg 提示消息
            if ( !trim ($admin_username) ) return [ 'code' => 0 , 'msg' => '请输入用户名' ];
            if ( !$admin_password ) return [ 'code' => 0 , 'msg' => '请输入密码' ];
            if ( !trim ($captcha) ) return [ 'code' => 0 , 'msg' => '请输入验证码' ];
            // 根据用户提交的username在数据库作为条件进行查找
            // admin_username[条件]='{$admin_username}[条件值]
            // 查询所有数据
            $userInfo = $this -> where ("admin_username='{$admin_username}'") -> getAll ();
            // 如果找不到数据,说明当前用户不存在
            if ( !$userInfo ) return [ 'code' => 0 , 'msg' => '用户名不存在' ];
            // 将获得的数据转为数组
            $userInfo = $userInfo -> toArray ();
            // 匹配密码是否正确
            // 第一个参数是传进来的值,第二个参数是数据库原本的值
            if ( !password_verify ($admin_password , $userInfo[ 0 ][ 'admin_password' ]) ) return [ 'code' => 0 , 'msg' => '密码不正确' ];
            // 匹配验证码
            if ( strtolower ($captcha) != strtolower ($_SESSION[ 'phrase' ]) ) return [ 'code' => 0 , 'msg' => '验证码不正确' ];
            // 登录成功,将用户登录的信息储存到session中,页面才可以显示调用
            $_SESSION[ 'admin_id' ]       = $userInfo[ 0 ][ 'admin_id' ];
            $_SESSION[ 'admin_username' ] = $userInfo[ 0 ][ 'admin_username' ];
            // 返回成功的标识和成功提示信息
            return [ 'code' => 1 , 'msg' => '登陆成功' ];
        }

        /*  [修改密码]
         */
        public function changePassword ( $data )
        {
            if ( IS_POST ) {
                // 判断提交的密码不能为空
                if ( !$data[ 'admin_password' ] ) return [ 'code' => 0 , 'msg' => '请输入原始密码' ];
                if ( !$data[ 'admin_password_a' ] ) return [ 'code' => 0 , 'msg' => '新密码不能为空' ];
                if ( !$data[ 'admin_password_b' ] ) return [ 'code' => 0 , 'msg' => '新密码不能为空' ];
                // 结果3个提交过来的结果用变量储存
                $admin_password   = $data[ 'admin_password' ];
                $admin_password_a = $data[ 'admin_password_a' ];
                $admin_password_b = $data[ 'admin_password_b' ];
                // 判断新的两个密码是否一致
                if ( $admin_password_a != $admin_password_b ) return [ 'code' => 0 , 'msg' => '新的两个密码不一致' ];
                // 获取数据库的所有信息
                $userInfo = $this -> getAll () -> toArray ();
                // 判断旧密码是否正确
                if ( !password_verify ($admin_password , $userInfo[ 0 ][ 'admin_password' ]) ) return [ 'code' => 0 , 'msg' => '原始密码不正确' ];
                // printT($userInfo[ 0 ]);die;
                // 将提交过来的密码用password_hash解析
                $admin_password_a = password_hash ($admin_password_b , PASSWORD_DEFAULT);
                // printT($admin_password_a);die;
                // 调阅原生sql将admin_id为1的密码替换成传入进来的密码
                $this -> query ("update admin set admin_password = '{$admin_password_a}' where admin_id=1");
                // printT($re);die;
                // 修改密码之后清空session的空间
                session_unset ();
                session_destroy ();
                // 走到这里说明上面的都验证通过,返回1,提示信息
                return [ 'code' => 1 , 'msg' => '修改密码成功' ];
            }
        }
    }