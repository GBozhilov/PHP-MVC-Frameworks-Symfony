<?php

namespace Core\Http;


class Request implements RequestInterface
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
     * @var string
     */
    private $queryString;

    /**
     * Request constructor.
     * @param string $controllerName
     * @param string $actionName
     * @param string $queryString
     */
    public function __construct(string $controllerName, string $actionName, string $queryString)
    {
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
        $this->queryString = $queryString;
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        return $this->actionName;
    }

    /**
     * @return string
     */
    public function getQueryString(): string
    {
        return $this->queryString;
    }
}