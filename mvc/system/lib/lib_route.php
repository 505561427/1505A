<?php
/**
 * URL处理类
 * @copyright   Copyright(c) 2011
 * @author      yuansir <yuansir@live.cn/yuansir-web.com>
 * @version     1.0
 */

/////////////////////////////////////////////////////////////////////////////////////////////////////////
// 内容详情                                                                                                //
    // URL处理类 又称为路由解析类
    // 注意querytToArray方法，将将query形式的URL转化成数组，                                                               //
    // 比如原来是localhost/myapp/index.php/app=admin&controller=index&action=edit&id=9&fid=10 这样的url就会被转发成如下的数组 //
    // array(                                                                                              //
    //     'app'       =>'admin',                                                                          //
    //     'controller'    =>'index',                                                                      //
    //     'action'    =>'edit',                                                                           //
    //     'id'        =>array(                                                                            //
    //                 'id'    =>9,                                                                        //
    //                 'fid'   =>10                                                                        //
    //             )                                                                                       //
    // )                                                                                                   //
    // 通过数组参数来分发到控制器，找到控制器以后还要引用相应的模型，然后就实例化控制器和模型。                                 //
/////////////////////////////////////////////////////////////////////////////////////////////////////////

// 代码详情
// final：用于类、方法前；类---不可被继承；方法---不可被覆盖
// __construct：构造函数
// parse_url：URL解析为数组，详情请百度
// trigger_error（）：错误提示,可设置错误级别，提示（默认）、警告、致命


final class Route{
        public $url_query;
        public $url_type;
        public $route_url = array();

        public function __construct() {
                $this->url_query = parse_url($_SERVER['REQUEST_URI']);      
        }
        /**
         * 设置URL类型
         * @access      public
         */
        public function setUrlType($url_type = 2){
                if($url_type > 0 && $url_type <3){
                        $this->url_type = $url_type;
                }else{
                        trigger_error("指定的URL模式不存在！");
                }
        }

        /**
         * 获取数组形式的URL  
         * @access      public
         */
        public function getUrlArray(){
                $this->makeUrl();
                return $this->route_url;
        }
        /**
         * @access      public
         */
        public function makeUrl(){
                switch ($this->url_type){
                        case 1:
                                $this->querytToArray();
                                break;
                        case 2:
                                $this->pathinfoToArray();
                                break;
                }
        }
        /**
         * 将query形式的URL转化成数组
         * @access      public
         */
        public function querytToArray(){
                $arr = !empty ($this->url_query['query']) ?explode('&', $this->url_query['query']) :array();
                $array = $tmp = array();
                if (count($arr) > 0) {
                        foreach ($arr as $item) {
                                $tmp = explode('=', $item);
                                $array[$tmp[0]] = $tmp[1];
                        }
                        if (isset($array['app'])) {
                                $this->route_url['app'] = $array['app'];
                                unset($array['app']);
                        } 
                        if (isset($array['controller'])) {
                                $this->route_url['controller'] = $array['controller'];
                                unset($array['controller']);
                        } 
                        if (isset($array['action'])) {
                                $this->route_url['action'] = $array['action'];
                                unset($array['action']);
                        }
                        if(count($array) > 0){
                                $this->route_url['params'] = $array;
                        }
                }else{
                        $this->route_url = array();
                }   
        }
        /**
         * 将PATH_INFO的URL形式转化为数组
         * @access      public
         */
        public function pathinfoToArray(){
                
        }
}


