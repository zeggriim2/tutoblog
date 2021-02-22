<?php
namespace App;

class Router {

    private $viewPath;
    /** @var \AltoRouter  */
    private $altorouter;
    public function __construct($viewPath)
    {
        $this->viewPath = $viewPath;
        $this->altorouter = new \AltoRouter();
    }

    public function get(){
        $this->altorouter->map();
    }

    public function run(){

    }
}