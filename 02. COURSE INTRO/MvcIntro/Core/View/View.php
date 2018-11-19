<?php

namespace Core\View;


class View implements ViewInterface
{
    /**
     * @var string
     */
    private $controllerName;

    /**
     * @var string
     */
    private $actionName;


    /**
     * View constructor.
     * @param string $controllerName
     * @param string $actionName
     */
    public function __construct(string $controllerName, string $actionName)
    {
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
    }


    public function render($model = null): void
    {
        include 'View/'
            . $this->controllerName
            . '/' . $this->actionName
            . '.php';
    }
}