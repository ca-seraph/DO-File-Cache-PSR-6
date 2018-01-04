<?php

namespace rapidweb\RWFileCachePSR6;

use Psr\Cache\CacheItemInterface;

class CacheItem implements CacheItemInterface
{
    private $key;
    private $value;
    private $expires = 0;
    public $isDeferred = false;

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function get()
    {
        if ($this->isHit()===false) {
            return null;
        }

        return $this->value;
    }

    public function getExpires()
    {
        return $this->expires;
    }

    public function isHit()
    {
        if ($this->isDeferred) {
            if ($this->expires !== 0 && $this->expires <= time()) {
                return false;
            }
        }

        return $this->value !== false;
    }

    public function set($value)
    {
        if ($this->isDeferred) {
            return;
        }

        $this->value = $value;
        return $this;
    }

    public function expiresAt($expiration)
    {
        if ($expiration==null) {
            $this->expires = 0;
        } else {
            $this->expires = $expiration->getTimestamp();
        }
        return $this;
    }

    public function expiresAfter($time)
    {
        if (is_integer($time)) {
            $this->expires = time()+$time;
        }
        return $this;
    }

}