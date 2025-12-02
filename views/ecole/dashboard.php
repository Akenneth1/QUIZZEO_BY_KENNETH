<?php require 'views/layout/header.php'; ?>
<h1>Tableau de bord École</h1>
<a href="index.php?page=ecole&action=create" class="btn">Créer un nouveau Quiz</a>

<table>
    <thead>
        <tr>
            <th>Titre</th>
            <th>Statut</th>
            <th>Nb Réponses</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($mine as $q): ?>
            <tr>
                <td><?= $q['title'] ?></td>
                <td><?= $q['status'] ?></td>
                <td><?= $q['response_count'] ?? 0 ?></td>
                <td>
                    <small>Lien: <a href="index.php?page=quiz&action=take&id=<?= $q['id'] ?>">Voir le quiz</a></small><br>
                    <a href="index.php?page=ecole&action=results&id=<?= $q['id'] ?>">Voir les notes</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require 'views/layout/footer.php'; ?>