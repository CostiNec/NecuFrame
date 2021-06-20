<?php

namespace controllers;

use core\Controller;
use core\Exception;
use core\Helper;
use models\User;

class LoginController extends Controller
{
    public function __construct($request)
    {
        parent::__construct($request);
        if ($this->auth) {
            $this->redirect('/');
        }
    }

    public function login($request)
    {
        $user = User::where('email',$request['email'])[0];

        if (empty($user)) {
            $this->redirect('/conectare?email=invalid');
        }

        if (md5($request['password']) != $user->password) {
            $this->redirect('/conectare?parola=invalid');
        }

        $_SESSION['user'] = $user;
        $this->redirect('/');
    }

    public function logout()
    {
        session_destroy();

        $this->redirect('/');
    }
}
