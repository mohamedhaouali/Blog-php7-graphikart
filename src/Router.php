<?php
namespace App;

class Router {
    /**
     * @var string
     */

    private $viewPath;

    /**
     * @var AltoRouter
     */

    private $router;

    public $layout = "layouts/default";

    public function __construct(string $viewPath)
    {
      $this->viewPath = $viewPath;
      $this->router = new \AltoRouter();
    }

    public function get(string $url,string $views, ?string $name = null): self
    {
        $this->router->map('GET', $url, $views, $name);
        return $this;

}

    public function post(string $url,string $views, ?string $name = null): self
    {
        $this->router->map('POST', $url, $views, $name);
        return $this;

    }

    public function match(string $url,string $views, ?string $name = null): self
    {
        $this->router->map('POST|GET', $url, $views, $name);
        return $this;

    }

 public function url (string $name, array $params = [])
 {
        return $this->router->generate($name, $params);
 }



    public function run(): self
    {
        $match = $this->router->match();
        $view = $match['target'];
        // on ajoute cette ligne
        $params = $match['params'];
        $router = $this;
        ob_start();
        require $this->viewPath . DIRECTORY_SEPARATOR . $view . '.php';
        $content = ob_get_clean();
        require $this->viewPath . DIRECTORY_SEPARATOR . $this->layout . '.php';

        return $this;
    }
}