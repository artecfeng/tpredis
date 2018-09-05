<?php
    /**
     * Created by PhpStorm.
     * User: tengjufeng
     * Date: 2018/9/5
     * Time: 下午2:36
     */

    namespace artecfeng\tpredis;

    class TpRedis {
        private $handler = null;

        //private $_instance = null;

        private $options = [

            'host' => '127.0.0.1',

            'port' => 6379,

            'password' => '',

            'select' => 0,

            'timeout' => 0,

            'expire' => 0,

            'persistent' => false,

            'prefix' => '',

        ];


        public function __construct($options = []) {

            if (!extension_loaded('redis')) {

                throw new \BadFunctionCallException('not support: redis');      //判断是否有扩展

            }
            $options = config('redisconf.');
            if (!empty($options)) {

                $this->options = array_merge($this->options, $options);

            }

            $func = $this->options['persistent'] ? 'pconnect' : 'connect';     //长链接

            $this->handler = new \Redis;

            $this->handler->$func($this->options['host'], $this->options['port'], $this->options['timeout']);


            if ('' != $this->options['password']) {

                $this->handler->auth($this->options['password']);

            }


            if (0 != $this->options['select']) {

                $this->handler->select($this->options['select']);

            }

        }


        /**
         * @return |null 对象
         */

        //        public function getInstance() {
        //
        //            if (!($this->_instance instanceof self)) {
        //
        //                $this->_instance = new self();
        //
        //            }
        //
        //
        //            return $this->_instance;
        //
        //        }


        /**
         * 禁止外部克隆
         */

        public function __clone() {

            trigger_error('Clone is not allow!', E_USER_ERROR);

        }


        /**
         * @param $key
         *
         * @return array 获取键
         */

        public function keys($key) {

            return $this->handler->keys($key);

        }


        /**
         * @param $key
         *
         * @return bool 成功返回：TRUE;失败返回：FALSE
         */

        public function exists($key) {

            return $this->handler->exists($key);

        }


        /**
         * @param $key 数字递增存储键值键.
         *
         * @return int 返回自增后的值
         */

        public function incr($key) {

            return $this->handler->incr($key);

        }


        /**
         * @param $key 数字递减存储键值键.
         *
         * @return int 返回自减后的值
         */

        public function decr($key) {

            return $this->handler->decr($key);

        }


        /**
         * 写入缓存
         *
         * @param string $key 键名
         * @param string $value 键值
         * @param int $exprie 过期时间 0:永不过期
         *
         * @return bool
         */


        public function set($key, $value, $exprie = 0) {

            if ($exprie == 0) {

                $set = $this->handler->set($key, $value);

            } else {

                $set = $this->handler->setex($key, $exprie, $value);

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

        public function get($key) {

            $fun = is_array($key) ? 'Mget' : 'get';


            return $this->handler->{$fun}($key);

        }


        /**
         * 获取值长度
         *
         * @param string $key
         *
         * @return int
         */

        public function lLen($key) {

            return $this->handler->lLen($key);

        }


        /**
         * 将一个或多个值插入到列表头部
         *
         * @param $key
         * @param $value
         *
         * @return int
         */

        public function LPush($key, $value) {
            return $this->handler->lPush($key, $value);

        }


        /**
         * 移出并获取列表的第一个元素
         *
         * @param string $key
         *
         * @return string
         */

        public function lPop($key) {

            return $this->handler->lPop($key);

        }
    }