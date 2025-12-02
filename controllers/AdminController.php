<?php
class AdminController
{
    private $db;
    public function __construct()
    {
        $this->db = new JsonDB();
    }

    private function ensureAdmin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?page=auth&action=login');
            exit;
        }
    }

    public function dashboard()
    {
        $this->ensureAdmin();
        $users = $this->db->read('users');
        $quizzes = $this->db->read('quizzes');
        $responses = $this->db->read('responses');
        // calculate counts
        foreach ($quizzes as &$q) {
            $q['responses_count'] = 0;
            foreach ($responses as $r) if ($r['quiz_id'] === $q['id']) $q['responses_count']++;
        }
        require 'views/admin/dashboard.php';
    }

    public function toggleUser()
    {
        $this->ensureAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) header('Location: index.php?page=admin&action=dashboard');
        $users = $this->db->read('users');
        foreach ($users as &$u) if ($u['id'] === $id) $u['active'] = !($u['active'] ?? true);
        $this->db->write('users', $users);
        header('Location: index.php?page=admin&action=dashboard');
        exit;
    }

    public function toggleQuiz()
    {
        $this->ensureAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) header('Location: index.php?page=admin&action=dashboard');
        $quizzes = $this->db->read('quizzes');
        foreach ($quizzes as &$q) if ($q['id'] === $id) $q['active'] = !($q['active'] ?? true);
        $this->db->write('quizzes', $quizzes);
        header('Location: index.php?page=admin&action=dashboard');
        exit;
    }
}
