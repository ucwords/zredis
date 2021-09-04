<?php


namespace Ucwords\Zredis\Command\Commands;


interface CommandInterface
{

    /**
     * 命令名定义
     *
     * @return mixed
     */
    function getName();


    /**
     * 参数解析
     *
     * @return mixed
     */
    function getArguments();

}