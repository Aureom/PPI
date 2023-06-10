<?php

class UserService {
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function login($email, $password): ?User {
        return $this->userRepository->authenticate($email, $password);
    }

    public function register($email, $name, $password): ?User {
        return $this->userRepository->createNewUser($email, $name, $password);
    }
}
