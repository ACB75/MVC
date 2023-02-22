<?php
namespace App;
use InvalidArgumentException;
abstract class Registry
{
      protected static $services = [];
      protected static $allowedKeys = [];

      public static function bind($key, $value)
      {
            static::$services[$key] = $value;
            static::$allowedKeys[] = $key;
      }

      public static function set(string $key, $value)
      {
            if (!in_array($key, self::$allowedKeys)) {
                  throw new InvalidArgumentException('Invalid key given');
            }
            self::$services[] = $key;
      }

      public static function get(string $key)
      {
            $allowedKeys = self::$allowedKeys;
            if (!in_array($key, $allowedKeys) || !isset(self::$services[$key])) {
                  throw new InvalidArgumentException('Invalid key given');
            }
            return self::$services[$key];
      }
}