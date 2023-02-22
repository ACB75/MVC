<?php

namespace App\Controllers;

use App\Helpers;
use App\Request;
use App\Session;
use App\Registry;
use App\FormBuilder;
use App\Database\QueryBuilder;

class Controller implements Helpers
{
    protected Request $request;
    protected Session $session;
    protected FormBuilder $form;

    function __construct()
    {
        $this->request = Registry::get("Request");
        $this->session = Registry::get("Session");
    }

    public function index()
    {
        $roles = Registry::get('Database')->selectAll('roles');
        //un cop tenim tota la col·lecció de roles els compactem
        // equival a fer un $rows[0] del resultat del fethAll
        return $this->view('index', compact('roles'));
    }

    //Helpers
    /** Require a view.	
     * @param  string $name
     * @param  array  $data
     */
    function view($name, $data = [])
    {
        extract($data);
            return require "src/Views/{$name}.view.php";
    }

    function error()
    {
        return $this->view('error', []);
    }

    protected function getDB()
    {
        return Registry::get('Database');
    }

    protected function getFormBuilder()
    {
        return new FormBuilder();
    }

    protected function getQueryBuilder()
    {
        return new QueryBuilder();
    }

    protected function check_csrf($token): bool
    {
        if($token === $this->session->get("csrf-token"))
            return false;
        else
            return true; //POST not in Request.
    }
}