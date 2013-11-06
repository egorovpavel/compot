<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel_000
 * Date: 10/23/13
 * Time: 8:56 PM
 * To change this template use File | Settings | File Templates.
 */

namespace PVC;


use PVC\Converters\DefaultConverter;
use Symfony\Component\HttpFoundation\Request;

class DefaultValueProvider implements IValueProvider
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var IConverter[]
     */
    protected $converters = array();

    /**
     * @var IConverter
     */
    protected $defaultConverter;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->defaultConverter = new DefaultConverter();
    }

    public function getValue($prefix, $name, \ReflectionClass $type = null)
    {
        if ($type) {
            if (isset($this->converters[$type->getName()])) {

                return $this->converters[$type->getName()]->convert($type, $this->getFromPrefix($prefix, $name));
            }
            return $this->defaultConverter->convert($type, $this->getFromPrefix($prefix, $name));
        }
        return $this->getFromPrefix($prefix, $name);
    }

    /**
     * @param $type
     * @return IConverter
     */
    public function getConverterFor($type)
    {
        return $this->converters[$type];
    }

    public function addConverter($type, IConverter $converter)
    {
        $this->converters[$type] = $converter;
    }

    protected function getFromPrefix($prefix, $name)
    {
        for ($i = $this->request; $key = array_shift($prefix); $i = $i->get($key)) {
            if (!$i->get($key)) return null;
        }
        return $i instanceof Request ? $i->get($name) : $i[$name] ? : null;
    }
}