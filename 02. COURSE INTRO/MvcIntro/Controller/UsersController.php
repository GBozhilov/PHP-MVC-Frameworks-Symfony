<?php

namespace Controller;


use Core\View\ViewInterface;
use Model\UserProfileViewModel;
use Model\UserRegisterFormModel;
use Service\UserService;

class UsersController
{
    /**
     * @var ViewInterface
     */
    private $view;


    /**
     * UsersController constructor.
     * @param ViewInterface $view
     */
    public function __construct(ViewInterface $view)
    {
        $this->view = $view;
    }


    public function profile(string $firstName, string $lastName): void
    {
        $model = new UserProfileViewModel($firstName, $lastName);
        $this->view->render($model);
    }

    public function register(): void
    {
        $this->view->render();
    }

    public function registerSave(UserRegisterFormModel $user): void
    {
        $userService = new UserService();
        $userService->register($user);
    }
}