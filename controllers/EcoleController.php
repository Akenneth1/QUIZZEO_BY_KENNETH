<?php
class EcoleController
{
    private $db;
    public function __construct()
    {
        $this->db = new JsonDB();
    }

    private function ensureEcole()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ecole') {
            header('Location: index.php?page=auth&action=login');
            exit;
        }
    }

    // Affiche le tableau de bord
    public function dashboard()
    {
        $this->ensureEcole();
        $quizzes = $this->db->read('quizzes');
        // Filtre pour n'afficher que MES quiz
        $mine = array_filter($quizzes, fn($q) => $q['author_id'] === $_SESSION['user']['id']);

        // Compte les réponses pour chaque quiz
        $responses = $this->db->read('responses');
        foreach ($mine as &$q) {
            $q['response_count'] = count(array_filter($responses, fn($r) => $r['quiz_id'] === $q['id']));
        }
        require 'views/ecole/dashboard.php';
    }

    // Affiche le formulaire de création
    public function create()
    {
        $this->ensureEcole();
        require 'views/ecole/create.php';
    }

    // Sauvegarde le quiz (Action du formulaire)
    public function store()
    {
        $this->ensureEcole();

        $title = trim($_POST['title'] ?? '');
        if (!$title) {
            header('Location: index.php?page=ecole&action=create');
            exit;
        }

        $questions = [];
        // On boucle sur les questions envoyées par le formulaire
        if (isset($_POST['questions'])) {
            foreach ($_POST['questions'] as $k => $qtext) {
                // Traitement des options (séparées par virgule)
                $optionsRaw = $_POST['options'][$k] ?? '';
                $options = array_map('trim', explode(',', $optionsRaw));

                $questions[] = [
                    'text' => htmlspecialchars($qtext),
                    'type' => 'qcm', // Force le type QCM pour les écoles
                    'options' => $options,
                    'points' => intval($_POST['points'][$k] ?? 1),
                    'correct' => intval($_POST['correct'][$k] ?? 1) - 1 // Convertit "1" en index "0"
                ];
            }
        }

        $newQuiz = [
            'id' => uniqid('q_'),
            'title' => htmlspecialchars($title),
            'author_id' => $_SESSION['user']['id'],
            'type' => 'ecole',
            'status' => 'termine', // Statut par défaut
            'active' => true,
            'questions' => $questions,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $quizzes = $this->db->read('quizzes');
        $quizzes[] = $newQuiz;
        $this->db->write('quizzes', $quizzes);

        header('Location: index.php?page=ecole&action=dashboard');
        exit;
    }

    // Voir les notes des élèves
    public function results()
    {
        $this->ensureEcole();
        $id = $_GET['id'] ?? null;

        $responses = $this->db->read('responses');
        $users = $this->db->read('users');
        $quizzes = $this->db->read('quizzes');

        // Récupérer le quiz pour le titre
        $quiz = null;
        foreach ($quizzes as $q) if ($q['id'] === $id) $quiz = $q;

        // Filtrer les réponses pour ce quiz uniquement
        $results = array_filter($responses, fn($r) => $r['quiz_id'] === $id);

        require 'views/ecole/results.php';
    }
}
