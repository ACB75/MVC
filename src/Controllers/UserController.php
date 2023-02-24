<?php

namespace App\Controllers;

use App\Models\User;

class UserController extends Controller
{
	function index()
	{
		if ($this->session->get("User"))
			return $this->view('user', ["user" => $this->session->get("User")]);

		return $this->view('user', []);
	}

	function create()
	{
		//index/register
		if ($this->isset_notEmpty_POST('csrf-token')) {
			$token = filter_input(INPUT_POST, 'csrf-token', FILTER_SANITIZE_STRING);
			if ($this->check_csrf($token))
				return $this->view('error', ['error' => 'CSRFed!!!']);
		}

		if ($this->isset_notEmpty_POST('c_name') && $this->isset_notEmpty_POST('c_email') && $this->isset_notEmpty_POST('c_password')) {

			$name = filter_input(INPUT_POST, 'c_name', FILTER_SANITIZE_STRING);
			$mail = filter_input(INPUT_POST, 'c_email', FILTER_SANITIZE_EMAIL);
			$pwd = filter_input(INPUT_POST, 'c_password', FILTER_SANITIZE_STRING);

			$data = ["id" => "", "name" => $name, "email" => $mail, "password" => $pwd];

			$user = $this->auth($data);

			if ($user != null)
				return $this->view('error', ['error' => 'User ' . $name . ' with email ' . $mail . ' already exists.']);

			$result = $this->getDB()->insert('User', $data);

			if ($result == 0 || $result == -1 || $result == null)
				return $this->view('error', ['error' => 'Something went wrong with the process :(']);

			$data = array_replace($data, ["id" => $result]);
			$user = new User($data);

			$this->session->set("User", $user);

			return $this->view('user', ['user' => $user]);
		}

	}

	function read()
	{
		//index/login
		if ($this->isset_notEmpty_POST('csrf-token')) {
			$token = filter_input(INPUT_POST, 'csrf-token', FILTER_SANITIZE_STRING);
			if ($this->check_csrf($token))
				return $this->view('error', ['error' => 'CSRFed!!!']);
		}

		if ($this->isset_notEmpty_POST('r_name') && $this->isset_notEmpty_POST('r_password')) {

			$name = filter_input(INPUT_POST, 'r_name', FILTER_SANITIZE_STRING);
			$pwd = filter_input(INPUT_POST, 'r_password', FILTER_SANITIZE_STRING);

			if (!str_contains($name, '@'))
				$data = ["id" => "", "name" => $name, "email" => "", "password" => $pwd];
			else
				$data = ["id" => "", "name" => "", "email" => $name, "password" => $pwd];

			$user = $this->auth($data);

			if ($user != null) {
				$this->session->set('User', $user);
				return $this->view('user', ['user' => $user]);
			} else
				return $this->view('error', ['error' => 'User not found.']);
		}
	}

	function update()
	{
		//index/dashboard
		if ($this->session->exists("User")) {
			$changes = false;
			$user = new User($this->session->get("User"));
			$data = ["id" => $user->getId(), "name" => $user->getName(), "email" => $user->getEmail(), "password" => $user->getPassword()];

			if ($this->isset_notEmpty_POST('csrf-token')) {
				$token = filter_input(INPUT_POST, 'csrf-token', FILTER_SANITIZE_STRING);
				if ($this->check_csrf($token))
					return $this->view('error', ['error' => 'CSRFed!!!']);
			}

			if ($this->isset_notEmpty_POST("u_name")) {
				$changes = true;
				$name = filter_input(INPUT_POST, 'u_name', FILTER_SANITIZE_STRING);
				if ($user->getName() != $name && $name != "") {
					$data = array_replace($data, ["name" => $name]);
					$user->setName($name);
				}
			}

			if ($this->isset_notEmpty_POST("u_email")) {
				$changes = true;
				$mail = filter_input(INPUT_POST, 'u_email', FILTER_SANITIZE_STRING);
				if ($user->getEmail() != $mail && $mail != "") {
					$data = array_replace($data, ["email" => $mail]);
					$user->setEmail($mail);
				}

			}

			if ($this->isset_notEmpty_POST('u_password') && $this->isset_notEmpty_POST('re_password') && $this->isset_notEmpty_POST('curr_password')) {
				$changes = true;
				$pwd = filter_input(INPUT_POST, 'u_password', FILTER_SANITIZE_STRING);
				$re_pwd = filter_input(INPUT_POST, 're_password', FILTER_SANITIZE_STRING);
				$curr_pwd = filter_input(INPUT_POST, 'curr_password', FILTER_SANITIZE_STRING);

				if ($user->getPassword() != $pwd) {
					if ($user->getPassword() == $curr_pwd && ($pwd === $re_pwd) && $pwd != "") {
						$data = array_replace($data, ["password" => $pwd]);
						$user->setPassword($pwd);
					}
				}
			}

			if ($changes) {

				$result = $this->getDB()->update('User', $data, "id");
				$user = $this->auth($data);

				if ($result && $user != null) {
					$this->session->set('User', $user);
					return $this->view('user', ["user" => $user]);
				} else
					return $this->view('error', ["error" => "User not updated, wrong result.", "user" => $user]);
			} else
				return $this->view('error', ["error" => "No values to update.", "user" => $user]);
		}
	}

	function delete()
	{
		//index/dashboard
		if ($this->session->exists("User")) {
			$user = new User($this->session->get("User"));
			if ($this->isset_notEmpty_POST('csrf-token')) {
				$token = filter_input(INPUT_POST, 'csrf-token', FILTER_SANITIZE_STRING);
				if ($this->check_csrf($token))
					return $this->view('error', ['error' => 'CSRFed!!!']);
			}
			$user = $this->session->get("User");
			if ($user->getId() != null || $user->getId() != "") {
				$this->getDB()->delete('User', 'id', $user->getId());
				header('Location:/index/logout');
			} else
				return $this->view('error', ['error' => "User id not in session"]);
		} else
			return $this->view('error', ['error' => "Stop breaking App >:("]);
	}

	//Helpers && BL
	protected function isset_notEmpty_POST($value)
	{
		return isset($_POST[$value]) && !empty($_POST[$value]);
	}

	protected function auth($data)
	{
		try {
			$result = $this->getDB()->selectWhere('User', $data, true);
			if (!empty($result)) {
				$data = array_replace($data, ["id" => $result[0]->id]);
				return new User($result[0]);
			}
		} catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	protected function monsterCookies($name, $email)
	{
		$hour = time() + 3600 * 24 * 30;
		$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

		setcookie('uName', $name->getName(), $hour, $path);
		setcookie('uEmail', $email->getEmail(), $hour, $path);
		setcookie('active', 1, $hour, $path);
	}
}