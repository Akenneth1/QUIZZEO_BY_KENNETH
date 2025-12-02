<?php require 'views/layout/header.php'; ?>
<h1>Création de Quiz (École)</h1>
<?php if (isset($error)) echo "<div class='alert'>$error</div>"; ?>

<form method="post" action="index.php?page=ecole&action=store">
    <label>Titre du Quiz:</label>
    <input type="text" name="title" required style="width: 100%; margin-bottom: 20px;">

    <div id="questions-container">
    </div>

    <button type="button" onclick="addQuestion()" class="btn" style="background: #28a745;">+ Ajouter une question</button>
    <br><br>
    <button type="submit" class="btn">Sauvegarder le Quiz</button>
</form>

<script>
    let qIndex = 0;

    function addQuestion() {
        const html = `
    <div style="border:1px solid #ddd; padding:15px; margin-bottom:10px; background:#fff;">
        <label>Question :</label>
        <input type="text" name="questions[${qIndex}]" required style="width:80%">
        <br><br>
        <label>Options (séparées par des virgules) :</label>
        <input type="text" name="options[${qIndex}]" placeholder="Rouge, Bleu, Vert" required style="width:80%">
        <br><br>
        <label>Numéro de la bonne réponse (1, 2 ou 3...) :</label>
        <input type="number" name="correct[${qIndex}]" value="1" min="1" style="width:50px">
        &nbsp;&nbsp;
        <label>Points :</label>
        <input type="number" name="points[${qIndex}]" value="1" min="1" style="width:50px">
    </div>`;
        document.getElementById('questions-container').insertAdjacentHTML('beforeend', html);
        qIndex++;
    }
    // Ajouter une question par défaut au chargement
    addQuestion();
</script>
<?php require 'views/layout/footer.php'; ?>