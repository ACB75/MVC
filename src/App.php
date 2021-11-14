<?php

namespace App;

use App\Request;
use App\Session;

final class App 
{
    static protected $action;
    static protected $req;

    private static function env()
    {
        $ipAddress = gethostbyname($_SERVER['SERVER_NAME']);
        if($ipAddress == '127.0.0.1')
        {
            return 'dev';
        }
        else
        {
            return 'pro';
        }
    }

    private static function loadConf()
    {
        $file = "config.json";
        $jsonStr = file_get_contents($file);
        $arrayJson = json_decode($jsonStr);

        return $arrayJson;
    }

    static function init()
    {
        //read conf
        $config = self::loadConf();
        //To dev or to pro?
        $strconf = 'conf_'.self::env();
        $conf = (array)$config->$strconf;

        return $conf;
    }

    public static function run()
    {
        unset($_SESSION);
        $session = new Session();
        //csrf-token to avoid csrf attacks
        if(!($session->exists('csrf-token')))
        {
            $session->set('csfr-token', bin2hex(random_bytes(32)));
        }
        //routes array
        $routes = self::getRoutes();

        //Get three params: controller, action, [param]
        //Url friendly: http://app/controller/action/param1/value1/param2/value2
        self::$req = new Request();
        $controller = self::$req->getController();
        self::$action = self::$req->getAction();
        self::dispatch($controller, $routes, $session);
    }

    private static function dispatch($controller, $routes, $session) : void
    {
        try
        {
            if(in_array($controller, $routes))
            {
                $nameController = '\\App\Controllers\\' . ucfirst($controller) . 'Controller'; 
                $objController = new $nameController(self::$req, $session);
                
                //check if action exists like method in object
                if(is_callable([$objController, self::$action]))
                {
                    call_user_func([$objController, self::$action]);
                }
                else
                {
                    call_user_func([$objController, 'error']);
                }
            }
            else
            {
                throw new \Exception("404 Not Found :(");
            }
        }
        catch(\Exception $e)
        {
            die($e->getMessage());
        }
    }

    /** 
     * @ return array
     * returns registered route array
     */
    static function getRoutes()
    {
        $dir = __DIR__.'/Controllers';
        $handle = opendir($dir);
        
        while(false != ($entry = readdir($handle)))
        {
            if($entry != "." && $entry != "..")
            {
                $routes[] = strtolower(substr($entry, 0 ,-14));
            }    
        }

        return $routes;
    }
}