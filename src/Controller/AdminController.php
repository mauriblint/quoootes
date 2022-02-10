<?php
namespace App\Controller;

use App\Lib\TemplatesService;

class AdminController extends AppController
{

    public function initialize() : void
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('admin');
    }

    public function index()
    {
    	$this->set('template', TemplatesService::all());
    }
}
