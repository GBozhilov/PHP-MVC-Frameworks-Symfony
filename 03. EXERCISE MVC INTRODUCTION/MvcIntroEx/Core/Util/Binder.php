<?php

namespace Core\Util;


class Binder implements BinderInterface
{
    public function bindData(array $array, $object): void
    {
        foreach ($array as $name => $value) {
            $parts = array_map('ucfirst', explode('_', $name));
            $methodName = 'set' . implode('', $parts);

            if (method_exists($object, $methodName)) {
                $object->$methodName($value);
            }
        }
    }
}