<?php
namespace Controllers;

use System\Api;
use Views\View;
use Entity\User;
use Exceptions\UserException;
use Models\User as ModelUser;
use Exceptions\NotFoundException;
use Exceptions\ForbiddenException;

/**
 * Class Controller
 * @package App\Controllers
 */
abstract class Controller
{
    //protected ?string $token; // текущий токен
    protected ?User $user;    // текущий пользователь
    protected View $view;     // объект view

    /**
     * Controller constructor
     */
    public function __construct()
    {
        $this->checkAuthorization();

        $this->view = new View();
        $this->user = ModelUser::getCurrent();
        //$this->token = ModelUser::getUserToken();

        $this->set('user', $this->user);
        //$this->set('token', $this->token);
    }

    /**
     * Проверяет доступ и формирует полное имя action
     * @param string $action
     * @param null $param
     * @throws ForbiddenException|NotFoundException
     */
    public function action(string $action, $param = null)
    {
        if (method_exists($this, $action)) {
            if ($this->access($action)) {
                if (method_exists($this, 'before')) $this->before();
                $this->$action($param ?? null);
                if (method_exists($this, 'after')) $this->after();
                die;
            } else throw new ForbiddenException();
        } else throw new NotFoundException();
    }

    /**
     * Объявляет переменную для View
     * @param $var
     * @param $value
     */
    protected function set($var, $value = null)
    {
        $this->view->$var = $value;
    }

    /**
     * Проверяет доступ к методу в классе $this
     * @param $action - метод, доступ к которому проверяется
     * @return bool
     */
    protected function access($action):bool
    {
        return true;
    }

    protected function checkAuthorization()
    {
        if (!ModelUser::isAuthorized() && !in_array('Auth', ROUTE)) {
            header('Location: /auth/');
            die;
        }
    }
}
