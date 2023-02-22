<?php

namespace App\Models;

use PDO;
use PDOException;

class Library
{
      protected $author;
      protected $isbn;
      protected $title;
      protected $year;
      protected $map;

      public function __construct($library = null)
      {
            //db
            if ($library != null && is_array($library)) {
                  $keys = array_keys($library);
                  $values = array_values($library);
                  //author
                  if ($keys[0] === "author" && ($values[0] != "" && $values[0] != null))
                        $this->author = $values[0];
                  else
                        $this->author = "";
                  //isbn
                  if ($keys[1] === "isbn" && ($values[1] != "" && $values[1] != null))
                        $this->isbn = $values[1];
                  else
                        $this->isbn = "";
                  //title
                  if ($keys[2] === "title" && ($values[2] != "" && $values[2] != null))
                        $this->title = $values[2];
                  else
                        $this->title = "";
                  //year
                  if ($keys[3] === "year" && ($values[3] != "" && $values[3] != null))
                        $this->year = $values[3];
                  else
                        $this->year = "";
                  //map
                  if (!empty($user[0]))
                        $this->map = $library[0];
            }
            //obj
            else if (is_object($library)) {
                  $this->author = $library->author;
                  $this->isbn = $library->isbn;
                  $this->title = $library->title;
                  $this->year = $library->year;
                  $this->map = [
                        "user" => $library->author,
                        "name" => $library->isbn,
                        "password" => $library->title,
                        "email" => $library->year,
                  ];
            }
      }

      public function setAuthor($author)
      {
            $this->author = $author;
      }
      public function getAuthor()
      {
            return $this->author;
      }
      public function setIsbn($isbn)
      {
            $this->isbn = $isbn;
      }
      public function getIsbn()
      {
            return $this->isbn;
      }
      public function setTitle($title)
      {
            $this->title = $title;
      }
      public function getTitle()
      {
            return $this->title;
      }
      public function setYear($year)
      {
            $this->year = $year;
      }
      public function getYear()
      {
            return $this->year;
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