<?php

namespace App\Controllers;

use App\Core\BaseController;

class HomeController extends BaseController
{
    /**
     * Muestra la página de inicio del portal
     */
    public function index()
    {
        return $this->view('portal.home');
    }
}
