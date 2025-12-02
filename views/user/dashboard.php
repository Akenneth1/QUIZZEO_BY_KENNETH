<?php require 'views/layout/header.php'; ?>
<h1>Mes participations</h1>

<?php if (empty($history)): ?>
    <p>Vous n'avez répondu à aucun quiz pour le moment.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Quiz</th>
                <th>Votre Note / Résultat</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($history as $h): ?>
                <tr>
                    <td><?= $h['quiz_title'] ?></td>
                    <td>
                        <?php if ($h['max_score'] > 0): ?>
                            <?= $h['score'] ?> / <?= $h['max_score'] ?>
                        <?php else: ?>
                            Sondage complété
                        <?php endif; ?>
                    </td>
                    <td><?= $h['date'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require 'views/layout/footer.php'; ?>