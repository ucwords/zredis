<?php


namespace Ucwords\Zredis\Connection;


class Connection
{

    public static function createResource($options)
    {

        $address = static::streamInitializer($options);

        $context = stream_context_create(['socket' => ['tcp_nodelay' => false]]);

        if (!$resource = @stream_socket_client($address, $errno, $errstr, $options['timeout'] ?? 3,
            STREAM_CLIENT_CONNECT, $context)) {

            throw new \Exception(trim($errstr), $errno);
        }

        if (isset($options['read_write_timeout'])) {
            $rwtimeout = (float) $options['read_write_timeout'];
            $rwtimeout = $rwtimeout > 0 ? $rwtimeout : -1;
            $timeoutSeconds = floor($rwtimeout);
            $timeoutUSeconds = ($rwtimeout - $timeoutSeconds) * 1000000;
            stream_set_timeout($resource, $timeoutSeconds, $timeoutUSeconds);
        }

        return $resource;

    }


    protected static function streamInitializer($options)
    {

        static::assertScheme($options);

        if ($options['scheme'] === 'unix') {
            return "{$options['scheme']}:{$options['host']}";
        }

        if (filter_var($options['host'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return "{$options['scheme']}://{$options['host']}:{$options['port']}";
        }

        return "{$options['scheme']}://{$options['host']}:{$options['port']}";
    }


    protected static function assertScheme($options)
    {
        switch ($options['scheme']) {
            case 'tcp':
            case 'unix':
                break;

            default:
                throw new \InvalidArgumentException("Invalid scheme: '{$options['scheme']}.");
        }

    }



}