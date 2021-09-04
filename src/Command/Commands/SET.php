<?php

namespace Ucwords\Zredis\Command\Commands;

use Ucwords\Zredis\Exception\ParamException;

class SET implements CommandInterface
{
    protected $arguments;

    protected $commandName;

    public function __construct($commandID, $arguments)
    {
        $this->arguments   = $arguments;
        $this->commandName = $commandID;
    }

    public function getName()
    {
        return strtoupper($this->commandName);
    }


    public function getArguments()
    {
        if (! is_array($this->arguments) || empty($this->arguments)) {

            throw new ParamException("命令 {$this->getName()} 所需参数不是一个有效数组");
        }

        return $this->arguments;
    }

}