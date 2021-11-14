<?php

namespace App;

class UserController
{
    function login()
    {
        $form = $this->createForm();
        $form->open(BASE . 'user/log')
            ->label('Email:')
            ->input('email', 'email')
            ->label('Password:')
            ->input('Password', 'passw')
            ->csrf($this->session->get('csrf-token'))
            ->submit('Sign')
            ->close();
        
        $this->render(['form'=>$form], 'login');
    }

    function log()
    {
        if(isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['passw']) && !empty($_POST['passw']))
        {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $passw = filter_input(INPUT_POST, 'passw', FILTER_SANITIZE_STRING);
            $user = $this->auth($email, $pass);

            if($user)
            {
                $this->session->set('user', $user);

                if(isset($_POST['remember-me'])&&($_POST['remember-me']=='on'||$_POST['remember-me']=='1' )&& !isset($_COOKIE['remember']))
                {
                    $hour = time() + 3600 * 24 * 30;
                    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                    setcookie('uname', $user['uname'], $hour, $path);
                    setcookie('email', $user['email'], $hour, $path);
                    setcookie('active', 1, $hour, $path);
                }

                header('Location:' .BASE. 'user/dashboard');
            }
            else
            {
                header('Location:' .BASE. 'user/login');
            }
        }
    }

    function dashboard()
    {
        $user = $this->session->get('user');
        $data = $this->getDB()->selectAllWithJoin('tasks', 'users', ['tasks.id', 'tasks.description', 'tasks.due_date'], 'user', 'id');
        $this->render(['user'=>$user,'data'=>$data],'dashboard');
    }
}