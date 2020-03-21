<?php
/**
 * @var $View View
 * @var $isDevice MobileDetect
 */

use core\Helper;
use core\View;
use Detection\MobileDetect;

$View->includeView('template.head');

?>

<body id="page-top">

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="mainNav">
    <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">Start with NecuFramework</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<section id="about" class="mt-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2>About NecuFramework</h2>
                <p class="lead">NecuFramework is a custom MVC that is easy to use. This framework was developed for personal projects and it can be used for a lot of things and will probably be perfect for your project. There is what you need to start with this framework:</p>
                <ul>
                    <li>php(minimun version 7.2)</li>
                    <li>composer</li>
                    <li>mysql(optional)</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="services" class="bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2>What NecuFrame offers</h2>
                <ul>
                    <li>easy usage of route system</li>
                    <li>MVC principles</li>
                    <li>integrated options like detect mobile devices,boostrap,FEED RSS classes, etc</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2>Contact us</h2>
                <p class="lead">email: cnecula20@yahoo.ro</p>
                <p class="lead">facebook: https://www.facebook.com/constantin.necula.58</p>
            </div>
        </div>
    </div>
</section>
</body>