<?php
/**
 * 应用驱动类
 * @copyright   Copyright(c) 2011
 * @author      yuansir <yuansir@live.cn/yuansir-web.com>
 * @version     1.0
 */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 内容详解                                                                                                        //
    // 我叫它框架驱动类，也许不合适，但是我是这样理解的，它用来启动这个框架，做好一些初始化的工作，下面我来详细分析一下每个方法的功能：//
    // 1.首先时定义了一些常量，很明了，不解释了                                                                                       //
    // 2.setAutoLibs 这个方法其实就是设定那些是系统启动时自动加载的类库，类库文件都存放在SYS_LIB_PATH下面，以lib_开头的，当然这里你可以根据自己的规则来命名                   //
    // 3.autoload 这个方法就是用来引入你要自动加载的类，然后来实例化，用$_lib数组来保存类的实例，比如$lib['route']是system/lib/lib_route.php中lib_route类的实例 //
    // 4.newLib 这个方法是用来加载你自定义的类的，自定义类存放在根目录下的lib中，但是自定义的类的文件前缀是你自己定义的，看系统配置文件里面有，我定义的是my，这样我就可以在lib                //
    //     目录下新建一个自定义的类了，比如 my_test.php                                                                            //
    //     <?php                                                                                                   //
    //     class MyTest {                                                                                          //
    //             function __construct() {                                                                        //
    //                       echo "my lib test";                                                                   //
    //             }                                                                                               //
    //     }                                                                                                       //
    // 为什么类名这样命名，看下newLib方法的实现就知道，其实这些你完全可以定义自己的规则，这个方法会首先去着lib下面有没有这个类，如果有就会引入实例化，如果没有就去找系统目录下面的类，有就实例化           //
    // 5.init 就是一个初始化的方法，里面其实就是加载自动加载的类，以及引入核心控制器和核心模型，这个2个核心文件过会我们再来分析                                            //
    // 6.run 方法就是启动这个框架的了，里面的最后2步很重要，就是获取URL然后拆分成一个数组的形似，然后由routeToCm来分发到Controller和Model                          //
    // 7.routeToCm 很重要，根据URL分发到Controller和Model，这个我们过会来说                                                           //
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////
// 代码详解                                                      //
    // define()：自定义函数                                            //
    // final：用于类、方法前；类---不可被继承；方法---不可被覆盖                        //
    // static：                                                   //
    //    声明类成员或方法为static，就可以不实例化类而直接访问                          //
    //    静态属性不可以由对象通过->操作符来访问                                      //
    //    只能被初始化为一个字符值或一个常量                                         //
    // self：引用静态成员和常量，从类的内部访问const或者static变量或者方法,那么就必须使用自引用的self //
    // $this：不能引用静态成员和常量                                               //
    // ucfirst：首字符转换为大写                                                     //
    // is_object：判断对象是否存在                                                   //
///////////////////////////////////////////////////////////////

define('SYSTEM_PATH', dirname(__FILE__));           # 框架核心目录 地址
define('ROOT_PATH',  substr(SYSTEM_PATH, 0,-7));    # 框架根目录 地址
define('SYS_LIB_PATH', SYSTEM_PATH.'/lib');         # 框架核心目录下 核心类库目录 地址
define('APP_LIB_PATH', ROOT_PATH.'/lib');           # 框架自定义类库 地址
define('SYS_CORE_PATH', SYSTEM_PATH.'/core');       # 框架核心目录下 核心文件目录 地址
define('CONTROLLER_PATH', ROOT_PATH.'/controller'); # 框架控制器文件目录 地址
define('MODEL_PATH', ROOT_PATH.'/model');           # 框架模型文件目录 地址
define('VIEW_PATH', ROOT_PATH.'/view');             # 框架视图文件目录 地址
define('LOG_PATH', ROOT_PATH.'/error/');            # 错误日志目录 地址

