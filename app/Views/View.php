<?php
namespace Views;

use Traits\Magic;

/**
 * Class View
 * @package App\Views
 */
class View
{
    public $view; // содержимое страницы для вывода в шаблоне
    public $user; // пользователь

    use Magic;
    //use Iterator;
    //use Count;
    //use ArrayAccess;

    /**
     * Возвращает строку - HTML-код шаблона
     * @param string $template - шаблон
     * @param array $vars - переданные переменные для рендера в шаблоне
     * @return false|string|null
     */
    public function render(string $template, $vars = [])
    {
        $file = explode('.', $template);
        $file_name = $file[0];
        $ext = $file[1] ?? 'php';
        $tmpl = defined('TEMPLATE') ? TEMPLATE : 'main';
        $file_path =
            DIR_TEMPLATES . DIRECTORY_SEPARATOR .
            $tmpl .
            (mb_substr($file_name, 0, 1) === '/' || mb_substr($file_name, 0, 1) === '\\' ? '' : DIRECTORY_SEPARATOR) .
            $file_name . '.' .
            $ext;

        if (empty($template) || !is_file($file_path)) return false;

        ob_start();
        foreach ($this as $name => $value) {
            $$name = $value;
        }

        if (!empty($vars) && is_array($vars)) {
            foreach ($vars as $key => $val) {
                $$key = $val;
            }
        }

        include $file_path;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * Отображает HTML-код шаблона
     * @param string $file
     */
    public function display(string $file)
    {
        $this->view = $this->render($file);
        echo $this->render('template');
        die;
    }

    /**
     * Отображает HTML-код файла
     * @param string $file
     */
    public function display_element(string $file)
    {
        echo $this->render($file);
        die;
    }
}
