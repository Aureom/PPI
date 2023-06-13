<?php

class UserService {
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function findByEmail($email): ?User {
        return $this->userRepository->findByEmail($email);
    }

    public function login($email, $password): ?User {
        return $this->userRepository->authenticate($email, $password);
    }

    public function register($email, $name, $password, $cpf, $phone): ?User {
        return $this->userRepository->createNewUser($email, $name, $password, $cpf, $phone);
    }

    public function update($id, $name, $password, $cpf, $phone): ?User {
        return $this->userRepository->updateUser($id, $name, $password, $cpf, $phone);
    }
}
