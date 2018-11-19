<?php

namespace Core\View;


interface ViewInterface
{
    public function render($model = null): void;
}