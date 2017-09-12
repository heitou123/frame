<?php
    /*
     *  连接数据库
     *  查询数据
     */

    namespace houdunwang\model;

    use Exception;
    use PDO;
    use PDOException;

    class Base
    {
        /*
         *  [定义属性]
         */
        //方便直接用$pdo调用
        private static $pdo = null;
        //用来接收处理过后的表名
        private $table;
        //储存查询结构数据
        private $data;
        //where条件
        private $where;
        //获取字段
        private $field = '';

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

        /*
         *  [将对象转为数组]
         *  要返回的是数组
         */
        public function toArray ()
        {
            if ( $this -> data ) {
                return $this -> data;
            }
            return [];
        }

        /*
         *  统计count()
         */
        public function count ()
        {
            // 变量接收拼接好的sql语句
            $sql = "select count(*)as tj from {$this->table} {$this->where}";
            // 执行sql
            $data = $this -> query ($sql);
            // 返回数据的0号下标的别名tj
            return $data[ 0 ][ 'tj' ];
        }

        /*
         *  字段有多少个group by
         */
        public function group ( $by )
        {
            // 变量接收拼接好的sql语句
            $sql = "select $by from {$this->table} group by {$by}";
            // return $sql;
            // 执行sql
            $data = $this -> query ($sql);
            // 返回数据
            return $data;
        }

        /*
         *  排序order by
         */
        public function order ( $by )
        {
            // return printT($by);
            // aid,desc
            //接收参进来的值用,分割成数组
            $this -> data = explode ("," , $by);
            // return printT($tj);
            //判断数组为1的时候为空字符串,如果有2个数组,则有多个条件,需要用,分开
            if ( count ($this -> data) == 1 ) {
                $douhao = '';
            } else {
                $douhao = ',';
            }
            // 三元判断,防止多个传入导致报错
            $noe = empty($this -> data[ 0 ]) ? '' : $this -> data[ 0 ];
            $tow = empty($this -> data[ 1 ]) ? '' : $this -> data[ 1 ];
            // 变量接收拼接的号sql语句
            $sql = "select * from {$this->table} {$this->where} order by {$noe}{$douhao}{$tow}";
            // return $sql;
            // 执行sql
            $data = $this -> query ($sql);
            // 返回结果
            return $data;
        }

        /*
         *  获取指定的字段
         *  调用field的方法,如果有传参就将参数赋值给$field定义好的属性
         */
        public function field ( $field )
        {
            $this -> field = $field;
            return $this;
        }

        /*
         *  查询所有数据
         *  用三元表达式判断,如果有传进指定的参数作为查询的字段,那则用
         */
        public function getAll ()
        {
            // 变量接收调用属性,如果没有传参则使用*
            $field = $this -> field ? : '*';
            // 拼接sql语句
            $sql = "select {$field} from {$this->table} {$this->where}";
            // 执行sql语句存入data数据
            $data         = $this -> query ($sql);
            $this -> data = $data;
            // 返回对象即可
            return $this;
        }

        /*
         *  更新数据
         */
        public function update ( array $data )
        {
            //防止没有传入where条件参数,返回出去不允许执行接下来的代码
            if ( empty($this -> where) ) {
                return false;
            }
//        声明一个空字符串来储存重组后的结果
            $fields = '';
            foreach ( $data as $key => $value ) {
//            判断传$data的数组value值是不是数字类型,如果不是数字类型,获取的值就添加单引号
                if ( is_int ($value) ) {
                    $fields .= "$key=$value" . ',';
                } else {
                    $fields .= "$key='$value'" . ',';
                }
            }
            //return $fields;
            // 将获得的重组字符串的最后一个","删除
            $this -> field = rtrim ($fields , ',');
            // 变量接收拼接好的sql语句
            $sql = "update {$this->table} set {$this->field} {$this->where }";
            //printT($sql);
            // 返回没有结果集
            return $this -> exec ($sql);
        }

        /*
         *  添加数据
         */
        public function insert ( $data )
        {
            // 当调用insert方法没有传参时执行if判断
            // 结果为false直接返回
            if ( empty($data) ) {
                return false;
            }
            // 声明两个孔字符串接收重组数组的值
            $fields = '';
            $values = '';
            foreach ( $data as $key => $value ) {
                // 循环获得主名存入先声明好的空变量里,增加多个会用,隔开
                $fields .= $key . ',';
                // 循环获得键值判断当不是数字类型时,值添加个单引号,增加多个会用,隔开
                if ( is_int ($value) ) {
                    $values .= $value . ',';
                } else {
                    $values .= "'$value'" . ',';
                }
            }
            // 将获得的重组字符串的最后一个","删除
            $fields = rtrim ($fields , ',');
            $values = rtrim ($values , ',');
            // 变量接收拼接好的sql语句
            $sql = "insert into {$this->table} ({$fields}) values ({$values})";
            // return $sql;
            // 返回没有结果集
            return $this -> exec ($sql);
        }

        /*
         *  [where]条件
         *  如果链式调用的话,需要返回的是对象
         */
        public function where ( $where )
        {
            $this -> where = "where $where";
            return $this;
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
            if ( !empty($data) ) {
                // 将其变成一个一维数组
                $this -> data = current ($data);
                return $this;
            }
            return $this;
            return [];
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
         *  删除数据
         *  destory要是有传参数则是删除第几条数据,语句aid删除
         *
         */
        public function destory ( $pk = '' )
        {
            // 有where条件或者有值传入
            // 则执行if判断,如果没有传参或主键则执行else
            if ( empty($this -> where) || empty($pk) ) {
                // 如果where条件是空的时候,则执行主键删除主键第几条aid
                if ( empty($this -> where) ) {
                    //获取主键
                    $priKey = $this -> getPk ();
                    // 这个时候说明没有where条件
                    // 那么把destory传入参数作为where条件
                    $this -> where ("{$priKey}={$pk}");
                }
                // 用$data变量接收,调用$this->query(结果的返回值)
                $sql = "delete from {$this->table} {$this->where}";
                // 执行sql语句
                return $this -> exec ($sql);
            } else {
                // 返回fales
                return false;
            }
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

        /*
         *  执行没有结果的sql
         */
        public function exec ( $sql )
        {
            try {
                // 变量接收执行系统自带的无结果集
                $re = self ::$pdo -> exec ($sql);
                // 当写入成功时就有主键增加
                // 执行系统自带的lastInsertId获得主键id最后一位
                if ( $lastInsertId = self ::$pdo -> lastInsertId () ) {
                    // 返回增加的主键值
                    return $lastInsertId;
                }
                return $re;
                // 当try里面的代码错误进入catch
                // 设置一个错误提示消息
                // 则会使用throw new语句
            } catch ( PDOException $e ) {
                throw new Exception($e -> getMessage ());
            }

        }


    }