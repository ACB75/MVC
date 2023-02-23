<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Library;

class UserLibraryController extends Controller
{
      function index()
      {
            if ($this->session->get("User"))
                  return $this->view('userlibrary', ["user" => $this->session->get("User")]);
            else
                  return $this->view('userlibrary', []);
      }


      function create()
      {
            //index/library_create
            if ($this->request->getParams() != null && $this->session->exists("User")) {
                  $params = $this->request->getParams();
                  $user = $this->session->get("User");
                  foreach ($params as $key => $value) {
                        $result = $this->getDB()->selectWhere('Library', [$key => $value], true);
                        if ($result != null)
                              $library = new Library($result[0]);
                        else
                              return $this->view("error", ["error" => "ISBN not found."]);
                  }

                  if ($library != null) {
                        $data = ["id_user" => $user->getId(), "isbn_library" => $library->getIsbn()];
                        $result = $this->getDB()->insert('User_Library', $data);
                        if ($result != -1)
                              return $this->view('userlibrary', ['library' => $library, 'user' => $user]);
                        else
                              return $this->view('error', ['error' => "userlibrary/create no res", "user" => $user]);
                  } else
                        return $this->view('error', ['error' => "userlibrary/create no lib no changes", "user" => $user]);
            }
      }

      function read()
      {
            if (!$this->session->exists('User'))
                  return $this->view('error', ['error' => "Not logged in."]);

            $user = $this->session->get('User');
            $data = ["id_user" => $user->getId()];
            $res_ul = $this->getDB()->selectWhere('User_Library', $data, true);
            $res_l = $this->getDB()->selectAll('Library', $data, true);
            if (!empty($res_ul) && !empty($res_l)) {
                  $library = [];
                  foreach ($res_ul as $ul) {
                        if ($ul->returned_day == null && $ul->isbn_library != null) {
                              foreach ($res_l as $lib) {
                                    if ($lib->isbn == $ul->isbn_library) {
                                          $library[] = new Library($lib);
                                    }
                              }
                        }
                  }
                  return $this->view('userlibrary', ['library' => $library, 'user' => $this->session->get('User')]);
            } else
                  return $this->view('error', ['error' => "No result.", 'user' => $this->session->get('User')]);
      }

      function update()
      {
            if ($this->request->getParams() != null && $this->session->exists("User")) {
                  $params = $this->request->getParams();
                  $user = $this->session->get("User");
                  $data = ["id_user" => $user->getId(), "returned_day" => $params["date"], "isbn_library" => $params["isbn"],];
                  $result = $this->getDB()->update('User_Library', $data, 'isbn_library');
                  $library = [];
                  if (!empty($result)) {
                        return $this->read();
                  }
            }
      }
}