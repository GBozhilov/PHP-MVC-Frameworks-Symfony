<?php

namespace Controllers;


use Core\Http\RequestInterface;
use Core\View\ViewInterface;
use Models\FormModels\UserRegisterForm;
use Services\Mail\MailServiceInterface;
use Services\User\UserServiceInterface;

class UsersController
{
    /**
     * @var RequestInterface
     */
    private $request;


    /**
     * UsersController constructor.
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }


    public function test(): void
    {
        echo 'TEST';
    }

    public function register(
        $id,
        MailServiceInterface $mailService,
        UserServiceInterface $userService,
        ViewInterface $view,
        UserRegisterForm $userRegisterForm): void
    {
        $view->render($userRegisterForm);
        //$userService->register([]);
        //$mailService->sendMAil();
    }
}