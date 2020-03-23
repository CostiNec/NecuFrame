<?php

namespace core;

use Detection\MobileDetect;
use Symfony\Component\VarDumper\VarDumper;

if(file_exists(__DIR__.'/../vendor/mobiledetect/mobiledetectlib/namespaced/Detection/MobileDetect.php')) {
    require_once __DIR__.'/../vendor/mobiledetect/mobiledetectlib/namespaced/Detection/MobileDetect.php';
}

class View
{
    public function includeView($view_name, $variables = [])
    {
        $isDevice = new MobileDetect();
        $view = new View();
        $variables['View'] = $view;
        $variables['isDevice'] = $isDevice;
        $view_name = explode('.',$view_name);
        $file = '';
        foreach ($view_name as $view) {
            $file = $file.'/'.$view.'/';
            $file = mb_substr($file,0,-1);
        }
        extract($variables);
        if (file_exists(__DIR__ . '/../views/' . $file . '.php')) {
            include(__DIR__ . '/../views/' . $file . '.php');
        } else {
            throw(new Exception('This view does not exist!',500));
        }
    }

    public function includeErrorView($view_name, $variables = [])
    {
        $isDevice = new MobileDetect();
        $view = new View();
        $variables['View'] = $view;
        $variables['isDevice'] = $isDevice;
        $view_name = explode('.',$view_name);
        $file = '';
        foreach ($view_name as $view) {
            $file = $file.'/'.$view.'/';
            $file = mb_substr($file,0,-1);
        }
        extract($variables);
        if (file_exists(__DIR__ . '/../core/ExceptionsDesign' . $file . '.php')) {
            echo '';
            include(__DIR__ . '/../core/ExceptionsDesign' . $file . '.php');
        }
    }
}