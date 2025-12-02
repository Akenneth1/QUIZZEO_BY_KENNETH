<?php require 'views/layout/header.php'; ?>
<h1>Résultats : <?= $quiz['title'] ?></h1>
<a href="index.php?page=ecole&action=dashboard" class="btn">Retour</a>

<table>
    <thead>
        <tr>
            <th>Élève</th>
            <th>Note</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $r): ?>
            <tr>
                <td>
                    <?php
                    // Recherche du nom de l'élève (optimisation possible)
                    $studentName = "Inconnu";
                    foreach ($users as $u) if ($u['id'] == $r['user_id']) $studentName = $u['name'];
                    echo $studentName;
                    ?>
                </td>
                <td>
                    <strong style="color: <?= ($r['score'] / $r['max_score'] < 0.5) ? 'red' : 'green' ?>">
                        <?= $r['score'] ?> / <?= $r['max_score'] ?>
                    </strong>
                </td>
                <td><?= $r['date'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require 'views/layout/footer.php'; ?>