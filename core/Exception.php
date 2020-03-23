<?php


namespace core;

use core\View;
use Throwable;

class Exception extends \Exception
{
    private $json;
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        foreach (getallheaders() as $name => $value) {
            if($name == 'Accept' && strpos(strtolower($value),'json') !== false) {
                $this->json = true;
            } else {
                $this->json = false;
            }
        }
    }

    public function render()
    {
        $View = new View();
        http_response_code($this->getCode());

        $parameters = [
            'errorCode' => $this->getCode(),
            'message' => $this->getMessage()
        ];

        if($this->json) {
            echo json_encode(['message' => $this->getMessage()]);
        } else {
            $View->includeErrorView('error',$parameters);
        }
    }
}