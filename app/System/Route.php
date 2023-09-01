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
        $urls = [];
        $link = '';

        foreach ($parts as $part) {
            $elem = ucfirst(str_replace('-', '_', $part));
            $link .= ($link ? '/' : '') . str_replace('-', '_', $part);

            if (!empty($elem)) {
                $routes[] = $elem;

                $urls[] = [
                    'name' => $elem,
                    'link' => $link
                ];
            }
        }

        define('ROUTE', $routes); // ['Catalog', 'Conditioners', 'Mobile', '335']
        define('URL', $urls); // [['name' => 'Personal', 'link' => 'personal'], ['name' => 'Subscriptions', 'link' => 'personal/subscriptions']]
    }

    /**
     * Формируется адрес контроллера и его экшн по типу \App\Controller\Catalog -> actionDefault
     * Сначала проверяется путь App\Controller\Blog\News\10 -> actionShow(10)
     * Затем проверяется путь App\Controller\Blog\News\Edit\10 -> actionEdit(10)
     * Затем проверяется путь App\Controller\Blog\News -> actionDefault()
     * Проверка идет с конца адресной строки
     * @throws NotFoundException|ForbiddenException
     */
    public static function start()
    {
        $class  = null;            // класс контроллера
        $action = null;            // метод контроллера
        $param1  = null;          // параметр1 метода
        $param2  = null;          // параметр2 метода
        $route  = ROUTE;         // массив роутов

        $base = 'Controllers';
        switch (count($route)) {
            case 4:
                if (class_exists($base . '\\' . $route[0] . '\\' . $route[1] . '\\' . $route[2] . '\\' . $route[3] . '\\' . 'Index') &&
                    method_exists($base . '\\' . $route[0] . '\\' . $route[1] . '\\' . $route[2] . '\\' . $route[3] . '\\' . 'Index', 'actionDefault'))
                {
                    $class = $base . '\\' . $route[0] . '\\' . $route[1] . '\\' . $route[2] . '\\' . $route[3] . '\\' . 'Index';
                    $action = 'actionDefault';
                }
                elseif (class_exists($base . '\\' . $route[0] . '\\' . $route[1] . '\\' . $route[2] . '\\' . $route[3]) &&
                    method_exists($base . '\\' . $route[0] . '\\' . $route[1] . '\\' . $route[2] . '\\' . $route[3], 'actionDefault'))
                {
                    $class = $base . '\\' . $route[0] . '\\' . $route[1] . '\\' . $route[2] . '\\' . $route[3];
                    $action = 'actionDefault';
                }
                elseif (class_exists($base . '\\' . $route[0] . '\\' . $route[1] . '\\' . $route[2]) &&
                    method_exists($base . '\\' . $route[0] . '\\' . $route[1] . '\\' . $route[2], "action{$route[3]}"))
                {
                    $class = $base . '\\' . $route[0] . '\\' . $route[1] . '\\' . $route[2];
                    $action = "action{$route[3]}";
                }
                elseif (class_exists($base . '\\' . $route[0] . '\\' . $route[1] . '\\' . $route[2]) &&
                    method_exists($base . '\\' . $route[0] . '\\' . $route[1] . '\\' . $route[2], "actionShow"))
                {
                    $class = $base . '\\' . $route[0] . '\\' . $route[1] . '\\' . $route[2];
                    $action = "actionShow";
                    $param1 = $route[3];
                }
                elseif (class_exists($base . '\\' . $route[0] . '\\' . $route[1]) &&
                    method_exists($base . '\\' . $route[0] . '\\' . $route[1], "action{$route[2]}"))
                {
                    $class = $base . '\\' . $route[0] . '\\' . $route[1];
                    $action = "action{$route[2]}";
                    $param1 = $route[3];
                }
                elseif (class_exists($base . '\\' . $route[0]) &&
                    method_exists($base . '\\' . $route[0], "action{$route[1]}"))
                {
                    $class = $base . '\\' . $route[0];
                    $action = "action{$route[1]}";
                    $param1 = $route[2];
                    $param2 = $route[3];
                }
                break;
            case 3:
                if (class_exists($base . '\\' . $route[0] . '\\' . $route[1] . '\\' . $route[2] . '\\' . 'Index') &&
                    method_exists($base . '\\' . $route[0] . '\\' . $route[1] . '\\' . $route[2] . '\\' . 'Index', "actionDefault"))
                {
                    $class = $base . '\\' . $route[0] . '\\' . $route[1] . '\\' . $route[2] . '\\' . 'Index';
                    $action = "actionDefault";
                }
                elseif (class_exists($base . '\\' . $route[0] . '\\' . $route[1] . '\\' . $route[2]) &&
                    method_exists($base . '\\' . $route[0] . '\\' . $route[1] . '\\' . $route[2], "actionDefault"))
                {
                    $class = $base . '\\' . $route[0] . '\\' . $route[1] . '\\' . $route[2];
                    $action = "actionDefault";
                }
                elseif (class_exists($base . '\\' . $route[0] . '\\' . $route[1]) &&
                    method_exists($base . '\\' . $route[0] . '\\' . $route[1], "action{$route[2]}"))
                {
                    $class = $base . '\\' . $route[0] . '\\' . $route[1];
                    $action = "action{$route[2]}";
                }
                elseif (class_exists($base . '\\' . $route[0] . '\\' . $route[1]) &&
                    method_exists($base . '\\' . $route[0] . '\\' . $route[1], "actionShow"))
                {
                    $class = $base . '\\' . $route[0] . '\\' . $route[1];
                    $action = "actionShow";
                    $param1 = $route[2];
                }
                elseif (class_exists($base . '\\' . $route[0]) &&
                    method_exists($base . '\\' . $route[0], "action{$route[1]}"))
                {
                    $class = $base . '\\' . $route[0];
                    $action = "action{$route[1]}";
                    $param1 = $route[2];
                }
                elseif (class_exists($base . '\\' . $route[0]) &&
                    method_exists($base . '\\' . $route[0], "actionShow"))
                {
                    $class = $base . '\\' . $route[0];
                    $action = "actionShow";
                    $param1 = $route[1];
                    $param2 = $route[2];
                }
                break;
            case 2:
                if (class_exists($base . '\\' . $route[0] . '\\' . $route[1] . '\\' . 'Index') &&
                    method_exists($base . '\\' . $route[0] . '\\' . $route[1] . 'Index', "actionDefault"))
                {
                    $class = $base . '\\' . $route[0] . '\\' . $route[1] . '\\' . 'Index';
                    $action = "actionDefault";
                }
                elseif (class_exists($base . '\\' . $route[0] . '\\' . $route[1]) &&
                    method_exists($base . '\\' . $route[0] . '\\' . $route[1], "actionDefault"))
                {
                    $class = $base . '\\' . $route[0] . '\\' . $route[1];
                    $action = "actionDefault";
                }
                elseif (class_exists($base . '\\' . $route[0]) &&
                    method_exists($base . '\\' . $route[0], "action{$route[1]}"))
                {
                    $class = $base . '\\' . $route[0];
                    $action = "action{$route[1]}";
                }
                elseif (class_exists($base . '\\' . $route[0]) &&
                    method_exists($base . '\\' . $route[0], "actionShow"))
                {
                    $class = $base . '\\' . $route[0];
                    $action = "actionShow";
                    $param1 = $route[1];
                }
                break;
            case 1:
                if (class_exists($base . '\\' . $route[0] . '\\' . 'Index') &&
                    method_exists($base . '\\' . $route[0] . 'Index', "actionDefault"))
                {
                    $class = $base . '\\' . $route[0] . '\\' . 'Index';
                    $action = "actionDefault";
                }
                elseif (class_exists($base . '\\' . $route[0]) &&
                    method_exists($base . '\\' . $route[0], "actionDefault"))
                {
                    $class = $base . '\\' . $route[0];
                    $action = "actionDefault";
                }
                break;
            case 0:
                $class = $base . '\\Index';
                $action = "actionDefault";
                break;
        }

        if (!empty($class) && !empty($action)) {
            $controller = new $class;

            if (!empty($param1) && !empty($param2)) $controller->action($action, mb_strtolower($param1), mb_strtolower($param2));
            elseif (!empty($param1)) $controller->action($action, mb_strtolower($param1));
            else$controller->action($action);

        } else
            if (!in_array('Js', ROUTE)) throw new NotFoundException(); // inputmask какого-то хрена далет еще один запрос...
    }
}