final class Application {
        public static $_lib = null;     # 自动加载的类库
        public static $_config = null;  # 配置信息
        /**
         * 初始化
         * @access      public
         */
        public static function init() {
                self::setAutoLibs();                        # 设定那些系统启动时 自动加载的类库
                require SYS_CORE_PATH.'/model.php';         # 核心控制器
                require SYS_CORE_PATH.'/controller.php';    # 核心模型
        }
        /**
         * 创建应用
         * @access      public
         * @param       array   $config
         */
        public static function run($config){
                self::$_config = $config['system'];     # 存入配置信息
                self::init();                           # 引入静态 初始化 方法
                self::autoload();                       # 引入静态 自动加载类库 方法
                self::$_lib['route']->setUrlType(self::$_config['route']['url_type']); # 设置url的类型
                $url_array = self::$_lib['route']->getUrlArray();                      # 将url转发成数组
                self::routeToCm($url_array);            # 引入静态 URL分发 方法
        }
        /**
         * 自动加载类库
         * @access      public
         * @param       array   $_lib
         */
        public static function autoload(){
                foreach (self::$_lib as $key => $value){
                        require (self::$_lib[$key]);    # 引入自动加载类文件
                        $lib = ucfirst($key);           # 首字符转换为大写
                        self::$_lib[$key] = new $lib;   # 实例化后的信息 存入方法
                }
                //初始化cache
                if(is_object(self::$_lib['cache'])){    # 判断配置信息 是否存在
                        self::$_lib['cache']->init(
                                ROOT_PATH.'/'.self::$_config['cache']['cache_dir'],     # 配置文件路径
                                self::$_config['cache']['cache_prefix'],                # 缓存文件名前缀
                                self::$_config['cache']['cache_time'],                  # 缓存时间默认1800秒
                                self::$_config['cache']['cache_mode']                   # /mode 1 为serialize ，model 2为保存为可执行文件 
                                );
                }
        }
        /**
         * 加载类库
         * @access      public  
         * @param       string  $class_name 类库名称
         * @return      object
         */
        public static function newLib($class_name){
                $app_lib = $sys_lib = '';
                $app_lib = APP_LIB_PATH.'/'.self::$_config['lib']['prefix'].'_'.$class_name.'.php';
                $sys_lib = SYS_LIB_PATH.'/lib_'.$class_name.'.php';
                
                if(file_exists($app_lib)){
                        require ($app_lib);
                        $class_name = ucfirst(self::$_config['lib']['prefix']).ucfirst($class_name);
                        return new $class_name;
                }else if(file_exists($sys_lib)){
                        require ($sys_lib);
                        return self::$_lib['$class_name'] = new $class_name;
                }else{
                        trigger_error('加载 '.$class_name.' 类库不存在');
                }
        }
        /**
         * 自动加载的类库
         * @access      public 
         */
        public static function setAutoLibs(){
                self::$_lib = array(
                    'route'              =>      SYS_LIB_PATH.'/lib_route.php',
                    'mysql'              =>      SYS_LIB_PATH.'/lib_mysql.php',
                    'template'           =>      SYS_LIB_PATH.'/lib_template.php',
                    'cache'              =>      SYS_LIB_PATH.'/lib_cache.php',
                    'thumbnail'          =>      SYS_LIB_PATH.'/lib_thumbnail.php'
                );      
        }
        /**
         * 根据URL分发到Controller和Model
         * @access      public 
         * @param       array   $url_array     
         */
        public static function routeToCm($url_array = array()){
                $app = '';
                $controller = '';
                $action = '';
                $model = '';
                $params = '';
                
                if(isset($url_array['app'])){
                        $app = $url_array['app'];
                }
                
                if(isset($url_array['controller'])){
                        $controller = $model = $url_array['controller'];
                        if($app){
                                $controller_file = CONTROLLER_PATH.'/'.$app.'/'.$controller.'Controller.php';
                                $model_file = MODEL_PATH.'/'.$app.'/'.$model.'Model.php';
                        }else{
                                $controller_file = CONTROLLER_PATH.'/'.$controller.'Controller.php';
                                $model_file = MODEL_PATH.'/'.$model.'Model.php';
                        }
                }else{
                        $controller = $model = self::$_config['route']['default_controller'];
                        if($app){
                                $controller_file = CONTROLLER_PATH.'/'.$app.'/'.self::$_config['route']['default_controller'].'Controller.php';
                                $model_file = MODEL_PATH.'/'.$app.'/'.self::$_config['route']['default_controller'].'Model.php';
                        }else{
                                $controller_file = CONTROLLER_PATH.'/'.self::$_config['route']['default_controller'].'Controller.php';
                                 $model_file = MODEL_PATH.'/'.self::$_config['route']['default_controller'].'Model.php';
                        }
                }
                if(isset($url_array['action'])){
                        $action = $url_array['action'];
                }else{
                        $action = self::$_config['route']['default_action'];
                }
                
                if(isset($url_array['params'])){
                        $params = $url_array['params'];
                }
                if(file_exists($controller_file)){
                       if (file_exists($model_file)) {
                                require $model_file;
                        }
                        require $controller_file;
                        $controller = $controller.'Controller';
                        $controller = new $controller;
                        if($action){
                                if(method_exists($controller, $action)){
                                        isset($params) ? $controller ->$action($params) : $controller ->$action();
                                }else{
                                        die('控制器方法不存在');
                                }
                        }else{
                                die('控制器方法不存在');
                        }
                }else{
                        die('控制器不存在');
                }
        }

        

}


