<?php
/*
 *  连接数据库
 *  查询数据
 */

namespace houdunwang\model;

use PDO;
use PDOException;
use Exception;

class Base
{
    //定义属性
    //方便直接用$pdo调用
    private static $pdo = null;
    //用来接收处理过后的表名
    private $table;

    /*  [new之后自动执行构造方法]
     *  实例化过的类,就会执行__construct这个构造方法
     */
    public function __construct ( $class )
    {
        //1.判断$pdo是否为空,如果空的话,执行connect静态方法
        //2.方便以后每次查询不需要重复连接connect方法里面的数据库连接
        if ( is_null (self ::$pdo) ) {
            self ::connect ();
        }
        //1.自动加载实例化传$class参数过来的值是:system\model\Articlesystem\model\Article
        //2.将$class优化
        //2.1载取至右边最后到\的字符串
        //2.2将左边的\去掉
        //2.3将字符串的首字母转为小写字母
        //3.将最后的值赋给$this->table
        $info          = strtolower (ltrim (strrchr ($class , '\\') , '\\'));
        $this -> table = $info;
        echo $info;
    }

    /*
     *  [连接数据库]
     *  账号密码,host不能固定死,以方便以后修改
     *  用helper.php助手文件里面的c()函数传入数据库名字+里面的关联键名
     *  返回出关联键名的值
     */
    public static function connect ()
    {
        try {
            $dsn      = c ('database.driver') . ":host=" . c ('database.host') . ";dbname=" . c ('database.dbname');
            $user     = c ('database.user');
            $password = c ('database.password');
            //设置字符集
            self ::$pdo = new PDO($dsn , $user , $password);
            //设置字符集
            self ::$pdo -> query ('set names utf8');
            //设置错误属性
        } catch ( PDOException $e ) {
            throw new Exception($e -> getMessage ());
        }
    }

    /*  [获取表的主键]
     *  $id调用find方法传进来的参数用来当做条件值
     *  需要获得表名和主键,把主键作为条件
     *  调用getPk方法获取主键
     *  用$data变量接收,调用$this->query(结果的返回值)
     *  把$data转为一维数组,返回给调用find方法的位置
     */
    public function find ( $id )
    {
        //echo $id;
        //echo $this->table;
        $pk   = $this -> getPk ();
        $sql  = "select * from {$this->table} where {$pk} = {$id}";
        $data = $this -> query ($sql);
        return current ($data);
        //$re = self::$pdo->query('select * from article where aid=1');
    }


    /*
     *  [获取表主键的唯一主键]
     *  用$sql查询获得的表结构
     *  用foreach循环抓取,并赋值给$pk变量
     *  将$pk变量的主键return返回给调用getPk方法的位置
     */
    public function getPk ()
    {
        //用desc+调用表,拼接到query方法查询
        $sql = "desc " . $this -> table;
        //返回的结果用foreach循环找出唯一的主键
        $data = $this -> query ($sql);
        $pk   = '';
        //printT($data);die;
        foreach ( $data as $v ) {
            //匹配成功将主键名付给$pk,并停止掉循环
            if ( $v[ 'Key' ] == 'PRI' ) {
                $pk = $v[ 'Field' ];
                break;
            }
        }
        return $pk;
    }

    /*
     *  执行有结果集的查询
     *  qurey($sql)的参数是通过调用附带进来的
     */
    public function query ( $sql )
    {
        //echo $sql;
        //desc article
        try {
            //声明一个变量来存储接收到的数据,用query执行sql
            $res = self ::$pdo -> query ($sql);
            //将$re变量的数据用关联方式return出去
            return $row = $res -> fetchAll (PDO::FETCH_ASSOC);
        } catch ( PDOException $e ) {
            //要是有报错,则会通过这里处理,结束往下的代码,可更改style样式
            throw new Exception($e -> getMessage ());
        }
    }

}