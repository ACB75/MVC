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
      }

      function delete()
      {
            $isbn = $this->request->getParams();
            $user = $this->session->get("User");
            //index/library_delete
            if ($isbn != "") {
                  $res = $this->getDB()->delete('User', 'isbn', $isbn);
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
				$data = array_replace($data, ["isbn" => $result[0]->isbn]);
				return new Library($result[0]);
			}

		} catch (\Exception $e) {
			die($e->getMessage());
		}
	}

      
}