<?php

    namespace app\admin\controller;

    use houdunwang\core\Controller;
    use houdunwang\view\View;
    use Gregwar\Captcha\CaptchaBuilder;
    use Gregwar\Captcha\PhraseBuilder;
    use system\model\Admin;

      /*
       *  登录控制器
       */

    class Login extends Controller
    {
        public function index ()
        {
            // 生成一个加密密码,存入输入库:版本要高,否侧无法使用password_hash函数
            // printT(password_hash ('admin888',PASSWORD_DEFAULT));
            // 检测是否连接数据库成功,调用测试
            // $res = Admin::find(1);
            // 检测u函数[用来传入匹配?s=参数]

            // if判断是否为正常的post方法提交的
            if ( IS_POST ) {
                // 接收模型返回的数据
                $re = ( new Admin() ) -> login ($_POST);
                // printT($re['code']);
                // 根据$re结果给模板页面进行相应(提示)
                // 接收的code会是1或者0
                if ( $re[ 'code' ] ) {
                    // 1成功会执行此代码
                    // u('模块'.'控制器'.'方法')
                    // $this->setRedirect ()跳转的url
                    // message提示的信息
                    // 如果没有写模块，默认当前模块
                    // 如果没有写控制器，默认当前模块当前控制器
                    // printT(u('entry.index'));
                    // printT(c('admin.entry.index'));die;
                    $this -> setRedirect ('?s=admin/entry/index') -> message ($re[ 'msg' ]);
                } else {
                    // 返回的结果为0执行此代码
                    // $this->setRedirect ()跳转的url
                    // message()参数是return过来附带的$re['msg']索引下标-提示的信息
                    $this -> setRedirect () -> message ($re[ 'msg' ]);
                }
            }
            // printT($_POST);
            return View ::make ();
        }

        /*  引入第三方验证码,切记要引入命名空间
         *  [加载验证码]
         */
        public function captcha ()
        {
            header ('Content-type: image/jpeg');
            $phraseBuilder = new PhraseBuilder(4);
            $builder       = new CaptchaBuilder(null , $phraseBuilder);
            $builder -> build ();
            //将验证码存入到session
            $_SESSION[ 'phrase' ] = $builder -> getPhrase ();
            $builder -> output ();
        }

        /*  [退出登录]
         *  释放⽤用户的session所有资源
         */
        public function logout ()
        {
            session_unset ();
            session_destroy ();
            $this -> setRedirect (u ('index')) -> message ('退出成功');
        }

        /*  [修改面]
         *
         */
        public function changePassword ()
        {
            // echo 1;
            // 调用Admin->$this->changePassword ()方法,并把提交的数据传给它
            if ( IS_POST ) {
                $re = ( new Admin() ) -> changePassword ($_POST);
                if ( $re[ 'code' ] ) {
                    // 1成功会执行此代码
                    // u('模块'.'控制器'.'方法')
                    // $this->setRedirect ()跳转的url
                    // message提示的信息
                    // 如果没有写模块，默认当前模块
                    // 如果没有写控制器，默认当前模块当前控制器
                    // printT(u('entry.index'));
                    $this -> setRedirect ('?s=admin/entry/index') -> message ($re[ 'msg' ]);
                } else {
                    // 返回的结果为0执行此代码
                    // $this->setRedirect ()跳转的url
                    // message()参数是return过来附带的$re['msg']索引下标-提示的信息
                    $this -> setRedirect () -> message ($re[ 'msg' ]);
                }
            }
            return View ::make ();
        }

    }