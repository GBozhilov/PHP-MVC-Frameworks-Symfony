<?php

namespace Service;


use Repository\UserRepository;

class UserService
{

    public function register($user): void
    {
        $userRepository = new UserRepository();
        $userRepository->insert($user);
    }
}