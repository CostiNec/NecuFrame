<?php

namespace routes;

require '../vendor/autoload.php';

use core\Helper;
use core\Route;

$route = new Route();

$route->get('/','HomeController','index');