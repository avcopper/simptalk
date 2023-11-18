<?php
namespace Controllers;

/**
 * Class Index
 * @package Controllers
 */
class Index extends Controller
{
    protected function before()
    {
        $this->checkAuthorization();
    }

    /**
     * Выводит стартовую страницу
     */
    protected function actionDefault()
    {
        $this->view->display('index');
    }
}
