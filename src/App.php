<?php
namespace App;

use App\Request;
use App\Session;

final class App
{
    protected Session $session;
    protected Request $request;
    protected $action;
    protected $params;

    public function __construct()
    {
        $this->session = new Session();
        $this->request = new Request();

        //Obtenir tres parámetres: controlador, accio,[parametres]
        //url friendly: http://app/controlador/accion/param1/valor1/param2/valor2
        $routes = self::getRoutes();
        $controller = $this->request->getController();
        $this->action = $this->request->getAction();

        self::dispatch($controller, $routes, $this->session, $this->request, $this->action, $this->params);
    }

    private static function dispatch($controller, $routes, $session, $request, $action, $params): void
    {
        try {
            //si es ruta de sistema es pot instanciar
            if (in_array(lcfirst($controller), $routes)) {
                $nameController = '\\App\Controllers\\' . ucfirst($controller) . 'Controller';
                //dispatcher
                $objContr = new $nameController($request, $session);

                //comprovar si existeix l'acció como mètode a l'objecte
                if (is_callable([$objContr, $action])) {
                    call_user_func([$objContr, $action]);
                } else {
                    call_user_func([$objContr, 'error']);
                }
            } else {
                throw new \Exception("Ruta no disponible");
            }
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
    /**
     *  register all available routes in controllers folder
     *  @return array $routes[]
     */
    static function getRoutes()
    {
        $dir = __DIR__ . '/Controllers';
        $handle = opendir($dir);
        while (($entry = readdir($handle)) != false) {
            if ($entry != '.' && $entry != '..') {
                $routes[] = strtolower(substr($entry, 0, -14));
            }
        }
        return $routes;
    }
}