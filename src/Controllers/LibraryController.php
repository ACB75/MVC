<?php

namespace App\Controllers;

use App\Models\Library;

class LibraryController extends Controller
{
      function index()
      {
            if ($this->session->get("User"))
                  return $this->view('user', ["user" => $this->session->get("User")]);
            else
                  return $this->view('library', []);
      }


      function create()
      {
            //index/library_create
            if ($this->isset_notEmpty_POST('csrf-token')) {
                  $token = filter_input(INPUT_POST, 'csrf-token', FILTER_SANITIZE_STRING);
                  if ($this->check_csrf($token))
                        return $this->view('error', ['error' => 'CSRFed!!!', "user" => $this->session->get("user")]);
            }

            if ($this->isset_notEmpty_POST('c_author') && $this->isset_notEmpty_POST('c_isbn') && $this->isset_notEmpty_POST('c_title') && $this->isset_notEmpty_POST('c_year')) {

                  $author = filter_input(INPUT_POST, 'c_author', FILTER_SANITIZE_STRING);
                  $isbn = filter_input(INPUT_POST, 'c_isbn', FILTER_SANITIZE_EMAIL);
                  $title = filter_input(INPUT_POST, 'c_title', FILTER_SANITIZE_STRING);
                  $year = filter_input(INPUT_POST, 'c_year', FILTER_SANITIZE_STRING);

                  $data = ["author" => $author, "isbn" => $isbn, "title" => $title, "year" => $year];

                  $library = $this->auth($data);

                  if ($library != null)
                        return $this->view('error', ['error' => 'Book with ISBN: ' . $isbn . ' already exists.', "user" => $this->session->get("user")]);

                  $result = $this->getDB()->insert('Library', $data);

                  if ($result == -1)
                        return $this->view('error', ['error' => 'Something went wrong with the process :(', "user" => $this->session->get("user")]);

                  $data = array_replace($data, ["isbn" => $result]);
                  $library[] = new Library($data);

                  if ($this->session->get('user'))
                        return $this->view('library', ['library' => $library, 'user' => $this->session->get('User')]);

                  return $this->view('library', ['library' => $library, 'user' => $this->session->get('User')]);
            }
      }

      function read()
      {
            if ($this->session->get('User')) {
                  $result = $this->getDB()->selectAll('Library');
                  if (!empty($result)) {
                        foreach ($result as $lib)
                              $library[] = new Library($lib);

                        return $this->view('library', ['library' => $library, 'user' => $this->session->get('User')]);
                  } else
                        return $this->view('library', ['error' => "No result."]);
            } else {
                  $result = $this->getDB()->selectAll('Library');
                  if (!empty($result)) {
                        foreach ($result as $lib)
                              $library[] = new Library($lib);

                        return $this->view('library', ['library' => $library, 'user' => $this->session->get('User')]);
                  } else
                        return $this->view('library', ['error' => "No result."]);
            }
      }


      function update()
      {
            //index/library_update
            if ($this->request->getParams() != null && $this->session->exists("User")) {

                  $params = $this->request->getParams();
                  $user = $this->session->get("User");

                  foreach ($params as $key => $value) {
                        $result = $this->getDB()->selectWhere('Library', [$key => $value], true);
                        $library = new Library($result[0]);
                  }

                  if ($result == null)
                        return $this->view("error", ["error" => "ISBN not found."]);

                  if ($this->isset_notEmpty_POST('csrf-token')) {
                        $token = filter_input(INPUT_POST, 'csrf-token', FILTER_SANITIZE_STRING);
                        if ($this->check_csrf($token))
                              return $this->view('error', ['error' => 'CSRFed!!!']);
                  }

                  $data = ["author" => "", "isbn" => $library->getIsbn(), "title" => "", "year" => ""];

                  if ($this->isset_notEmpty_POST("u_author")) {
                        $changes = true;
                        $author = $this->request->get("u_author");
                        if ($library->getAuthor() != $author && $author != "") {
                              $data = array_replace($data, ["author" => $author]);
                              $library->setAuthor($author);
                        }
                  }

                  if ($this->isset_notEmpty_POST('u_isbn')) {
                        $isbn = $this->request->get('u_isbn');
                        if ($library->getIsbn() == $isbn  && $isbn != "")
                              $data = array_replace($data, ["isbn" => $isbn]);
                        else
                              return $this->view("error", ["error" => "Can't update ISBN."]);

                  }

                  if ($this->isset_notEmpty_POST('u_title')) {
                        $changes = true;
                        $title = filter_input(INPUT_POST, 'u_title', FILTER_SANITIZE_STRING);
                        if ($library->getTitle() != $title  && $title != "") {
                              $data = array_replace($data, ["title" => $title]);
                              $library->setTitle($title);

                        }
                  }

                  if ($this->isset_notEmpty_POST('u_year')) {
                        $changes = true;
                        $year = filter_input(INPUT_POST, 'u_year', FILTER_SANITIZE_STRING);
                        if ($library->getYear() != $year  && $year != "") {
                              $data = array_replace($data, ["year" => $year]);
                              $library->setYear($year);
                        }
                  }

                  if ($library != null && $changes) {
                        $result = $this->getDB()->update('Library', $data, "isbn");
                        if ($result != null)
                              return $this->view('library', ['library' => $library, 'user' => $user]);
                        else
                              return $this->view('error', ['error' => "library/update no res", "user" => $user]);
                  } else
                        return $this->view('error', ['error' => "library/update no lib no changes", "user" => $user]);
            }
      }

      function delete()
      {
            $params = $this->request->getParams();
            $user = $this->session->get("User");
            //index/library_delete
            if ($params != null) {
                  foreach ($params as $key => $value) {
                        $res = $this->getDB()->delete('Library', $key, $value);
                  }
                  if ($res) {
                        return $this->read();
                  } else
                        return $this->view('error', ['error' => "library/delete res failed", "user" => $user]);
            }
            return $this->view('error', ['error' => "library/delete isbn null", "user" => $user]);
      }

      //Helpers && BL
      protected function isset_notEmpty_POST($value)
      {
            return isset($_POST[$value]) && !empty($_POST[$value]);
      }

      protected function auth($data)
      {
            try {
                  $result = $this->getDB()->selectWhere('Library', $data, true);
                  if (!empty($result)) {
                        return new Library($result[0]);
                  }

            } catch (\Exception $e) {
                  die($e->getMessage());
            }
      }


}