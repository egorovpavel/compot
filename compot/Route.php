<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 11/9/13
 * Time: 2:43 PM
 * To change this template use File | Settings | File Templates.
 */

namespace compot;

class Route
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $rule;
    /**
     * @var array
     */
    protected $defaults;
    /**
     * @val bool
     */
    protected $acceptNull;
    /**
     * @var string
     */
    protected $target;
    /**
     * @var string
     */
    protected $action;
    /**
     * @var array
     */
    protected $arguments;

    /**
     * @var string
     */
    protected $rawRule;

    public function __construct($name, $rule, $defaults = null, $acceptNull = false)
    {
        $this->rawRule    = $rule;
        $this->name       = $name;
        $this->rule       = self::prepareRule($rule, $defaults);
        $this->defaults   = $defaults;
        $this->acceptNull = $acceptNull;
    }

    public static function prepareRule($rule, $defaults)
    {
        $convertToRegexp = function ($rule) use (&$defaults, &$convertToRegexp) {
            if (is_array($rule)) {
                $isOptional       = ($defaults && array_key_exists($rule[1], $defaults));
                $optionalGroup    = $isOptional
                    ? '?'
                    : '';
                $optionalContents = $isOptional
                    ? '*'
                    : '+';
                $rule             = "{$optionalGroup}(?P<{$rule[1]}>[^/]{$optionalContents}){$optionalGroup}";
            }

            return preg_replace_callback('/{([^}]*)}/', $convertToRegexp, $rule);
        };

        return "/^" . str_replace("/", "\\/", $convertToRegexp($rule)) . "$/";
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param string $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @return string
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @param string $rule
     */
    public function setRule($rule)
    {
        $this->rule = $rule;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * @param array $defaults
     */
    public function setDefaults($defaults)
    {
        $this->defaults = $defaults;
    }

    /**
     * @return mixed
     */
    public function getAcceptNull()
    {
        return $this->acceptNull;
    }

    /**
     * @param mixed $acceptNull
     */
    public function setAcceptNull($acceptNull)
    {
        $this->acceptNull = $acceptNull;
    }

    /**
     * @param $uri
     *
     * @return Route|null
     */
    public function match($uri)
    {

        if (!empty($uri) && preg_match($this->rule, $uri, $matches)) {
            $controller = isset($matches['controller']) && $matches['controller']
                ? $matches['controller']
                : $this->defaults['controller'];
            $action     = $matches['action']
                ? : $this->defaults['action'];

            $def = $this->filterArgs(
                $this->defaults
                    ? : []
            );
            $arr = $this->filterArgs(
                $matches
                    ? : []
            );

            if (!$this->acceptNull) {
                $arr = array_filter($arr, 'strlen');
            }

            $this->target    = $controller;
            $this->action    = $action;
            $this->arguments = array_merge($def, $arr);

            return $this;
        }

        return null;
    }

    protected function filterArgs($arguments = [])
    {
        $result = [];
        array_walk(
            $arguments,
            function ($item, $key) use (&$result) {
                if (!is_int($key) && !in_array($key, ['controller', 'action'])) {
                    $result[$key] = $item;
                }
            }
        );

        return $result;
    }

    public function generateFor($controller = null, $action = null, $args = [])
    {
        $map = array_merge(
            [
                'controller' => strtolower(
                    $controller
                        ? : $this->defaults['controller']
                ),
                'action'     => strtolower(
                    $action
                        ? : $this->defaults['action']
                )
            ],
            $args
                ? : []
        );

        $placeholders = [];

        if (!empty($map)) {
            foreach ($map as $placeholder => $value) {
                $placeholders["{{$placeholder}}"] = $value;
            }
        }

        $uri = str_replace(array_keys($placeholders), array_values($placeholders), $this->rawRule);

        return $uri;
    }
}
