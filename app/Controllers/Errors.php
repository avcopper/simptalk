<?php
namespace Controllers;

use Views\View;

/**
 * Class Errors
 * @package App\Controllers
 */
class Errors extends Controller
{
    public function __construct($e)
    {
        $this->view = new View();
        $this->set('code', $e->getCode());
        $this->set('message', $e->getMessage());
    }

    /**
     * Показ страницы ошибки
     * @param int $code - код исключения
     */
    protected function actionError(int $code)
    {
        $status = match ($code) {
            400 => 'HTTP/1.1 400 Bad Request',
            401 => 'HTTP/1.1 401 Unauthorized',
            403 => 'HTTP/1.1 403 Forbidden',
            404 => 'HTTP/1.1 404 Not Found',
            default => 'HTTP/1.1 500 Internal Server Error',
        };

        header($status, $code ?: 500);
        $this->display('errors/error');
        die();
    }
}
