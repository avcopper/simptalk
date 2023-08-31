<?php
namespace Controllers;

use Views\View;
use Entity\User;
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
     * @param null $param1
     * @param null $param2
     * @throws \Exceptions\ForbiddenException
     * @throws \Exceptions\NotFoundException
     */
    public function action(string $action, $param1 = null, $param2 = null)
    {
        if (method_exists($this, $action)) {
            if ($this->access($action)) {
                if (method_exists($this, 'before')) $this->before();

                if (!empty($param1) && !empty($param2)) $this->$action($param1, $param2);
                elseif (!empty($param1)) $this->$action($param1);
                else$this->$action();

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
     * Отображает HTML-код шаблона
     * @param $file
     */
    protected function display($file)
    {
        $this->view->display($file);
    }

    /**
     * Отображает HTML-код файла
     * @param $file
     */
    protected function display_element($file)
    {
        $this->view->display_element($file);
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
