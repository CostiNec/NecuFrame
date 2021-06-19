<?php

namespace core;

use core\View;
use Detection\MobileDetect;
use providers\ApplicationProvider;

if(file_exists(__DIR__.'/../vendor/mobiledetect/mobiledetectlib/namespaced/Detection/MobileDetect.php')) {
    require_once __DIR__.'/../vendor/mobiledetect/mobiledetectlib/namespaced/Detection/MobileDetect.php';
}

class Controller
{
    const POST_TEXT = 'This route is get not post :( !';
    const GET_TEXT = 'This route is post not get :( !';
    protected $post = [self::POST_TEXT];
    protected $get = [self::GET_TEXT];
    protected $isDevice;
    protected $user;
    protected $auth;
    protected $guest;

    public function __construct($request_url)
    {
        if($request_url['request_method'] === 'POST')
        {
            $this->post = $_POST;
        } else {
            $this->get = $_GET;
        }
        $this->isDevice = new MobileDetect();

        $userDetails = (new ApplicationProvider())->handle();

        $this->user = $userDetails['user'];
        $this->auth = $userDetails['auth'];
        $this->guest = $userDetails['guest'];
    }

    public function render($view_name, $variables=[])
    {
        $view = new View();
        $view->includeView($view_name, $variables);
    }

    public function redirect($url)
    {
        header('Location: '.$url);
        die();
    }

    public function permanentRedirect($url)
    {
        header("HTTP/1.1 301 Moved Permanently");
        header('Location: '.$url);
        die();
    }
}
