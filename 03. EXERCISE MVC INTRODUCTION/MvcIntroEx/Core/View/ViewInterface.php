<?php

namespace Core\View;


interface ViewInterface
{
    public function render($templateName = null, $model = null): void;
}