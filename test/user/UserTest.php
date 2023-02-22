<?php
/*
* MVC$ ./vendor/bin/phpunit --verbose test
*/
use App\Registry;
use App\Container;

use App\Models\User;

use App\Database\DB;
use App\Database\Connection;

use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
      public function testConstructor(): void
      {
            //empty obj
            $user = new User();
            if ($this->assertNotEmpty($user)) {
                  //empty fields
                  $this->assertEmpty($user->__toString());
            }

            //db
            $data = ["id" => "1", "name" => "test", "password" => "test", "email" => "test@test.com"];

            var_dump($data);
            var_dump(new User($data));
            $this->assertInstanceOf(User::class, new User($data));
            
            //obj
            $this->assertInstanceOf(User::class, new User(new User($user)));
      }
}