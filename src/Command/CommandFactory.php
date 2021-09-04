<?php

namespace Ucwords\Zredis\Command;


class CommandFactory
{

    /** @var string 命令类路基 */
    protected $command;

    /** @var string 命令名称 */
    protected $commandID;

    /** @var array 参数 */
    protected $arguments;

    public function __construct(string $commandID, array $arguments)
    {

        $this->command      = "Ucwords\Zredis\Command\Commands\\".strtoupper($commandID);
        $this->commandID    = $commandID;
        $this->arguments    = $arguments;
    }


    public function getCommand()
    {
        if (! class_exists($this->command)) {
            throw new \Exception("命令 `$this->commandID` 还没被支持或者请检查拼写是否正确");
        }

        return new $this->command($this->commandID, $this->arguments);
    }
}