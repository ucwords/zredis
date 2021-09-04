<?php



require '../vendor/autoload.php';

$singleServer = [
    'host' => '127.0.0.1',
    'port' => 6379,
];

$client = new Ucwords\Zredis\Client($singleServer);

// 单行回复 示例
//$client->set(true, 'library', 'library');

// 批量回复 示例
// $client->get(true, 'library');

// 回复 整型数字
// $client->exists(true, 'test_hash');


// hash set
// $client->hset(true, 'test_hash', 'attr', 2);

// hash set
// $client->hget(true, 'test_hash', 'attr');

// 错误回复
// $client->hgetall(true, 'library');

// 二进制安全
//$client->set(true, "test\0test", 'test');
// $client->get(true, "test\0test");


