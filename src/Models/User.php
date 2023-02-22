<?php

namespace App\Models;

use PDO;
use PDOException;

class User
{
      protected $id;
      protected $name;
      protected $email;
      protected $password;
      protected $map;

      public function __construct($user = null)
      {
            //db
            if ($user != null && is_array($user)) {
                  $keys = array_keys($user);
                  $values = array_values($user);
                  //id
                  if ($keys[0] === "id" && ($values[0] != "" && $values[0] != null))
                        $this->id = $values[0];
                  else
                        $this->id = "";
                  //name
                  if ($keys[1] === "name" && ($values[1] != "" && $values[1] != null))
                        $this->name = $values[1];
                  else
                        $this->name = "";
                  //email
                  if ($keys[3] === "email" && ($values[3] != "" && $values[3] != null))
                        $this->email = $values[3];
                  else
                        $this->email = "";
                  //password
                  if ($keys[2] === "password" && ($values[2] != "" && $values[2] != null))
                        $this->password = $values[2];
                  else
                        $this->password = "";
                  //map
                  if (!empty($user[0]))
                        $this->map = $user[0];
            }
            //obj
            else if (is_object($user)) {
                  $this->id = $user->id;
                  $this->name = $user->name;
                  $this->password = $user->password;
                  $this->email = $user->email;
                  $this->map = [
                        "user" => $user->id,
                        "name" => $user->name,
                        "password" => $user->password,
                        "email" => $user->email,
                  ];
            }
      }

      public function setId($id)
      {
            $this->id = $id;
      }
      public function getId()
      {
            return $this->id;
      }
      public function setName($name)
      {
            $this->name = $name;
      }
      public function getName()
      {
            return $this->name;
      }
      public function setEmail($email)
      {
            $this->email = $email;
      }
      public function getEmail()
      {
            return $this->email;
      }
      public function setPassword($password)
      {
            $this->password = $password;
      }
      public function getPassword()
      {
            return $this->password;
      }

      public function setMap($map)
      {
            $this->map = $map;
      }
      public function getMap()
      {
            return $this->map;
      }
}