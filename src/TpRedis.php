<?php
    /**
     * Created by PhpStorm.
     * User: tengjufeng
     * Date: 2018/9/5
     * Time: 下午2:36
     */
    namespace artecfeng\tpredis;

    class TpRedis {
        private static $handler = null;

        private static $_instance = null;

        private static $options = [

            'host'       => '127.0.0.1',

            'port'       => 6379,

            'password'   => '',

            'select'     => 0,

            'timeout'    => 0,

            'expire'     => 0,

            'persistent' => false,

            'prefix'     => '',

        ];



        private function __construct ($options = []) {

            if (!extension_loaded( 'redis' )) {

                throw new \BadFunctionCallException( 'not support: redis' );      //判断是否有扩展

            }
            $options = config('redisconf.');
            if (!empty( $options )) {

                self::$options = array_merge( self::$options, $options );

            }

            $func = self::$options['persistent'] ? 'pconnect' : 'connect';     //长链接

            self::$handler = new \Redis;

            self::$handler->$func( self::$options['host'], self::$options['port'], self::$options['timeout'] );



            if ('' != self::$options['password']) {

                self::$handler->auth( self::$options['password'] );

            }



            if (0 != self::$options['select']) {

                self::$handler->select( self::$options['select'] );

            }

        }





        /**

         * @return RedisPackage|null 对象

         */

        public static function getInstance () {

            if (!( self::$_instance instanceof self )) {

                self::$_instance = new self();

            }



            return self::$_instance;

        }



        /**

         * 禁止外部克隆

         */

        public function __clone () {

            trigger_error( 'Clone is not allow!', E_USER_ERROR );

        }



        /**

         * @param $key

         *

         * @return array 获取键

         */

        public static function keys ($key) {

            return self::$handler->keys( $key );

        }



        /**

         * @param $key

         *

         * @return bool 成功返回：TRUE;失败返回：FALSE

         */

        public static function exists ($key) {

            return self::$handler->exists( $key );

        }



        /**

         * @param $key 数字递增存储键值键.

         *

         * @return int 返回自增后的值

         */

        public static function incr ($key) {

            return self::$handler->incr( $key );

        }



        /**

         * @param $key 数字递减存储键值键.

         *

         * @return int 返回自减后的值

         */

        public static function decr ($key) {

            return self::$handler->decr( $key );

        }



        /**

         * 写入缓存

         *

         * @param string $key    键名

         * @param string $value  键值

         * @param int    $exprie 过期时间 0:永不过期

         *

         * @return bool

         */





        public static function set ($key, $value, $exprie = 0) {

            if ($exprie == 0) {

                $set = self::$handler->set( $key, $value );

            } else {

                $set = self::$handler->setex( $key, $exprie, $value );

            }



            return $set;

        }



        /**

         * 读取缓存

         *

         * @param string $key 键值

         *

         * @return mixed

         */

        public static function get ($key) {

            $fun = is_array( $key ) ? 'Mget' : 'get';



            return self::$handler->{$fun}( $key );

        }



        /**

         * 获取值长度

         *

         * @param string $key

         *

         * @return int

         */

        public static function lLen ($key) {

            return self::$handler->lLen( $key );

        }





        /**

         * 将一个或多个值插入到列表头部

         *

         * @param $key

         * @param $value

         *

         * @return int

         */

        public static function LPush ($key, $value) {

            return self::$handler->lPush( $key, $value );

        }



        /**

         * 移出并获取列表的第一个元素

         *

         * @param string $key

         *

         * @return string

         */

        public static function lPop ($key) {

            return self::$handler->lPop( $key );

        }
    }