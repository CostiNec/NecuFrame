<?php

namespace core;

use core\Helper;
use Symfony\Component\VarDumper\VarDumper;

class Route
{
    private $_GET;
    private $_POST;
    private $request;

    public function __construct()
    {
        $this->_POST = [];
        $this->_GET = [];
        $this->request = [];

    }

    public static function getInbetweenStrings($start, $end, $str){
        $matches = array();
        $regex = "/$start([a-zA-Z0-9_]*)$end/";
        preg_match_all($regex, $str, $matches);
        return $matches[1];
    }

    public function checkUrl($url)
    {
        $webURL = explode('?',$_SERVER['REQUEST_URI'])[0];
        $request = [];
        $this->request = [];
        $infos = self::getInbetweenStrings('{','}',$url);
        $exploadedUrl = explode('/',$webURL);
        $chekUrlExploade = explode('/',$url);

        if(count($exploadedUrl) != count($chekUrlExploade))
        {
            return false;
        }

        if($exploadedUrl[1] == '')
        {
            if($url != '/')
                return false;
            else
                return true;
        }

        if(count($infos) > count($exploadedUrl) - 1)
        {
            return false;
        }

        if(count($infos) == 0) {
            if($webURL != $url)
                return false;
            else
                return true;
        }

        for($i = 0; $i < count($exploadedUrl) - count($infos); $i++)
        {
            if($exploadedUrl[$i] != $chekUrlExploade[$i])
                return false;
        }

        for($i = count($exploadedUrl) - count($infos); $i < count($exploadedUrl)  ; $i ++) {
            $request[$infos[$i+count($infos)-count($exploadedUrl)]] = $exploadedUrl[$i];
        }

        $this->request = $request;

        return true;
    }

    public function get($link ,$controller, $method)
    {
        if(!self::checkUrl($link))
            return false;

        array_push($this->_GET,[
            'url' => $link,
            'controller' => $controller,
            'method' => $method,
            'request' => $this->request,
            'request_method' => 'GET'
        ]);
    }

    public function post($link ,$controller, $method)
    {
        if(!self::checkUrl($link))
            return false;

        array_push($this->_POST,[
            'url' => $link,
            'controller' => $controller,
            'method' => $method,
            'request' => $this->request,
            'request_method' => 'POST'
        ]);
    }

    public function execute()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($this->_POST as $one) {
                if($one['request_method'] === $_SERVER['REQUEST_METHOD']) {
                    return $one;
                }
            }

            if(count($this->_GET) > 0 ) {
                throw(new Exception('Method not supported',405));
            }

        } else {
            foreach ($this->_GET as $one) {
                if($one['request_method'] === $_SERVER['REQUEST_METHOD']) {
                    return $one;
                }
            }

            if(count($this->_POST) > 0 ) {
                throw(new Exception('Method not supported',405));
            }
        }
        if(!count($this->_GET) && !count($this->_POST)) {
            throw(new Exception('404 Not found',404));
        }

    }
}