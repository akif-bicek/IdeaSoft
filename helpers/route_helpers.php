<?php
class route_helpers{
    protected $requestMethod;
    protected $requestUri;
    protected $controller;
    protected $action;
    protected $parameters;
    function __construct() {
        $request = new request_helpers();
        $request->setBaseUrl(BaseUrl);
        $request->createRequest();
        $this->requestUri = strtolower($request->getControllerClassName());
        $this->parameters = $request->getParameters();

        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
    }
    protected function parseControllerAction($contollerAction){
        $contollerAction = explode(".", $contollerAction);
        $this->controller = $contollerAction[0];
        $this->action = $contollerAction[1];
    }
    protected function controlAndParse($type, $url, $controllerAction){
        if (($this->requestMethod === $type) and ($this->requestUri === $url)){
            $this->parseControllerAction($controllerAction);
            return true;
        }else{
            return false;
        }
    }
    protected function controllerCore($type, $url, $controllerAction){
        if ($this->controlAndParse($type, $url, $controllerAction)){
            $control = new $this->controller;
            if(method_exists($control, $this->action)){
                $action = $this->action;
                $control->$action($this->parameters);
            }else{
                die("404 not found");
            }
        }
    }
    public function post($url, $controllerAction){
        $this->controllerCore("POST", $url, $controllerAction);
    }
    public function get($url, $controllerAction){
        $this->controllerCore("GET", $url, $controllerAction);
    }
    public function put($url, $controllerAction){
        $this->controllerCore("PUT", $url, $controllerAction);
    }
    public function delete($url, $controllerAction){
        $this->controllerCore("DELETE", $url, $controllerAction);
    }
}
?>