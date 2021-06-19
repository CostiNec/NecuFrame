<?php

namespace providers;

use core\Helper;
use models\User;

class ApplicationProvider
{
    private $user = null;

    private $guest = false;
    private $auth = false;

    public function handle()
    {
        $this->auth();

        return [
            'auth' => $this->auth,
            'guest' => $this->guest,
            'user' => $this->user
        ];
    }

    private function auth()
    {
        if (empty($_SESSION['user'])) {
            $this->guest = true;

            return;
        }

        $this->auth = true;
        $this->user = User::where('email',$_SESSION['user']->email)[0];
    }
}
