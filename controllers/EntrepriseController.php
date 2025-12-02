<?php
class EntrepriseController
{
    private $db;
    public function __construct()
    {
        $this->db = new JsonDB();
    }

    private function ensureEntreprise()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'entreprise') {
            header('Location: index.php?page=auth&action=login');
            exit;
        }
    }

    public function dashboard()
    {
        $this->ensureEntreprise();
        $quizzes = $this->db->read('quizzes');
        $myQuizzes = array_filter($quizzes, fn($q) => $q['author_id'] === $_SESSION['user']['id']);

        $responses = $this->db->read('responses');
        foreach ($myQuizzes as &$q) {
            $q['response_count'] = count(array_filter($responses, fn($r) => $r['quiz_id'] === $q['id']));
        }
        require 'views/entreprise/dashboard.php';
    }

    public function create()
    {
        $this->ensureEntreprise();
        require 'views/entreprise/create.php';
    }

    public function store()
    {
        $this->ensureEntreprise();

        $title = trim($_POST['title'] ?? '');
        $questions = [];

        if (isset($_POST['questions'])) {
            foreach ($_POST['questions'] as $k => $qtext) {
                // Spécificité Entreprise : On récupère le type (QCM ou FREE)
                $type = $_POST['types'][$k] ?? 'qcm';
                $options = [];

                if ($type === 'qcm') {
                    $optionsRaw = $_POST['options'][$k] ?? '';
                    $options = array_map('trim', explode(',', $optionsRaw));
                }

                $questions[] = [
                    'text' => htmlspecialchars($qtext),
                    'type' => $type,
                    'options' => $options,
                    'points' => 0, 
                    'correct' => 0
                ];
            }
        }

        $newQuiz = [
            'id' => uniqid('q_'),
            'title' => htmlspecialchars($title),
            'author_id' => $_SESSION['user']['id'],
            'type' => 'entreprise', 
            'status' => 'termine',
            'active' => true,
            'questions' => $questions,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $quizzes = $this->db->read('quizzes');
        $quizzes[] = $newQuiz;
        $this->db->write('quizzes', $quizzes);

        header('Location: index.php?page=entreprise&action=dashboard');
        exit;
    }

    // Calcul des statistiques (Pourcentages)
    public function stats()
    {
        $this->ensureEntreprise();
        $id = $_GET['id'] ?? null;

        $quizzes = $this->db->read('quizzes');
        $quiz = null;
        foreach ($quizzes as $q) if ($q['id'] === $id) $quiz = $q;

        $allResponses = $this->db->read('responses');
        $quizResponses = array_filter($allResponses, fn($r) => $r['quiz_id'] === $id);
        $total = count($quizResponses);

        $stats = [];
       


        //
        foreach ($quiz['questions'] as $k => $quest) {
            $s = ['question' => $quest['text'], 'type' => $quest['type'], 'data' => []];

            if ($quest['type'] === 'qcm') {
                $counts = array_fill(0, count($quest['options']), 0);
                foreach ($quizResponses as $r) {
                    if (isset($r['answers'][$k])) {
                        $idx = intval($r['answers'][$k]);
                        if (isset($counts[$idx])) $counts[$idx]++;
                    }
                }
                foreach ($quest['options'] as $i => $opt) {
                    $s['data'][] = [
                        'label' => $opt,
                        'count' => $counts[$i],
                        'percent' => $total > 0 ? round(($counts[$i] / $total) * 100, 1) : 0
                    ];
                }
            } else {
                // Réponses libres : on liste les textes
                foreach ($quizResponses as $r) {
                    if (!empty($r['answers'][$k])) $s['data'][] = $r['answers'][$k];
                }
            }
            $stats[] = $s;
        }

        require 'views/entreprise/stats.php';
    }
}
