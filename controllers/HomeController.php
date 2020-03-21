<?php

namespace controllers;

use core\Controller;
use core\Helper;
use models\Article;

class HomeController extends Controller
{
    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function index()
    {
        $this->render('home');
    }
}