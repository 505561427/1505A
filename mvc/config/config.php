<?php
/**
 * 系统配置文件
 * @copyright   Copyright(c) 2011
 * @author      yuansir <yuansir@live.cn/yuansir-web.com>
 * @version     1.0
 */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 内容详解                                                                                                    //
    // 里面其实是一个$CONFIG变量，这个变量存放的全局的配置                                                                           //
    // 这里有意识的定义$CONFIG['system']数组表示是系统的配置文件，                                                //
    // 当然你可以在里面定义$CONFIG['myconfig']为表示在定义的配置，以后在程序的控制器，模型，视图中来调用，都个很自由。 //
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*数据库配置*/
$CONFIG['system']['db'] = array(
    'db_host'           =>      'localhost',    // 数据库链接地址
    'db_user'           =>      'root',         // 用户名
    'db_password'       =>      '',             // 密码
    'db_database'       =>      'app',          // 数据库名称
    'db_table_prefix'   =>      'app_',         // 表前缀
    'db_charset'        =>      'urf8',         // 字符集
    'db_conn'           =>      '',             // 数据库连接标识; pconn 为长久链接，默认为即时链接
    
);

/*自定义类库配置*/
$CONFIG['system']['lib'] = array(
    'prefix'            =>      'my'   //自定义类库的文件前缀
);

$CONFIG['system']['route'] = array(
    'default_controller'             =>      'home',  //系统默认控制器
    'default_action'                 =>      'index',  //系统默认控制器
    'url_type'                       =>      1          /*定义URL的形式 1 为普通模式    index.php?c=controller&a=action&id=2
                                                         *              2 为PATHINFO   index.php/controller/action/id/2(暂时不实现)              
                                                         */                                                                           
);

/*缓存配置*/
$CONFIG['system']['cache'] = array(
    'cache_dir'                 =>      'cache', //缓存路径，相对于根目录
    'cache_prefix'              =>      'cache_',//缓存文件名前缀
    'cache_time'                =>      1800,    //缓存时间默认1800秒
    'cache_mode'                =>      2,       //mode 1 为serialize ，model 2为保存为可执行文件    
);






