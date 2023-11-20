<?php
namespace Controllers;

use Views\View;
use Entity\User;
use Models\Model;
use System\Crypt;
use System\Request;
use Models\User as ModelUser;
use Exceptions\NotFoundException;
use Exceptions\ForbiddenException;

/**
 * Class Controller
 * @package App\Controllers
 */
abstract class Controller
{
    protected View $view;  // объект view
    protected ?User $user; // текущий пользователь
    protected ?Crypt $crypt; // объект шифрования
    protected ?Model $model = null;

    /**
     * Controller constructor
     */
    public function __construct()
    {
        $this->view = new View();
        $this->user = ModelUser::getCurrent();
        $this->crypt = new Crypt($this->user->getPublicKey(), $this->user->getPrivateKey());
        $this->set('user', $this->user);
        $this->set('crypt', $this->crypt);
    }

    /**
     * Проверяет авторизавон ли пользователь
     */
    protected function checkAuthorization()
    {
        if (!ModelUser::isAuthorized()) {
            unset($_SESSION['user'], $_SESSION['token']);
            header('HTTP/1.1 403 Forbidden', 403);
            if (!Request::isAjax()) header('Location: /auth/');
            die;
        }
    }

    /**
     * Проверяет доступ и формирует полное имя action
     * @param string $action
     * @param null $param1
     * @param null $param2
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function action(string $action, $param1 = null, $param2 = null)
    {
        if (method_exists($this, $action)) {
            if ($this->access($action)) {
                if (method_exists($this, 'before')) $this->before();

                if (!is_null($param1) && !is_null($param2)) $this->$action($param1, $param2);
                elseif (!is_null($param1)) $this->$action($param1);
                else $this->$action();

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
     * Устанавливает директорию шаблона
     * @param $template - имя директории шаблона
     */
    protected function setTemplate(string $template)
    {
        $this->view->setTemplate($template);
    }

    /**
     * Возвращает отрендеренный файл
     * @param $file
     * @param array $vars
     * @return false|string|null
     */
    protected function render($file, $vars = [])
    {
        return $this->view->render($file, $vars = []);
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
}
