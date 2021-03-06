<?php

namespace app\controllers\admin;

use app\core\base\Controller;
use app\models\User;

class CommonController extends Controller
{
    public $layout = 'admin';

    public function __construct($route)
    {
        parent::__construct($route);
        if (!User::isAdmin() && $route['action'] != 'login'){
            redirect(ADMIN.'/user/login');
        }
    }
}