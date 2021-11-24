<?php


namespace App\Controller\Admin\Route;


use FastRoute\Route;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Router\DispatcherFactory;

/**
 * @AutoController()
 */
class RouteController
{
    public function all()
    {

        $route = di()->get(DispatcherFactory::class)->getRouter('http');
        dd($route);

    }
}