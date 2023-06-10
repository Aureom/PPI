<?php

require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../service/UserService.php';

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
        if (isset($_SESSION['user'])) {
            return unserialize($_SESSION['user'], ['allowed_classes' => true]);
        }

        return null;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function register(string $email, string $name, string $password): ?User
    {
        $auth = new Auth();
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = $auth->userService->register($email, $name, $hashPassword);

        if ($user) {
            self::login($user);
            return $user;
        }

        return null;
    }

    public static function attempt($email, $password): bool
    {
        $auth = new Auth();
        $user = $auth->userService->login($email, $password);

        if ($user) {
            self::login($user);
            return true;
        }

        return false;
    }
}
