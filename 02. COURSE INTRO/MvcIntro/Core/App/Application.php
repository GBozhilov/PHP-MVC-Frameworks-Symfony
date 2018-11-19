<?php

namespace Core\App;


use Core\View\ViewInterface;

class Application
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
     * @var array
     */
    private $params;

    /**
     * Application constructor.
     * @param string $controllerName
     * @param string $actionName
     * @param array $params
     */
    public function __construct(string $controllerName, string $actionName, array $params)
    {
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
        $this->params = $params;
    }


    public function run(ViewInterface $view): void
    {
        $controllerName = 'Controller\\' . $this->controllerName . 'Controller';
        $controller = new $controllerName($view);

        if (!\is_callable([$controller, $this->actionName])) {
            throw new \Exception('Action not exists');
        }

        $actionData = new \ReflectionMethod($controllerName, $this->actionName);
        $aparams = $actionData->getParameters();
        $params = [];

        foreach ($aparams as $param) {
            $class = $param->getClass();

            if ($class) {
                $className = $class->getName();
                $paramObj = new $className();
                $properties = new \ReflectionClass($paramObj);

                foreach ($properties->getProperties() as $property) {
                    $propertyName = $property->getName();

                    if (array_key_exists($propertyName, $_POST)) {
                        $setter = 'set' . ucfirst($propertyName);
                        $paramObj->$setter($_POST[$propertyName]);
                    }
                }
            }

            $params[] = $paramObj;
        }

        \call_user_func_array(
            [$controller, $this->actionName],
            $params
        );
    }
}