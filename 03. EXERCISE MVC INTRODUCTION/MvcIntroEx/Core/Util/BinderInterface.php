<?php

namespace Core\Util;


interface BinderInterface
{
    public function bindData(array $array, $object);
}