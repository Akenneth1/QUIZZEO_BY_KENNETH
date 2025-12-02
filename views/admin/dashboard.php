<?php require 'views/layout/header.php'; ?>
<h1>Administration</h1>

<h3>Gestion des Utilisateurs</h3>
<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['name'] ?></td>
                <td><?= $u['email'] ?></td>
                <td><?= $u['role'] ?></td>
                <td>
                    <?php if ($u['role'] !== 'admin'): ?>
                        <a href="index.php?page=admin&action=toggleUser&id=<?= $u['id'] ?>" class="btn <?= $u['active'] ? 'btn-danger' : 'btn' ?>">
                            <?= $u['active'] ? 'Désactiver' : 'Activer' ?>
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Gestion des Quiz</h3>
<table>
    <thead>
        <tr>
            <th>Titre</th>
            <th>Auteur</th>
            <th>Réponses</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($quizzes as $q): ?>
            <tr>
                <td><?= $q['title'] ?></td>
                <td><?= $q['type'] ?></td>
                <td><?= $q['responses_count'] ?></td>
                <td><?= $q['active'] ? 'Actif' : 'Désactivé' ?></td>
                <td>
                    <a href="index.php?page=admin&action=toggleQuiz&id=<?= $q['id'] ?>" class="btn <?= $q['active'] ? 'btn-danger' : 'btn' ?>">
                        <?= $q['active'] ? 'Désactiver' : 'Activer' ?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require 'views/layout/footer.php'; ?>