<?php
/**
 * 应用入口文件
 * @copyright   Copyright(c) 2011
 * @author      yuansir <yuansir@live.cn/yuansir-web.com>
 * @version     1.0
 */

////////////////////////////////////////////////////////////
// 内容详解：                                                 //
	// 入口文件主要做了2件事
	// 第一引入系统的驱动类
	// 第二是引入配置文件，然后运行run（）方法，传入配置作为参数 //
////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////
// 代码详解：                                                    //
	// __FILE__	:当前文件完整路径                                    //
	// dirname()	:文件的部分路径                                    //
	// dirname(__FILE__)	：当前文件的路径                            //
	// Application：是驱动类中的类名
	// $CONFIG:	配置文件中的数组
	// 在run方法中:
	// self::$_lib['route']->setUrlType(self::$_config['route']['url_type']); //设置url的类型
	// $url_array = self::$_lib['route']->getUrlArray();                      //将url转发成数组
///////////////////////////////////////////////////////////////

require dirname(__FILE__).'/system/app.php';	# 引入系统的驱动类
require dirname(__FILE__).'/config/config.php';	# 引入配置文件
Application::run($CONFIG);	# 运行run（）方法
