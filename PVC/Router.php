<?php

namespace PVC;


use Symfony\Component\HttpFoundation\Request;

class Router
{
    /**
     * @var array
     */
    private $rules = [];

    /**
     * @param string $name Route name must be used by url generator
     * @param string $rule string Rule
     * @param array $defaults  array of defaults
     * @param bool $acceptNull sets default "empty value" behavior
     */
    public function mapRoute($name, $rule, array $defaults = NULL, $acceptNull = false)
    {
        $this->rules[$rule] = [
            'name' => $name,
            'rule' => $this->prepareRule($rule, $defaults),
            'defaults' => $defaults,
            'acceptNull' => $acceptNull
        ];
    }

    /**
     * @param Request $request
     * @return array|null
     */
    public function match(Request $request)
    {
        foreach ($this->rules as &$rule) {
            $uri = $request->getRequestUri();
            if (empty($uri) && isset($rule['defaults']['controller']) && isset($rule['defaults']['action'])) {
                return [
                    "target" => $rule['defaults']['controller'],
                    "action" => $rule['defaults']['action'],
                    "arguments" => $this->filterArgs($rule['defaults'])
                ];
            }
            if (!empty($uri) && preg_match($rule['rule'], $uri, $matches)) {
                $controller = isset($matches['controller']) ? $matches['controller'] : $rule['defaults']['controller'];
                $action = $matches['action'] ? : $rule['defaults']['action'];

                $def = $this->filterArgs($rule['defaults'] ? : []);
                $arr = $this->filterArgs($matches ? : []);

                if (!$rule['acceptNull']) {
                    $arr = array_filter($arr, 'strlen');
                }
                return [
                    "controller" => $controller,
                    "action" => $action,
                    "arguments" => array_merge($def, $arr)
                ];
            }
        }
        return NULL;
    }

    protected function filterArgs($arguments = [])
    {
        $result = [];
        array_walk($arguments, function ($item, $key) use (&$result) {
            if (!is_int($key) && !in_array($key, ['controller', 'action'])) {
                $result[$key] = $item;
            }
        });
        return $result;
    }

    public function prepareRule($rule, $defaults)
    {
        $convertToRegexp = function ($rule) use (&$defaults, &$convertToRegexp) {
            if (is_array($rule)) {
                $isOptional = ($defaults && array_key_exists($rule[1], $defaults));
                $optionalGroup = $isOptional ? '?' : '';
                $optionalContents = $isOptional ? '*' : '+';
                $rule = "{$optionalGroup}(?P<{$rule[1]}>[^/]{$optionalContents}){$optionalGroup}";
            }
            return preg_replace_callback('/{([^}]*)}/', $convertToRegexp, $rule);
        };

        return "/\/" . str_replace("/", "\\/", $convertToRegexp($rule)) . "/";
    }

}