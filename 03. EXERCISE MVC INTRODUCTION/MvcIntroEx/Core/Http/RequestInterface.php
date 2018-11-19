<?php

namespace Core\Http;


interface RequestInterface
{
    public function getControllerName(): string;

    public function getActionName(): string;

    public function getQueryString(): string;
}