<?php
class request_helpers{
    const CONTROLLER_CLASSNAME = 'index';
    protected $controllerkey = 0;
    protected $baseUrl;
    protected $controllerClassName;
    protected $parameters;

    public function __construct()
    {
        // set defaults
        $this->controllerClassName = self::CONTROLLER_CLASSNAME;
    }

    public function setBaseUrl($url)
    {
        $this->baseUrl = $url;
        return $this;
    }

    public function setParameters($params)
    {
        $this->parameters = $params;
        return $this;
    }

    public function getParameters()
    {
        if ($this->parameters == null) {
            $this->parameters = array();
        }
        return $this->parameters;
    }

    public function getControllerClassName()
    {
        return $this->controllerClassName;
    }

    public function getParam($name, $default = null)
    {
        if (isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }
        return $default;
    }

    public function getRequestUri()
    {
        if (!isset($_SERVER['REQUEST_URI'])) {
            return '';
        }

        $uri = $_SERVER['REQUEST_URI'];
        $uri = trim(str_replace($this->baseUrl, '', $uri), '/');

        return other_helpers::security($uri);
    }

    public function createRequest()
    {
        $uri = $this->getRequestUri();

        // Uri parts
        $uriParts = explode('/', $uri);

        // if we are in index page
        if (!isset($uriParts[$this->controllerkey])) {
            return $this;
        }

        // format the controller class name
        $this->controllerClassName = $this->formatControllerName($uriParts[$this->controllerkey]);

        // remove controller name from uri
        unset($uriParts[$this->controllerkey]);

        // now add $_POST data
        if (count($_POST) > 0) {
            foreach ($_POST as $postKey => $postData) {
                $postData = other_helpers::security($postData);
                $postKey = other_helpers::security($postKey);
                $this->parameters[$postKey] = $postData;
            }
        }

        // if there are no parameters left
        if (empty($uriParts)) {
            return $this;
        }

        // find and setup parameters starting from $_GET to $_POST
        $i = 0;
        $keyName = '';
        foreach ($uriParts as $key => $value) {
            $value = other_helpers::security($value);
            if ($i == 0) {
                $this->parameters[$value] = '';
                $keyName = $value;
                $i = 1;
            } else {
                $this->parameters[$keyName] = $value;
                $i = 0;
            }
        }
        return $this;
    }
    protected function formatControllerName($unformatted)
    {
        if (strpos($unformatted, '-') !== false) {
            $formattedName = array_map('ucwords', explode('-', $unformatted));
            $formattedName = join('', $formattedName);
        } else {
            // string is one word
            $formattedName = ucwords($unformatted);
        }

        // if the string starts with number
        if (is_numeric(substr($formattedName, 0, 1))) {
            $part = $part == $this->controllerkey ? 'controller' : 'action';
            throw new Exception('Incorrect ' . $part . ' name "' . $formattedName . '".');
        }
        return ltrim($formattedName, '_');
    }
}
?>