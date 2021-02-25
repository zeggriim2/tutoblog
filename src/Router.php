<?php
namespace App;

class Router {
    /** @var string  */
    private $viewPath;

    /** @var \AltoRouter  */
    private $altorouter;

    public function __construct(string $viewPath)
    {
        $this->viewPath = $viewPath;
        $this->altorouter = new \AltoRouter();
    }

    public function get(string $url,string $view,?string $name = null): self
    {
        $this->altorouter->map('GET', $url,$view,$name);

        return $this;
    }

    public function url(string $url,array $params = [])
    {
        return $this->altorouter->generate($url, $params);

    }

    public function run(): self
    {
        $match = $this->altorouter->match();
        $view = $match['target'];
        $router = $this;
        ob_start();
        require $this->viewPath . DIRECTORY_SEPARATOR . $view . '.php';
        $content = ob_get_clean();
        require $this->viewPath . DIRECTORY_SEPARATOR . 'layout/default.php';
        return $this;
    }
}