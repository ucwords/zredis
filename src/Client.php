<?php

namespace Ucwords\Zredis;

use Ucwords\Zredis\Connection\Connection;
use Ucwords\Zredis\Command\CommandFactory;
use Ucwords\Zredis\Colors\Colors;


class Client implements ClientInterface
{

    /** @var array 初始化选贤*/
    private $options;

    /** @var Connection 连接资源 */
    private $connection;

    /** @var bool 是否以原始报文输出 */
    private $printOriginal = false;

    /** @var array 读结果集 */
    private $readRes = [];

    /** @var array 写结果集 */
    private $writeRes = [];


    public function __construct($parameters = [])
    {
        $this->options    = self::createOptions($parameters);
        $this->connection = self::createConnection();

    }

    protected function createConnection()
    {
        return Connection::createResource($this->options);

    }

    protected static function createOptions($options) : array
    {
        $default = include_once "config/connection.php";

        if (empty($options['scheme']) || (! in_array($options['scheme'], ['tcp', 'unix']))) {
            $options['scheme'] = $default['scheme'];
        }

        if (empty($options['host'])) {
            $options['host'] = $default['host'];
        }

        if (empty($options['port'])) {
            $options['port'] = $default['port'];
        }

        if (empty($options['host'])) {
            $options['database'] = $default['database'];
        }

        return $options;
    }

    public function __call($commandID, $arguments)
    {

        $commandHandle = $this->createCommand($commandID, $arguments);
        $res = $this->executeCommand($commandHandle);

        if (! $this->printOriginal) return $res;

        // 格式化原始报文
        $originalCommand = $this->getCommand($commandHandle);
        $colors = new Colors();
        echo $colors->getColoredString("-----------请求 开始 ----------\r\n", "cyan");
        echo $colors->getColoredString("请求原始报文: {$originalCommand}\r\n", "cyan");
        echo $colors->getColoredString("请求格式化为 Redis 报文: \r\n{$this->writeRes}", "cyan");
        echo $colors->getColoredString("\r\n----------- 请求 结束 ----------\r\n", "cyan");

        echo $colors->getColoredString("\r\n----------- 响应 开始 ----------\r\n", "cyan");

        echo $colors->getColoredString("响应原始报文: {$res}\r\n", "cyan");

        $read = "";
        foreach ($this->readRes as $item) {
            $read .= "\r\n{$item}";
        }

        echo $colors->getColoredString("响应格式化为 Redis 报文: {$read}", "cyan");
        echo $colors->getColoredString("\r\n----------- 响应 结束 ----------", "cyan");


    }

    public function getCommand($commandHandle)
    {
        $arguments = $commandHandle->getArguments();

        $arg = ' ';
        foreach ($arguments as $key => $argument) {
            if ($key == 0) continue;
            $arg .= "{$argument} ";
        }
        return $commandHandle->getName(). rtrim($arg);
    }

    public function executeCommand($command)
    {
        $this->writeRequest($command);

        return $this->readResponse();

    }

    public function createCommand($commandID, $arguments = [])
    {
        $factory = new CommandFactory($commandID, $arguments);
        return $factory->getCommand();

    }

    public function writeRequest($command)
    {
        $commandName = $command->getName();
        $arguments = $command->getArguments();

        $this->printOriginal = $arguments[0];

        $cmdlen = strlen($commandName);
        $reqlen = count($arguments) + 1 - 1; // 减去 o_protocol 这个自定义参数

        $buffer = "*{$reqlen}\r\n\${$cmdlen}\r\n{$commandName}\r\n";

        // 处理参数
        foreach ($arguments as $key => $argument) {

            // 第 0 个是打印标志
            if ($key == 0) continue;

            $arglen = strlen($argument);
            $buffer .= "\${$arglen}\r\n{$argument}\r\n";
        }

        if ($this->printOriginal) {

            $this->writeRes = $buffer;
        }

        $this->write($buffer);

    }


    protected function write($buffer)
    {

        while (($length = strlen($buffer)) > 0) {

            $written = @fwrite($this->connection, $buffer);

            if ($length === $written) {
                return;
            }

            if ($written === false || $written === 0) {
                throw new \Exception('写入 Redis 失败');
            }

            $buffer = substr($buffer, $written);

        }

    }

    public function readResponse()
    {
        return $this->read();

    }

    public function read()
    {
        $socket = $this->connection;
        $chunk = fgets($socket);

        if ($chunk === false || $chunk === '') {

            throw new \Exception("读取失败。");
        }

        $prefix = $chunk[0];
        $payload = substr($chunk, 1, -2);

        switch ($prefix) {
            case '+':
                    // 'OK', 'QUEUED'=
                // 写入响应结果集
                    if ($this->printOriginal) {
                        array_push($this->readRes, "+{$payload}");
                    } else {
                        return $payload;
                    }
                    break;

            case '$':

                $size = (int) $payload;

                // 写入响应结果集
                if ($this->printOriginal) array_push($this->readRes, "\${$size}");

                if ($size === -1) return;

                $bulkData = '';
                $bytesLeft = ($size += 2);

                do {

                    $chunk = fread($socket, min($bytesLeft, 4096));

                    if ($chunk === false || $chunk === '') {
                        throw new \Exception("读取失败。");
                    }

                    $bulkData .= $chunk;
                    $bytesLeft = $size - strlen($bulkData);

                    // 写入响应结果集
                    if ($this->printOriginal) array_push($this->readRes, $bulkData);


                } while ($bytesLeft > 0);

                return substr($bulkData, 0, -2);

            case '*':

                $count = (int) $payload;

                // 写入响应结果集
                if ($this->printOriginal) array_push($this->readRes, "*{$count}");

                if ($count === -1) return;

                $multibulk = array();

                for ($i = 0; $i < $count; ++$i) {
                    $multibulk[$i] = $this->read();
                }

                return $multibulk;

            case ':':

                $integer = (int) $payload;

                // 写入响应结果集
                if ($this->printOriginal) array_push($this->readRes, ":{$integer}");

                return $integer;

            case '-':

                if ($this->printOriginal) {
                    array_push($this->readRes, "-");
                    array_push($this->readRes, "异常信息：{$payload}");

                } else {
                    throw new \Exception($payload);
                }

                break;

            default:

                throw new \Exception("未知的响应前缀: '$prefix'.");
        }
    }

    public function disconnect()
    {
        fclose($this->connection);
    }

    public function __destruct()
    {
        $this->disconnect();
    }

}
