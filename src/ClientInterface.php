<?php

namespace Ucwords\Zredis;

/**
 * 定义可操作 redis 的接口方法
 *
 * @method int         del($originalProtocol, array|string $keks)
 * @method int         exists($originalProtocol, $key)
 * @method int         expire($originalProtocol, $key, $seconds)
 * @method int         expireat($originalProtocol, $key, $timestamp)
 * @method array       keys($originalProtocol, $pattern)
 * @method int         pexpire($originalProtocol, $key, $milliseconds)
 * @method int         pexpireat($originalProtocol, $key, $timestamp)
 * @method string|null randomkey($originalProtocol)
 * @method mixed       rename($originalProtocol, $key, $target)
 * @method int         renamenx($originalProtocol, $key, $target)
 * @method array       scan($originalProtocol, $cursor, array $options = null)
 * @method int         ttl($originalProtocol, $key)
 * @method mixed       type($originalProtocol, $key)
 * @method int         append($originalProtocol, $key, $value)
 * @method int         decr($originalProtocol, $key)
 * @method int         decrby($originalProtocol, $key, $decrement)
 * @method string|null get($originalProtocol, $key)
 * @method int         incr($originalProtocol, $key)
 * @method int         incrby($originalProtocol, $key, $increment)
 * @method string      incrbyfloat($originalProtocol, $key, $increment)
 * @method array       mget($originalProtocol, array $keys)
 * @method mixed       mset($originalProtocol, array $dictionary)
 * @method int         msetnx($originalProtocol, array $dictionary)
 * @method mixed       set($originalProtocol, $key, $value, $expireResolution = null, $expireTTL = null, $flag = null)
 * @method int         setex($originalProtocol, $key, $seconds, $value)
 * @method int         setnx($originalProtocol, $key, $value)
 * @method int         strlen($originalProtocol, $key)
 * @method int         hdel($originalProtocol, $key, array $fields)
 * @method int         hexists($originalProtocol, $key, $field)
 * @method string|null hget($originalProtocol, $key, $field)
 * @method array       hgetall($originalProtocol, $key)
 * @method array       hkeys($originalProtocol, $key)
 * @method int         hlen($originalProtocol, $key)
 * @method array       hmget($originalProtocol, $key, array $fields)
 * @method mixed       hmset($originalProtocol, $key, array $dictionary)
 * @method array       hscan($originalProtocol, $key, $cursor, array $options = null)
 * @method int         hset($originalProtocol, $key, $field, $value)
 * @method int         hsetnx($originalProtocol, $key, $field, $value)
 * @method int         hstrlen($originalProtocol, $key, $field)
 * @method int         llen($originalProtocol, $key)
 * @method string|null lpop($originalProtocol, $key)
 * @method int         lpush($originalProtocol, $key, array $values)
 * @method int         lpushx($originalProtocol, $key, array $values)
 * @method array       lrange($originalProtocol, $key, $start, $stop)
 * @method int         lrem($originalProtocol, $key, $count, $value)
 * @method mixed       lset($originalProtocol, $key, $index, $value)
 * @method mixed       ltrim($originalProtocol, $key, $start, $stop)
 * @method string|null rpop($originalProtocol, $key)
 * @method string|null rpoplpush($originalProtocol, $source, $destination)
 * @method int         rpush($originalProtocol, $key, array $values)
 * @method int         rpushx($originalProtocol, $key, array $values)
 * @method int         sadd($originalProtocol, $key, array $members)
 * @method int         scard($originalProtocol, $key)
 * @method array       sdiff($originalProtocol, array|string $keys)
 * @method int         sdiffstore($originalProtocol, $destination, array|string $keys)
 * @method array       sinter($originalProtocol, array|string $keys)
 * @method int         sinterstore($originalProtocol, $destination, array|string $keys)
 * @method int         sismember($originalProtocol, $key, $member)
 * @method array       smembers($originalProtocol, $key)
 * @method int         smove($originalProtocol, $source, $destination, $member)
 * @method string|null spop($originalProtocol, $key, $count = null)
 * @method string|null srandmember($originalProtocol, $key, $count = null)
 * @method int         srem($originalProtocol, $key, $member)
 * @method array       sscan($originalProtocol, $key, $cursor, array $options = null)
 * @method array       sunion($originalProtocol, array|string $keys)
 * @method int         sunionstore($originalProtocol, $destination, array|string $keys)
 * @method int         zadd($originalProtocol, $key, array $membersAndScoresDictionary)
 * @method int         zcard($originalProtocol, $key)
 * @method string      zcount($originalProtocol, $key, $min, $max)
 * @method string      zincrby($originalProtocol, $key, $increment, $member)
 * @method int         zinterstore($originalProtocol, $destination, array|string $keys, array $options = null)
 * @method array       zrange($originalProtocol, $key, $start, $stop, array $options = null)
 * @method array       zrangebyscore($originalProtocol, $key, $min, $max, array $options = null)
 * @method int|null    zrank($originalProtocol, $key, $member)
 * @method int         zrem($originalProtocol, $key, $member)
 * @method int         zremrangebyrank($originalProtocol, $key, $start, $stop)
 * @method int         zremrangebyscore($originalProtocol, $key, $min, $max)
 * @method array       zrevrange($originalProtocol, $key, $start, $stop, array $options = null)
 * @method array       zrevrangebyscore($originalProtocol, $key, $max, $min, array $options = null)
 * @method int|null    zrevrank($originalProtocol, $key, $member)
 * @method int         zunionstore($originalProtocol, $destination, array|string $keys, array $options = null)
 * @method string|null zscore($originalProtocol, $key, $member)
 * @method array       zscan($originalProtocol, $key, $cursor, array $options = null)
 * @method array       zrangebylex($originalProtocol, $key, $start, $stop, array $options = null)
 * @method array       zrevrangebylex($originalProtocol, $key, $start, $stop, array $options = null)
 * @method int         zremrangebylex($originalProtocol, $key, $min, $max)
 * @method int         zlexcount($originalProtocol, $key, $min, $max)
 * @method mixed       discard($originalProtocol)
 * @method array|null  exec($originalProtocol)
 * @method mixed       multi($originalProtocol)
 * @method mixed       unwatch($originalProtocol)
 * @method mixed       watch($originalProtocol, $key)
 * @method mixed       eval($originalProtocol, $script, $numkeys, $keyOrArg1 = null, $keyOrArgN = null)
 * @method mixed       evalsha($originalProtocol, $script, $numkeys, $keyOrArg1 = null, $keyOrArgN = null)
 * @method mixed       script($originalProtocol, $subcommand, $argument = null)
 * @method mixed       auth($originalProtocol, $password)
 * @method string      echo($originalProtocol, $message)
 * @method mixed       ping($originalProtocol, $message = null)
 * @method mixed       select($originalProtocol, $database)
 * @method mixed       bgrewriteaof($originalProtocol)
 * @method mixed       bgsave($originalProtocol)
 * @method mixed       client($originalProtocol, $subcommand, $argument = null)
 * @method mixed       config($originalProtocol, $subcommand, $argument = null)
 * @method int         dbsize($originalProtocol)
 * @method mixed       flushall($originalProtocol)
 * @method mixed       flushdb($originalProtocol)
 * @method array       info($originalProtocol, $section = null)
 * @method int         lastsave($originalProtocol )
 * @method mixed       save($originalProtocol)
 * @method mixed       slaveof($originalProtocol, $host, $port)
 * @method mixed       slowlog($originalProtocol, $subcommand, $argument = null)
 * @method array       time($originalProtocol )
 * @method array       command($originalProtocol)
 * @package Zredis
 */

interface ClientInterface
{

    public function disconnect();


    public function executeCommand($command);


    public function __call($method, $arguments);

    public function writeRequest($command);

    public function readResponse();

}