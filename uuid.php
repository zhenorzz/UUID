<?php
/**
 * ID 生成策略 (请在64位系统中使用，否则需要修改generate函数，防止int溢出)
 * 毫秒级时间(41位) + 自增sequence(16位) + 机器码 (6位)。
 * 0           41           57         63 
 * +-----------+------------+----------+
 * |time       |sequence   |workid   |
 * +-----------+-----------+---------+
 * 前41bits是以微秒为单位的timestamp。
 * 接着16位sequence自增。
 * 最后6bits是机器码。
 * 机器码(6bits)标明最多只能有63台机器同时产生ID.
 *
 * auth: zhenorzz@gmail.com
 */
class uuid
{
    const timestampLeftShift = 14;
    const sequenceLeftShift = 6;
    //毫秒内自增数点的位数
    const sequenceBits = 16;
    //开始时间,固定一个小于当前时间的毫秒数即可
    const twepoch = 1399943202863;
    //要用静态变量
    static $lastTimestamp = -1;
    static $sequence = 0;

    private $workerId = 1;

    public function __construct($workerId = 1)
    {
        $this->workerId = $workerId;
    }

    public function generate()
    {
        $timestamp = $this->millisecond();
        $lastTimestamp = self::$lastTimestamp;
        //判断时钟是否正常
        if ($timestamp < $lastTimestamp) {
            throw new Exception("Clock moved backwards.  Refusing to generate id for %d milliseconds", ($lastTimestamp - $timestamp));
        }
         //生成唯一序列
         if ($lastTimestamp == $timestamp) {
            $sequenceMask = -1 ^ (-1 << self::sequenceBits);
            self::$sequence = (self::$sequence + 1) & $sequenceMask;
            if (self::$sequence == 0) {
                $timestamp = $this->tilNextMillis($lastTimestamp);
            }
        } else {
            self::$sequence = 0;
        }
        self::$lastTimestamp = $timestamp;

        $nextId = ((sprintf('%.0f', $timestamp) - sprintf('%.0f', self::twepoch)) << self:: timestampLeftShift ) | self::$sequence << self::sequenceLeftShift | $this->workerId;
        return $nextId;
    }

    private function millisecond()
    {
        $time = explode(' ', microtime());
        $time2= substr($time[0], 2, 3);
        return $time[1] . $time2;
    }

    //取下一毫秒
    private function tilNextMillis($lastTimestamp) {
        $timestamp = $this->timeGen();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->timeGen();
        }
        return $timestamp;
    }
}
