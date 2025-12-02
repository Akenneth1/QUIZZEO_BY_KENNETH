<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Quizzeo</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* CSS Rapide pour la mise en page */
        body {
            font-family: sans-serif;
            background: #f4f4f4;
            margin: 0;
        }

        nav {
            background: #333;
            color: #fff;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin-left: 15px;
        }

        .logo {
            font-weight: bold;
            font-size: 1.5rem;
            color: #FFD700;
        }

        /* Couleurs logo */
        .container {
            padding: 20px;
            max-width: 1000px;
            margin: auto;
            background: white;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .btn {
            padding: 8px 15px;
            background: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-danger {
            background: #dc3545;
        }

        .alert {
            padding: 10px;
            background: #f8d7da;
            color: #721c24;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
    </style>
</head>

<body>
    <nav>
        <div class="logo">QUIZZEO</div>
        <div>
            <?php if (isset($_SESSION['user'])): ?>
                <span>Bonjour, <?= $_SESSION['user']['name'] ?> (<?= ucfirst($_SESSION['user']['role']) ?>)</span>

                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                    <a href="index.php?page=admin&action=dashboard">Dashboard</a>
                <?php elseif ($_SESSION['user']['role'] === 'ecole'): ?>
                    <a href="index.php?page=ecole&action=dashboard">Mes Quiz</a>
                <?php elseif ($_SESSION['user']['role'] === 'entreprise'): ?>
                    <a href="index.php?page=entreprise&action=dashboard">Mes Sondages</a>
                <?php else: ?>
                    <a href="index.php?page=user&action=dashboard">Mes Réponses</a>
                <?php endif; ?>

                <a href="index.php?page=auth&action=logout">Déconnexion</a>
            <?php else: ?>
                <a href="index.php?page=auth&action=login">Connexion</a>
                <a href="index.php?page=auth&action=register">Inscription</a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="container">