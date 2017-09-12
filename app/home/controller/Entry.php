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
            //[1.]测试数据库是否连接成功
            //Article::connect();

            //[2.]测试抛出异常加载第三方类库
            //include 'a';
            //Article::query('aaa');

            //[3.]测试数据库根据主键查找单一一条数据
            //1.拼接调用方法的时候需要用返回的$this对象
            //2.拼接完在将获得的对象用toArray方法解析[对象转为数组]
            //$re = Article ::find (1) -> toArray();
            //printT ($re);

            //[4.]where条件查找数据
            //1.where()方法里面需要添加需要查询的条件
            // $re = Article ::where("aid=1");
            // printT($re);

            //[5.]查询所有数据
            //$re = Article::getAll() ->toArray();
            //printT($re);

            //[6.]使用原生方式查询所有数据
            //$re = Article:: query('select * from student');
            //printT($re);

            //[7.]删除数据
            // $re = Article:: u

            //[8.]更新数据
            //1.返回的成功的条数
            // $data =  [
            //         'atitle' => '金三胖回来了'
            //     ];
            // $re = Article::where('aid=1') ->update($data);
            // printT($re);

            //[9.]添加数据
            //返回的是添加完成后的最后一个自增id
            // $data = [
            //     "atitle" => "李滨来耶"
            // ];
            // $re = Article::insert($data);
            // printT($re);

            // [10.]获取指定字段的所有
            // $re = Article::field('atitle') -> getAll() -> toArray();
            // $re = Article::field('atitle') -> find(1) ->toArray();
            // printT($re);

            //[11.]统计
            // $re = Article::count();
            // printT($re);

            //[12.]group by有多少个
            // 1.参数是要查找的字段
            // 2.例如,查出表里有多少个班级
            // $re = Article::group('atitle');
            // printT($re);

            //[13.]order by
            // 1.参数是排序的顺序'desc'从大到小,'asc'从小到大
            // 2.order 可以传递两个参数实例[bj desc,aid]
            // $re = Article::order('bj desc');
            // printT($re);

            //[14.]删除数据
            // $re = Article::where('aid=1')->destory();
            // printT($re);

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