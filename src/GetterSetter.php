<?php

namespace BCLib\PrimoClient;

use BCLib\PrimoClient\Exceptions\BadPropertyException;

trait GetterSetter
{

    public function __get($name)
    {
        $prop = $this->getProperty($name);
        return $this->$prop;
    }

    public function __set($name, $value)
    {
        $setter = $this->getSetter($name);
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } else {
            $prop = $this->getProperty($name);
            $this->$prop = $value;
        }
    }

    protected function getProperty($name): string
    {
        $prop = "_$name";
        if (!property_exists($this, $prop)) {
            throw new BadPropertyException("$name is not a valid property on " . self::class);
        }
        return $prop;
    }

    protected function getSetter($property): string
    {
        $camel_case_param = str_replace('_', '', lcfirst(ucwords($property, '_')));
        return "set$camel_case_param";
    }
}