<?php

namespace Core;


use Core\Http\Request;
use Core\Http\RequestInterface;
use Core\Util\Binder;

class Application
{
    private $controllerName;

    private $actionName;

    private $params;

    private $mappings = [];

    private $instances = [];

    /**
     * Application constructor.
     * @param string $controllerName
     * @param string $actionName
     * @param string[] $params
     */
    public function __construct(string $controllerName, string $actionName, array $params)
    {
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
        $this->params = $params;
    }

    public function start(): void
    {
        $request = $this->initiateRequester();
        $binder = new Binder();
        $this->instances[RequestInterface::class] = $request;

        $fullQualifiedName = 'Controllers\\'
            . ucfirst($this->controllerName)
            . 'Controller';
        $controller = new $fullQualifiedName($request);

        $methodInfo = new \ReflectionMethod($fullQualifiedName, $this->actionName);

        for ($i = \count($this->params); $i < \count($methodInfo->getParameters()); $i++) {
            $parameter = $methodInfo->getParameters()[$i];
            $paramType = $parameter->getType();

            if (null === $paramType) {
                continue;
            }

            $interfaceName = $paramType->getName();

            if (!array_key_exists($interfaceName, $this->mappings) && !array_key_exists($interfaceName, $this->instances)) {
                $bindingModelName = $interfaceName;
                $instance = new $bindingModelName();
                $binder->bindData($_POST, $instance);
            } else {
                $instance = $this->resolveDependencies($interfaceName);
            }

            $this->params[] = $instance;
        }

        \call_user_func_array(
            [$controller, $this->actionName],
            $this->params
        );
    }

    public function resolveDependencies(string $interfaceName)
    {
        if (array_key_exists($interfaceName, $this->instances)) {
            return $this->instances[$interfaceName];
        }

        $className = $this->mappings[$interfaceName];
        $classInfo = new \ReflectionClass($className);
        $constructorInfo = $classInfo->getConstructor();

        if (null === $constructorInfo
            || \count($constructorInfo->getParameters()) === 0) {
            $instance = new $className();
            $this->instances[$interfaceName] = $instance;
            return $instance;
        }

        $instanceArguments = [];

        foreach ($constructorInfo->getParameters() as $parameter) {
            $paramType = $parameter->getType();

            if (null === $paramType) {
                continue;
            }

            $dependentInterface = $paramType->getName();
            $instance = $this->resolveDependencies($dependentInterface);
            $instanceArguments[] = $instance;
        }

        $instance = $classInfo->newInstanceArgs($instanceArguments);
        return $instance;
    }

    private function initiateRequester(): RequestInterface
    {
        return new Request($this->controllerName, $this->actionName, $_SERVER['QUERY_STRING']);
    }

    public function addMapping(string $interface, string $className): void
    {
        $this->mappings[$interface] = $className;
    }

    public function addInstance(string $interface, object $instance): void
    {
        $this->instances[$interface] = $instance;
    }
}