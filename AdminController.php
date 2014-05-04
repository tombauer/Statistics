<?php
namespace Plugin\Statistics;

class AdminController extends \Ip\Controller
{
    public function index()
    {
        $view = ipView('view/index.php', []);

        ipAddJs('assets/import.js');
        ipAddJs('assets/export.js');

        return $view->render();
    }
}