<?php

namespace app\controllers;

use app\core\App;
use app\core\base\Controller;
use app\core\libs\Pagination;
use app\models\Main;
use app\models\Work;


class MainController extends Controller
{
//    public $layout = 'main';

//    public function indexAction()
//    {
//        $this->view = 'main';
//
//        $total = Work::count();
//        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
//        $perPage = 2;
//        $pagination = new Pagination($page, $perPage, $total);
//        $start = $pagination->getStart();
//        $works = Work::offset($start)->limit($perPage)->get();
////        dd($start);
//
//        $title = 'заголовок';
//        $this->setMeta('Главная', 'mydesc', 'mykeywords');
//        $this->set(compact('title', 'works', 'pagination', 'total'));
//    }

    public function indexAction(){
//        $this->view = 'index';

        $title = 'главная';
        $lang_code = App::$app->getProperty('lang')['code'];
        $total = Work::where('lang_code', $lang_code)->count();
 
        $page = isset($_GET['page'])? (int)$_GET['page'] : 1;
        $perPage = 3;
        $pagination = new Pagination($page, $perPage, $total);
        $start = $pagination->getStart();
        $works = Work::where('lang_code', $lang_code)->offset($start)->limit($perPage)->get();
//        dd($works);

        $this->set(compact('title', 'works', 'pagination', 'total'));
    }


    public function showAction()
    {
        $post = $this->app->main->findOne('posts', 1);
        App::$app->cache->set('post', $post);

//        $post = $model->findOne('posts', 1);
        $title = 'Показать одну запись - (showAction)';
        $this->set(compact('title', 'post'));
    }

    public function testSqlAction()
    {
        $this->layout = false;
        $model = new Main;
        $sql = "SELECT * FROM {$model->table} ORDER BY id DESC";
        $res = $model->findBySql($sql);
        dump($res);
    }

    public function findLikeAction()
    {
        $this->layout = false;
        $model = new Main();
        $res = $model->findLike('asd', 'text', 'posts');
        dump($res);
    }

    public function testAction()
    {
        $this->layout = 'default';

        $name = 'MAX';
        $this->set([
            'name' => $name,
            'car' => 'toyota'
        ]);

        echo "это экшн - <b style='color: forestgreen;'>testAction</b> у контроллера MainController";
    }

    public function ajaxAction()
    {
//        $this->layout = false;

        if ($this->isAjax()) {
            $model = new Main();
//            $data = [
//                'answer' => 'ответ с сервера',
//                'code' => 200
//            ];
//            echo json_encode($data);
            $post = R::findOne('posts', "id = {$_POST['id']}");
            $this->loadView('_test', compact('post'));
            exit;
        }
        echo '404';
    }


}