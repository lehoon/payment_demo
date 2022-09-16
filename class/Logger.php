<?php
/**
 * 文件描述: 日志工具类
 * Created by PhpStorm.
 * User: lehoon
 * Date: 2019/4/19 10:48
 */

//设置时区
ini_set('date.timezone', 'Asia/Shanghai');

interface ILoggerHandler {
    public function write($message);
}

//日志接口实现类
class CLoggerFileHandler implements ILoggerHandler {
    //文件句柄
    private $handler = null;

    /**
     * CLoggerFileHandler constructor.
     * @param null $file
     */
    public function __construct($file = '')
    {
        $this->handler = fopen($file, 'a+');
    }

    public function __destruct()
    {
        fclose($this->handler);
    }

    /**
     * 写日志到文件
     * @param $message
     */
    public function write($message) {
        fwrite($this->handler, $message, 4096);
    }
}

/**
 * 文件实现工具
 * Created by PhpStorm.
 * User: lehoon
 * Date: 2019/4/19 10:59
 */
class Logger
{
    private $handler = null;
    private $level = 15;
    private static $instance = null;

    private function __construct() {}
    private function __clone() {}

    /**
     * 初始化文件日志对象
     * @param null $filename
     * @param int $level
     */
    public static function InitFileLogger($filename = null, $level = 15) {
        if(!self::$instance instanceof self) {
            self::$instance = new self();
            self::$instance->handler = new CLoggerFileHandler($filename);
            self::$instance->level = $level;
        }
    }

    /**
     * debug日志
     * @param $msg
     */
    public static function DEBUG($msg) {
        self::$instance->write(1, $msg);
    }

    /**
     * info日志
     * @param $msg
     */
    public static function INFO($msg) {
        self::$instance->write(2, $msg);
    }

    /**
     * warn日志
     * @param $msg
     */
    public static function WARN($msg) {
        self::$instance->write(4, $msg);
    }

    /**
     * error日志
     * @param $msg
     */
    public static function ERROR($msg) {
        self::$instance->write(8, $msg);
    }

    /**
     * 写文件日志
     * @param $level
     * @param $message
     */
    protected function write($level, $message) {
        if($this->handler == null) {
            return;
        }

        if(($level & $this->level) == $level) {
            $message = '[' . date('Y-m-d H:i:s') . '][' . $this->getLevelMsg($level) . ']' . $message . PHP_EOL;
            //$msg = sprintf('[%s] [%s] %s%c', date('Y-m-d H:i:s'), $message, $level_msg, PHP_EOL);
            $this->handler->write($message);
        }
    }

    /**
     * 获取级别字符串
     * @param $level
     * @return string
     */
    private function getLevelMsg($level) {
        switch ($level) {
            case 1:
                return 'DEBUG';
                break;
            case 2:
                return 'INFO';
                break;
            case 4:
                return 'WARN';
                break;
            case 8:
                return 'ERROR';
                break;
            default:
                return 'UNKNOW';
                break;
        }
    }
}