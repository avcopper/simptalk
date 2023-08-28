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
        $param  = null;          // параметр метода
        $route  = ROUTE;         // массив роутов
        $count  = count($route); // количество роутов

        if ($count > 1) {
            $last = $count - 1; // индекс последнего элемента массива роутов

            $base = 'Controllers';
            for ($i = 0; $i < $last - 1; $i++) {
                $base .= '\\' . $route[$i];
            }

            if (class_exists($base . '\\' . $route[$last - 1] . '\\' . $route[$last] . '\\Index') &&
                method_exists($base . '\\' . $route[$last - 1] . '\\' . $route[$last] . '\\Index', 'actionDefault'))
            { // \Controllers\Blog\Index -> actionDefault()
                $class = $base . '\\' . $route[$last - 1] . '\\' . $route[$last] . '\\Index';
                $action = 'actionDefault';
            }
            elseif (class_exists($base . '\\' . $route[$last - 1] . '\\' . $route[$last]) &&
                method_exists($base . '\\' . $route[$last - 1] . '\\' . $route[$last], 'actionDefault'))
            { // Controllers\Blog\News -> actionDefault()
                $class = $base . '\\' . $route[$last - 1] . '\\' . $route[$last];
                $action = 'actionDefault';
            }
            elseif (class_exists($base . '\\' . $route[$last - 1])) {
                if (method_exists($base . '\\' . $route[$last - 1], 'action' . $route[$last])) { // Controllers\Blog\News -> actionSave()
                    $class = $base . '\\' . $route[$last - 1];
                    $action = 'action' . $route[$last];
                }
                elseif (method_exists($base . '\\' . $route[$last - 1], 'actionShow')) { // Controllers\Blog\News -> actionShow(10)
                    $class = $base . '\\' . $route[$last - 1];
                    $action = 'actionShow';
                    $param = $route[$last];
                }
            }
            elseif (class_exists($base) && method_exists($base, 'action' . $route[$last - 1])) { // Controllers\Blog -> actionEdit(10)
                $class = $base;
                $action = 'action' . $route[$last - 1];
                $param = $route[$last];
            }
        }
        elseif ($count === 1) { //shop/blog/
            if (class_exists('Controllers\\' . $route[0]) &&
                method_exists('Controllers\\' . $route[0], 'actionDefault'))
            { // Controllers\Blog -> actionDefault()
                $class  = 'Controllers\\' . $route[0];
                $action = 'actionDefault';
            }
            elseif (class_exists('Controllers\\' . $route[0] . '\\Index') &&
                method_exists('Controllers\\' . $route[0] . '\\Index', 'actionDefault'))
            { // Controllers\Blog\Index -> actionDefault()
                $class  = 'Controllers\\' . $route[0] . '\\Index';
                $action = 'actionDefault';
            }
        }
        else {
            $class  = 'Controllers\\Index';
            $action = 'actionDefault';
        }

        if (!empty($class) && !empty($action)) {
            $controller = new $class;
            $controller->action($action, mb_strtolower($param) ?? null);
        }
        else
            if (!in_array('Js', ROUTE)) throw new NotFoundException(); // inputmask какого-то хрена далет еще один запрос...
    }

    public static function startApi()
    {
        $class  = null;  // класс контроллера
        $action = null;  // метод контроллера
        $param1  = null; // параметр метода
        $param2  = null; // параметр метода
        $route  = ROUTE; // массив роутов

        if (!empty($route[0])) {
            $base_dir = 'Api\\Controllers' . '\\' . $route[0];

            switch (true) {
                case Request::isGet():
                    $subClass = 'Read';
                    break;
                case Request::isPost():
                    $subClass = 'Create';
                    break;
                case Request::isPut():
                    $subClass = 'Update';
                    break;
                case Request::isDelete():
                    $subClass = 'Delete';
                    break;
                case Request::isPatch():
                    $subClass = 'Patch';
                    break;
                default:
                    $subClass = '';
                    break;
            }

            switch (count($route)) {
                case 4:
                    if (class_exists($base_dir . '\\' . $route[1] . '\\' . $route[2] . '\\' . $subClass) &&
                        method_exists($base_dir . '\\' . $route[1] . '\\' . $route[2] . '\\' . $subClass, 'actionDefault'))
                    {
                        $class = $base_dir . '\\' . $route[1] . '\\' . $route[2] . '\\' . $subClass;
                        $param1 = $route[3];
                        $action = 'actionDefault';
                    }
                    elseif (class_exists($base_dir . '\\' . $route[2] . '\\' . $subClass) &&
                        method_exists($base_dir . '\\' . $route[2] . '\\' . $subClass, 'actionDefault'))
                    {
                        $class = $base_dir . '\\' . $route[2] . '\\' . $subClass;
                        $param1 = $route[1];
                        $param2 = $route[3];
                        $action = 'actionDefault';
                    }
                    break;
                case 3:
                    if (class_exists($base_dir . '\\' . $route[1]) &&
                        method_exists($base_dir . '\\' . $route[1], "action{$route[2]}"))
                    {
                        $class = $base_dir . '\\' . $route[1];
                        $action = "action{$route[2]}";
                    }
                    elseif (class_exists($base_dir . '\\' . $route[1]) &&
                        method_exists($base_dir . '\\' . $route[1], 'actionDefault'))
                    {
                        $class = $base_dir . '\\' . $route[1];
                        $param1 = $route[2];
                        $action = 'actionDefault';
                    }
                    elseif (class_exists($base_dir . '\\' . $route[1] . '\\' . $subClass) &&
                        method_exists($base_dir . '\\' . $route[1] . '\\' . $subClass, 'actionDefault'))
                    {
                        $class = $base_dir . '\\' . $route[1] . '\\' . $subClass;
                        $param1 = $route[2];
                        $action = 'actionDefault';
                    }
                    elseif (class_exists($base_dir . '\\' . $route[2] . '\\' . $subClass) &&
                        method_exists($base_dir . '\\' . $route[2] . '\\' . $subClass, 'actionDefault'))
                    {
                        $class = $base_dir . '\\' . $route[2] . '\\' . $subClass;
                        $param1 = $route[1];
                        $action = 'actionDefault';
                    }
                    break;
                case 2:
                    if (class_exists($base_dir . '\\' . $route[1]) &&
                        method_exists($base_dir . '\\' . $route[1], 'actionDefault'))
                    {
                        $class = $base_dir . '\\' . $route[1];
                        $action = 'actionDefault';
                    }
                    elseif (class_exists($base_dir . '\\' . $route[1] . '\\' . $subClass) &&
                        method_exists($base_dir . '\\' . $route[1] . '\\' . $subClass, 'actionDefault'))
                    {
                        $class = $base_dir . '\\' . $route[1] . '\\' . $subClass;
                        $action = 'actionDefault';
                    }
                    elseif (class_exists($base_dir . '\\' . $subClass) &&
                        method_exists($base_dir . '\\' . $subClass, 'actionDefault'))
                    {
                        $class = $base_dir . '\\' . $subClass;
                        $param1 = $route[1];
                        $action = 'actionDefault';
                    }
                    break;
                case 1:
                    if (class_exists($base_dir . '\\' . $subClass) &&
                        method_exists($base_dir . '\\' . $subClass, 'actionDefault'))
                    {
                        $class = $base_dir . '\\' . $subClass;
                        $action = 'actionDefault';
                    }
                    break;
            }
        }

        if (!empty($class) && !empty($action)) {
            $controller = new $class;

            if (!empty($param1) && !empty($param2))
                $controller->action($action, mb_strtolower($param1), mb_strtolower($param2));
            elseif (!empty($param1))
                $controller->action($action, mb_strtolower($param1));
            else
                $controller->action($action);

        } else {
            throw new NotFoundException();
        }
    }
}
