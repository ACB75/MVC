<?php

namespace App\Controllers;

class IndexController extends Controller
{
	public function index()
	{
		if ($this->session->get("User"))
			return $this->view('index', ["user" => $this->session->get("User")]);
		else
			return $this->view('index', []);
	}

	function register()
	{
		$this->session->set('csrf-token', md5(uniqid(mt_rand(), true)));

		//User/Create
		$this->form = $this->getFormBuilder();
		$this->form->open('/user/create', 'POST');
		$this->form->csrf($this->session->get("csrf-token"));
		$this->form->label('Name');
		$this->form->input('string', 'c_name', '', true, 'JohnDoe');
		$this->form->label('Email');
		$this->form->input('email', 'c_email', '', true, 'johndoe@sample.com');
		$this->form->label('Password');
		$this->form->input('password', 'c_password', '', '*****');
		$this->form->submit('userCreate', 'Register');
		$this->form->close();

		return $this->view('index', ["form" => $this->form]);
	}
	function login()
	{
		$this->session->set('csrf-token', md5(uniqid(mt_rand(), true)));

		//User/Read
		$this->form = $this->getFormBuilder();
		$this->form->open('/user/read', 'POST');
		$this->form->csrf($this->session->get("csrf-token"));
		$this->form->label('Username - Email');
		$this->form->input('string', 'r_name', '', true, 'JohnDoe or JohnDoe@JohnDoe.com');
		$this->form->label('Password');
		$this->form->input('password', 'r_password', '', true, '*****');
		$this->form->label('Remember');
		$this->form->checkbox('checkbox', 'remember', 'true');
		$this->form->submit('userRead', 'Login');
		$this->form->close();

		return $this->view('index', ["form" => $this->form]);
	}

	function dashboard()
	{
		if ($this->session->exists("User")) {
			$user = $this->session->get("User");
			//User/Update
			$this->form = $this->getFormBuilder();
			$this->form->open('/user/update', 'POST');
			$this->form->csrf($this->session->get("csrf-token"));
			$this->form->label('Current Name');
			$this->form->input('string', '', $user->getName(), false, '', true);
			$this->form->label('Update Name');
			$this->form->input('string', 'u_name', '', false, '');
			$this->form->label('Current Email');
			$this->form->input('string', '', $user->getEmail(), false, '', true);
			$this->form->label('Update Email');
			$this->form->input('email', 'u_email', '', false, '');
			$this->form->label('Current Password');
			$this->form->input('password', 'curr_password', '', false, '*****');
			$this->form->label('New Password');
			$this->form->input('password', 'u_password', '', false, '*****');
			$this->form->label('Retype Password');
			$this->form->input('password', 're_password', '', false, '*****');
			$this->form->submit('userUpdate', 'Update');
			$this->form->close();
			//User/Delete
			$this->form->open('/user/delete', 'POST');
			$this->form->csrf($this->session->get("csrf-token"));
			$this->form->submit('userDelete', 'Delete');
			$this->form->close();
			return $this->view('index', ["form" => $this->form, "user" => $user]);
		}
	}

	function logout()
	{
		$this->session->destroy();

		return $this->view('index', ["See ya :)"]);
	}

	function library_create()
	{
		if ($this->session->exists("User")) {
			$user = $this->session->get("User");

			if ($user->getName() != 'master')
				return $this->view("error", ["error" => "Try with Sudo :D."]);

			//Library/Create
			$this->form = $this->getFormBuilder();
			$this->form->open('/library/create', 'POST');
			$this->form->csrf($this->session->get("csrf-token"));
			$this->form->label('Author');
			$this->form->input('string', 'c_author', '', true, 'Miguel de Cervantes');
			$this->form->label('ISBN');
			$this->form->input('string', 'c_isbn', '', true, '9781234567897');
			$this->form->label('Title');
			$this->form->input('string', 'c_title', '', true, 'El Ingenioso Hidalgo Don Quijote de la Mancha');
			$this->form->label('Year');
			$this->form->input('string', 'c_year', '', true, '2001');
			$this->form->submit('libraryCreate', 'Create');
			$this->form->close();

			return $this->view('index', ["form" => $this->form, "user" => $user]);
		}
	}

	function library_read()
	{
		//Library/Read
		header('Location:/library/read');
	}

	function library_update()
	{
		if ($this->session->exists("User")) {
			$user = $this->session->get("User");
			if ($user->getName() === 'master') {
				//Library/Update
				$params = $this->request->getParams();
				$location = '/library/update/';
				foreach ($params as $key => $value) {
					$location .= $key . '/' . $value;
					$this->form = $this->getFormBuilder();
					$this->form->open($location, 'POST');
					$this->form->csrf($this->session->get("csrf-token"));
					$this->form->label('Update Author');
					$this->form->input('string', 'u_author', '', true, 'Alonso Fernández de Avellaneda');
					$this->form->label('Current ISBN');
					$this->form->input('string', 'nu_isbn', $value, false, $value, true);
					$this->form->label('Update Title');
					$this->form->input('string', 'u_title', '', true, 'El Quijote de Avellaneda');
					$this->form->label('Update Year');
					$this->form->input('string', 'u_year', '', false, '1605');
					$this->form->submit('libraryUpdate', 'Update');
					$this->form->close();
				}
				return $this->view('index', ["form" => $this->form, "user" => $user]);
			} else
				return $this->view('error', ["error" => "Try with sudo :D", "user" => $user]);
		} else
			return $this->view('error', ["error" => "Not logged in."]);
	}

	function library_delete()
	{
		if ($this->session->exists("User")) {
			$params = $this->request->getParams();
			$location = '/library/delete/';
			foreach ($params as $key => $value) {
				$location .= $key . '/' . $value;
			}
			header('Location:' . $location);
		} else
			return $this->view('error', ["error" => "Not logged in."]);
	}

	function user_x_library()
	{
		if ($this->session->exists("User")) {	
			$params = $this->request->getParams();
			if($params["devolver"] == "true"){
				$location = '/userlibrary/delete/';
			}
			elseif ($params != null) {
				$location = '/userlibrary/create/';
				foreach ($params as $key => $value) {
					$location .= $key . '/' . $value;
				}
			} else {
				$location = '/userlibrary/read/';
			}
			header('Location:' . $location);
		} else
			return $this->view('error', ["error" => "Not logged in."]);
	}
}