<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class RedisService
{
    /**
     * Redisから値を取得し、存在しない場合は設定する
     * @param string $key
     * @param callable $value_fn
     * @param callable $cache_fn
     * @return mixed
     * @throws \Exception
     */
    public function get_and_set($key, $value_fn, $cache_fn = null)
    {
        try {
            if (Redis::exists($key)) {
                $result = json_decode(Redis::get($key), true);
                if ($cache_fn) {
                    $result = $cache_fn($result);
                }
            } else {
                $result = $value_fn();
                Redis::set($key, json_encode($result));
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        return $result;
    }

    public function set($key, $value)
    {
        Redis::set($key, json_encode($value));
    }

    /**
     * Redisのキーを削除
     * @param string $key
     * @return void
     */
    public function clear($key)
    {
        Redis::del($key);
    }

    /**
     * Redisのパターンにマッチするキーを削除
     * @param string $pattern
     * @return void
     */
    public function clear_pattern($pattern)
    {
        $keys = Redis::keys($pattern);
        foreach ($keys as $key) {
            Redis::del($key);
        }
    }
}
