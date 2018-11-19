<?php

namespace Services\User;


class UserService implements UserServiceInterface
{
    public function register($obj): void
    {
        echo 'All dependencies were resolved :)' . PHP_EOL;
    }

}