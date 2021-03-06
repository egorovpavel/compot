<?php

namespace compot;

class Router
{
    /**
     * @var Route[]
     */
    private $routes = [];

    /**
     * @param string $name       Route name must be used by url generator
     * @param string $rule       string Rule
     * @param array  $defaults   array of defaults
     * @param bool   $acceptNull sets default "empty value" behavior
     */
    public function mapRoute($name, $rule, array $defaults = null, $acceptNull = false)
    {
        $this->routes[$name] = new Route($name, $rule, $defaults, $acceptNull);
    }

    /**
     * @param string $uri
     *
     * @return Route|null
     */
    public function match($uri)
    {
        $uri = trim($uri,"/");
        foreach ($this->routes as &$route) {
            if ($route->match($uri)) {
                return $route;
            }
        }

        return null;
    }

    public function getRoute($name)
    {
        return $this->routes[$name];
    }
}
