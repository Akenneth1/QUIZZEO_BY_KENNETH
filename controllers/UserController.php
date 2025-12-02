<?php
// controllers/UserController.php
class UserController
{
    private $db;
    public function __construct()
    {
        $this->db = new JsonDB();
    }

    public function dashboard()
    {
        $userId = $_SESSION['user']['id'];
        $allResponses = $this->db->read('responses');
        $allQuizzes = $this->db->read('quizzes');

        // Filtrer l'historique de l'utilisateur [cite: 62]
        $myResponses = array_filter($allResponses, fn($r) => $r['user_id'] === $userId);

        // Joindre les infos du quiz pour l'affichage (Titre, etc.)
        $history = [];
        foreach ($myResponses as $r) {
            foreach ($allQuizzes as $q) {
                if ($q['id'] === $r['quiz_id']) {
                    $r['quiz_title'] = $q['title'];
                    $history[] = $r;
                }
            }
        }
        require 'views/user/dashboard.php';
    }

    public function profile()
    {
        require 'views/user/profile.php';
    }

    public function updateProfile()
    {
        // Logique pour modifier nom/email/password dans users.json 
        $id = $_SESSION['user']['id'];
        $users = $this->db->read('users');

        foreach ($users as &$u) {
            if ($u['id'] === $id) {
                $u['name'] = htmlspecialchars($_POST['name']);
                if (!empty($_POST['password'])) {
                    $u['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                }
            }
        }
        $this->db->write('users', $users);
        // Mettre à jour la session
        $_SESSION['user']['name'] = $_POST['name'];
        header('Location: index.php?page=user&action=profile&success=1');
    }
}
