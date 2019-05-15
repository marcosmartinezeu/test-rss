<?php

namespace App\Service;


use Predis\Client;
use Psr\SimpleCache\CacheInterface;

class RedisCacheService implements CacheInterface
{
    /**
     * @var Client
     */
    private $client;


    /**
     * RedisCache constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $key
     * @param null | mixed $default
     * @return mixed|string
     */
    public function get($key, $default = null)
    {
        return $this->client->get($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param null|int $ttl
     * @return $this|bool
     */
    public function set($key, $value, $ttl = null)
    {
        if($ttl >0) {
            $this->client->setex($key, $ttl, $value);
        }else{
            $this->client->set($key, $value);
        }

        return $this;
    }

    /**
     * @param string $key
     * @return int
     */
    public function delete($key) : int
    {
        return $this->client->del(array($key));
    }

    /**
     * @return bool
     */
    public function clear() : bool
    {
        return $this->client->flushAll();
    }

    /**
     * @param iterable $keys
     * @param null $default
     *
     * @return array|iterable
     */
    public function getMultiple($keys, $default = null) : array
    {
        $values = [];

        foreach ($keys as $key)
        {
            $values[] = $this->get($key, $default);
        }

        return $values;
    }

    /**
     * @param iterable $values
     * @param null $ttl
     * @return bool|void
     */
    public function setMultiple($values, $ttl = null) : void
    {
        foreach ($values as $key => $value)
        {
            $this->set($key, $value, $ttl);
        }
    }


    /**
     * @param iterable $keys
     * @return bool|void
     */
    public function deleteMultiple($keys) : void
    {
        foreach ($keys as $key)
        {
            $this->delete($key);
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key) : bool
    {
        return $this->client->exists($key);
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}