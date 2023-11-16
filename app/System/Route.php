<?php
namespace System;

use Exceptions\NotFoundException;
use Exceptions\ForbiddenException;

/**
 * Class Route
 * @package App\System
 */
class Route
{
    /**
     * Разбираем url на составные части и объявляем константу с ними
     * @param $uri
     */
    public static function parseUrl($uri)
    {
        $uri = explode('?', $uri)[0];
        $uri = mb_substr(trim($uri), 1, mb_strlen($uri) - 2);
        $parts  = explode('/', $uri);
        $routes = [];

        foreach ($parts as $part) {
            $elem = ucfirst(str_replace('-', '_', $part));

            if (!empty($elem)) $routes[] = $elem;
        }

        define('ROUTE', array_values($routes)); // ['Catalog', 'Conditioners', 'Mobile', '335']
    }

    /**
     * Формируется адрес контроллера и его экшн по типу \App\Controller\Catalog -> actionDefault
     * Сначала проверяется путь App\Controller\Blog\News\10 -> actionShow(10)
     * Затем проверяется путь App\Controller\Blog\News\Edit\10 -> actionEdit(10)
     * Затем проверяется путь App\Controller\Blog\News -> actionDefault()
     * Проверка идет с конца адресной строки
     * @throws NotFoundException
     */
    public static function start()
    {
        $routes  = ROUTE;// массив роутов
        $controller = 'Controllers';
        $results = [];
        $params = [];

        if (!empty($routes)) {
            foreach ($routes as $i => $route) {
                if (is_numeric($route)) {
                    $params[] = $route;
                    continue;
                }

                if ($i >= count($routes) - 3) {
                    if (class_exists($controller) && method_exists($controller, 'action' . $route)) {
                        $params1 = [];
                        if (!empty($routes[$i + 1])) $params1[] = $routes[$i + 1];
                        if (!empty($routes[$i + 2])) $params1[] = $routes[$i + 2];
                        $results[] = ['class' => $controller, 'method' => 'action' . $route, 'params' => array_merge($params, $params1)];
                    }

                    if (empty($routes[$i + 1]) && empty($routes[$i + 2])) {
                        if (class_exists($controller . ('\\' . $route)) && method_exists($controller . ('\\' . $route), 'actionDefault')) {
                            $results[] = ['class' => $controller . ('\\' . $route), 'method' => 'actionDefault'];
                        }
                    }

                    if (class_exists($controller . ('\\Index')) && method_exists($controller . ('\\Index'), 'action' . $route)) {
                        $params1 = [];
                        if (!empty($routes[$i + 1])) $params1[] = $routes[$i + 1];
                        if (!empty($routes[$i + 2])) $params1[] = $routes[$i + 2];
                        $results[] = ['class' => $controller . ('\\Index'), 'method' => 'action' . $route, 'params' => array_merge($params, $params1)];
                    }

                    if (!empty($routes[$i + 1]) && empty($routes[$i + 2])) {
                        if (class_exists($controller . ('\\' . $route)) && method_exists($controller . ('\\' . $route), 'actionShow')) {
                            $params1 = [];
                            if (!empty($routes[$i + 1])) $params1[] = $routes[$i + 1];
                            $results[] = ['class' => $controller . ('\\' . $route), 'method' => 'actionShow', 'params' => array_merge($params, $params1)];
                        }

                        if (class_exists($controller . ('\\' . $route) . ('\\Index')) && method_exists($controller . ('\\' . $route) . ('\\Index') , 'actionShow')) {
                            $params1 = [];
                            if (!empty($routes[$i + 1])) $params1[] = $routes[$i + 1];
                            $results[] = ['class' => $controller . ('\\' . $route) . ('\\Index'), 'method' => 'actionShow', 'params' => array_merge($params, $params1)];
                        }

                    }
                }

                $controller .= ('\\' . $route);
            }
        } else $results[] = ['class' => $controller . '\\Index', 'method' => 'actionDefault'];

        if (!empty($results) && is_array($results)) {
            $routeInfo = array_pop($results);

            if (!empty($routeInfo['class']) && !empty($routeInfo['method'])) {
                $class = $routeInfo['class'];
                $method = $routeInfo['method'];

                $controller = new $class;

                if (!empty($params[0]) && !empty($params[1])) $controller->action($method, mb_strtolower($params[0]), mb_strtolower($params[1]));
                elseif (!empty($params[0])) $controller->action($method, mb_strtolower($params[0]));
                else $controller->action($method);
            }
        }

        throw new NotFoundException();
    }
}
