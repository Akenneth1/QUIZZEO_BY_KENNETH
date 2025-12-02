<?php
class QuizController
{
    private $db;
    public function __construct()
    {
        $this->db = new JsonDB();
    }

    // Affiche le quiz pour y répondre
    public function take()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) die("Lien invalide");

        $quizzes = $this->db->read('quizzes');
        $quiz = null;
        foreach ($quizzes as $q) if ($q['id'] === $id) $quiz = $q;

        if (!$quiz || !$quiz['active']) die("Ce quiz n'est pas disponible.");

        // Sécurité : Faut-il être connecté ? (Oui, selon le cahier des charges)
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=auth&action=login');
            exit;
        }

        require 'views/quiz/take.php';
    }

    // Traite la réponse
    public function submit()
    {
        if (!isset($_SESSION['user'])) header('Location: index.php');

        $quizId = $_POST['quiz_id'];
        $answers = $_POST['answers'] ?? [];

        // Récupérer le quiz pour comparer les réponses
        $quizzes = $this->db->read('quizzes');
        $quiz = null;
        foreach ($quizzes as $q) if ($q['id'] === $quizId) $quiz = $q;

        $score = 0;
        $maxScore = 0;

        // Calcul du score UNIQUEMENT pour les écoles
        if ($quiz['type'] === 'ecole') {
            foreach ($quiz['questions'] as $k => $quest) {
                $maxScore += $quest['points'];
                // Si la réponse de l'utilisateur correspond à la bonne réponse définie par le prof
                if (isset($answers[$k]) && intval($answers[$k]) === intval($quest['correct'])) {
                    $score += $quest['points'];
                }
            }
        }

        // Sauvegarde
        $newResponse = [
            'id' => uniqid('r_'),
            'quiz_id' => $quizId,
            'user_id' => $_SESSION['user']['id'],
            'answers' => $answers,
            'score' => $score,
            'max_score' => $maxScore,
            'date' => date('Y-m-d H:i:s')
        ];

        $responses = $this->db->read('responses');
        $responses[] = $newResponse;
        $this->db->write('responses', $responses);

        // Redirection vers le profil utilisateur
        header('Location: index.php?page=user&action=dashboard');
        exit;
    }
}
