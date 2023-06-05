<?php

// UserService.php
class UserService {
    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function login($email, $password) {
        $user = $this->userRepository->authenticate($email, $password);

        if ($user) {
            return new User($user['email'], $user['name'], $user['password']);
        } else {
            return false;
        }
    }

    public function register($email, $name, $password) {
        $user = new User($email, $name, $password);

        if ($this->userRepository->save($user)) {            
            return $user;
        } else {
            return false;
        }
    }
}
