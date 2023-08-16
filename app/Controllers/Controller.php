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
    //protected ?array $device; // данные устройства пользователя
    protected ?User $user;    // текущий пользователь
    protected View $view;     // объект view

    /**
     * Controller constructor
     * @throws UserException
     */
    public function __construct()
    {
        //$this->token = ModelUser::getUserToken();
        //$this->device = ModelUser::getUserDevice();

        $this->checkAuthorization();

        $this->view = new View();
        //$this->user = !empty($_SESSION['user']) ? $_SESSION['user'] : ModelUser::getCurrent();

        //$this->set('user', $this->user);
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

    /**
     * @throws UserException
     */
    protected function checkAuthorization()
    {
        if (!ModelUser::isAuthorized() && !in_array('Auth', ROUTE)) {
            header('Location: /auth/');
            die;
        }
    }
}
