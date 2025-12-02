<?php require 'views/layout/header.php'; ?>

<h1><?= $quiz['title'] ?></h1>

<form method="post" action="index.php?page=quiz&action=submit">
    <input type="hidden" name="quiz_id" value="<?= $quiz['id'] ?>">

    <?php foreach ($quiz['questions'] as $k => $q): ?>
        <div style="background: white; padding: 20px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ddd;">
            <h3>Question <?= $k + 1 ?>: <?= $q['text'] ?></h3>

            <?php if ($q['type'] === 'qcm'): ?>
                <?php foreach ($q['options'] as $idx => $opt): ?>
                    <div style="margin-bottom: 5px;">
                        <input type="radio" name="answers[<?= $k ?>]" value="<?= $idx ?>" id="q<?= $k ?>o<?= $idx ?>">
                        <label for="q<?= $k ?>o<?= $idx ?>"><?= $opt ?></label>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <textarea name="answers[<?= $k ?>]" rows="3" style="width: 100%;"></textarea>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <button type="submit" class="btn" style="font-size: 1.2rem;">Envoyer mes réponses</button>
</form>

<?php require 'views/layout/footer.php'; ?>