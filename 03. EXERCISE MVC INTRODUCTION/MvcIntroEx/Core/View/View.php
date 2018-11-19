<?php

namespace Core\View;


use Core\Http\RequestInterface;

class View implements ViewInterface
{
    private const DEFAULT_TEMPLATE_FOLDER = 'views';
    private const DEFAULT_TEMPLATE_EXTENSION = '.php';

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * View constructor.
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function render($templateName = null, $model = null): void
    {
        if (\is_object($templateName)) {
            $model = $templateName;
            $templateName = null;
        }

        if (null === $templateName) {
            include self::DEFAULT_TEMPLATE_FOLDER
                . DIRECTORY_SEPARATOR
                . $this->request->getControllerName()
                . DIRECTORY_SEPARATOR
                . $this->request->getActionName()
                . self::DEFAULT_TEMPLATE_EXTENSION;
        } else {
            include self::DEFAULT_TEMPLATE_FOLDER
                . DIRECTORY_SEPARATOR
                . $templateName
                . self::DEFAULT_TEMPLATE_EXTENSION;
        }
    }
}