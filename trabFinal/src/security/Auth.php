<?php

require_once __DIR__ . '/../model/User.php';

class Auth
{

    protected UserRepository $userRepository;
    protected UserService $userService;

    public function __construct()
    {
        $mysqlConnector = new MySQLConnector();
        $this->userRepository = new UserRepository($mysqlConnector);
        $this->userService = new UserService($this->userRepository);
    }

    public static function login(User $user): void
    {
        session_start();
        $_SESSION['user'] = serialize($user);
    }

    public static function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
    }

    public static function user(): ?User
    {
        session_start();
        if (isset($_SESSION['user'])) {
            return unserialize($_SESSION['user'], ['allowed_classes' => true]);
        }

        return null;
    }

    public static function check(): bool
    {
        session_start();
        return isset($_SESSION['user_id']);
    }
}
