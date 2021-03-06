<?php

namespace app\core\base;

use app\core\App;
use app\core\widgets\language\Language;
use app\core\Registry;

abstract class Controller
{
    public $pathToView = [];

    /**
     * текущий маршрут и параметры (controller, action, params)
     * @var array
     */
    public $route = [];

    /**
     * вид
     * @var string
     */
    public $view;

    /**
     * базовый шаблон
     * @var string
     */
    public $layout = LAYOUT;

    /**
     * данные для View
     * @var array
     */
    public $vars = array();

    public $meta = [
        'title' => '',
        'description' => '',
        'keywords' => ''
    ];

    public $app;


    public function __construct($route)
    {
        $this->route = $route;
        $this->view = $route['action'];
        $this->app = Registry::instance();

        App::$app->setProperty('langs', Language::getLanguages());
        App::$app->setProperty('lang', Language::getLanguage(App::$app->getProperty('langs')));

//        dd(App::$app->getProperties());
    }

    public function getView()
    {
        $vObj = new View($this->route, $this->layout, $this->view);
        $this->vars['meta'] = $this->meta;
        $vObj->render($this->vars);
    }

    public function set($data)
    {
        $this->vars = $data;
    }

    public function setMeta($title='', $description='', $keywords=''){
        $this->meta['title'] = $title;
        $this->meta['description'] = $description;
        $this->meta['keywords'] = $keywords;
    }

    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function loadView($view, $vars = []){
        extract($vars);
        require APP."/views/{$this->route['controller']}/{$view}.php";
    }
}