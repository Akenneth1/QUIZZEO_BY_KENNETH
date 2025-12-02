<?php
class AuthController
{
    private $db;
    public function __construct()
    {
        $this->db = new JsonDB();
    }

    public function login()
    {
        require 'views/auth/login.php';
    }
    public function register()
    {
        require 'views/auth/register.php';
    }
    public function logout()
    {
        session_destroy();
        header('Location: index.php');
        exit;
    }

    // Vérification de la connexion
    public function check()
    {
        $email = $_POST['email'] ?? '';
        $pass = $_POST['password'] ?? '';
        $users = $this->db->read('users');

        foreach ($users as $u) {
            if ($u['email'] === $email && password_verify($pass, $u['password'])) {
                if (!$u['active']) {
                    $error = "Compte désactivé.";
                    require 'views/auth/login.php';
                    return;
                }
                $_SESSION['user'] = $u;

                // REDIRECTION SELON LE ROLE [Source: 27-31]
                if ($u['role'] === 'admin') header('Location: index.php?page=admin&action=dashboard');
                elseif ($u['role'] === 'ecole') header('Location: index.php?page=ecole&action=dashboard');
                elseif ($u['role'] === 'entreprise') header('Location: index.php?page=entreprise&action=dashboard');
                else header('Location: index.php?page=user&action=dashboard');
                exit;
            }
        }
        $error = "Identifiants incorrects";
        require 'views/auth/login.php';
    }

    // Enregistrement du nouvel utilisateur
    public function store()
    {
        // Vérif Captcha [Source: 26]
        if (intval($_POST['captcha']) !== intval($_POST['captcha_expected'])) {
            $error = "Captcha incorrect";
            require 'views/auth/register.php';
            return;
        }

        $users = $this->db->read('users');

        // Le tout premier utilisateur devient ADMIN automatiquement
        // Sinon, on prend le rôle choisi dans le formulaire
        $role = empty($users) ? 'admin' : $_POST['role'];

        $newUser = [
            'id' => uniqid('u_'),
            'name' => htmlspecialchars($_POST['name']),
            'email' => htmlspecialchars($_POST['email']),
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'role' => $role,
            'active' => true,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $users[] = $newUser;
        $this->db->write('users', $users);

        header('Location: index.php?page=auth&action=login');
    }
}
