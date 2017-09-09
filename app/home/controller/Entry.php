<?php
/*
 *  检查其他方法的引入或是否正常使用在这个类检测
 *  访问此类的方法,默认是跳转到index这个方法
 */

namespace app\home\controller;

use houdunwang\core\Controller;
use houdunwang\view\View;
use system\model\Article;

class Entry extends Controller
{
    public function index ()
    {
        include 1;
        //测试数据库是否连接成功
        //Article::connect();
        //测试调用数据
        $re = Article::find(1);
        //打印调用数据库的结果
        printT($re);

        //1.测试houdunwang/view/base里的make和with静态方法
        //2.在这个类中调用不到houdunwang/view/Base的make方法
        //3.需要借助houdunwang/view/View里面的自动加载类
        //4.调用with(将$text替成键名,houunwang替成键值);
        //5.数组格式[text] => houdunwang
        $text = 'houdunwang';
        return View ::with (compact ('text')) -> make ();
    }

    public function add ()
    {
        //1.测试houdunwang/core/里面的Controller
        //2.message和setRedirect方法
        //3.调用serRedirect的时候需要这个方法函数return值
        //4.不然这里调用方法会报错找不到obj对象
        $this -> setRedirect () -> message ('添加成功');
    }
}