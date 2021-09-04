## zredis
使用 PHP 实现的 redis 客户端，它能够清晰告诉你请求 redis 和接受到 redis 响应的原始报文格式。

## 简单使用

**加载代码库**

```php
require '../vendor/autoload.php';
```

**创建连接**
```php

$singleServer = [
    'host' => '127.0.0.1',
    'port' => 6379,
];

$client = new Ucwords\Zredis\Client($singleServer);

```

**使用**
```php
<?php

require '../vendor/autoload.php';

$singleServer = [
    'host' => '127.0.0.1',
    'port' => 6379,
];

$client = new Ucwords\Zredis\Client($singleServer);

// 单行回复 示例
$client->set(true, 'library', 'library');

```

## 原始报文

该包部分代码借鉴于 predis, 命令及参数遵循 redis 格式和要求。不同的是每个命令的第一个参数控制是否打印原始报文。

当使用时候若想查看请求或响应的原始报文，第一个参数请赋值为 true。
当想正常使用如像 predis 时候，第一个参数请赋值为 false。

简单例子如下：
```php 

// 下例将会输出原始报文

$client->set(true, 'library', 'library');
$client->get(true, 'library');


// 下例将如 predis 一样正常使用

$client->set(false, 'library', 'library');
$client->get(false, 'library');

```

**单行回复 示例**

Redis 回复的第一个字节为 “+” 表示单行回复。

```php 
// 单行回复 示例
$client->set(true, 'library', 'library');

格式化结果输出：

-----------请求 开始 ----------
请求原始报文: SET library library
请求格式化为 Redis 报文: 
*3
$3
SET
$7
library
$7
library

----------- 请求 结束 ----------

----------- 响应 开始 ----------
响应原始报文: 
响应格式化为 Redis 报文: 
+OK
----------- 响应 结束 ---------- 

```

**错误消息 示例**

Redis 回复的第一个字节为 “-” 表示错误消息。 比如我们对一个 string 类似的key，执行 hget 指令。

```php 

$client->set(true, 'library', 'library');

// 错误回复
$client->hgetall(true, 'library');


格式化结果输出：

-----------请求 开始 ----------
请求原始报文: HGETALL library
请求格式化为 Redis 报文: 
*2
$7
HGETALL
$7
library

----------- 请求 结束 ----------

----------- 响应 开始 ----------
响应原始报文: 
响应格式化为 Redis 报文: 
-

异常信息：WRONGTYPE Operation against a key holding the wrong kind of value
----------- 响应 结束 ----------    

```

**整型数字消息 示例**

Redis 回复的第一个字节为 “:” 表示整型数字回复。 如判断一个 key 是否存在。

```php 

$client->set(true, 'library', 'library');

$client->exists(true, 'library');

格式化结果输出：
-----------请求 开始 ----------
请求原始报文: EXISTS library
请求格式化为 Redis 报文: 
*2
$6
EXISTS
$7
library

----------- 请求 结束 ----------

----------- 响应 开始 ----------
响应原始报文: 1
响应格式化为 Redis 报文: 
:1

----------- 响应 结束 ----------

```


**批量回复消息 示例**

Redis 回复的第一个字节为 “$” 表示批量回复。 

```php 

$client->set(true, 'library', 'library');

// 批量回复 示例
$client->get(true, 'library');

格式化结果输出：

-----------请求 开始 ----------
请求原始报文: GET library
请求格式化为 Redis 报文: 
*2
$3
GET
$7
library

----------- 请求 结束 ----------

----------- 响应 开始 ----------
响应原始报文: library
响应格式化为 Redis 报文: 
$7
library

----------- 响应 结束 ----------

```

**多个批量回复消息 示例**

Redis 回复的第一个字节为 “*” 表示多个批量回复，如使用 hgetall 指令。

```php 

$client->hgetall(true, 'test_hash');

格式化结果输出：

-----------请求 开始 ----------
请求原始报文: HGETALL test_hash
请求格式化为 Redis 报文: 
*2
$7
HGETALL
$9
test_hash

----------- 请求 结束 ----------

----------- 响应 开始 ----------
响应原始报文: Array
响应格式化为 Redis 报文: 
*8
$4
name

$5
zhang

$3
age

$2
18

$4
attr

$1
2

$8
attr\0fa

$1
2

----------- 响应 结束 ----------

```
