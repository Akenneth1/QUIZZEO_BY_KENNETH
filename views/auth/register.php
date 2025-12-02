<?php require 'views/layout/header.php'; ?>
<div style="max-width: 400px; margin: auto;">
    <h2>Créer un compte</h2>
    <?php if (isset($error)) echo "<div class='alert'>$error</div>"; ?>

    <form method="post" action="index.php?page=auth&action=store">
        <label>Nom complet</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Mot de passe</label>
        <input type="password" name="password" required>

        <label>Vous êtes :</label>
        <select name="role" required style="width: 100%; padding: 10px; margin-bottom: 15px;">
            <option value="user">Utilisateur (Je veux répondre aux quiz)</option>
            <option value="ecole">École (Je veux créer des quiz notés)</option>
            <option value="entreprise">Entreprise (Je veux créer des sondages)</option>
        </select>

        <?php $n1 = rand(1, 5);
        $n2 = rand(1, 5); ?>
        <label>Sécurité : Combien font <?= $n1 ?> + <?= $n2 ?> ?</label>
        <input type="number" name="captcha" required style="width: 60px;">
        <input type="hidden" name="captcha_expected" value="<?= $n1 + $n2 ?>">

        <button type="submit" class="btn">S'inscrire</button>
    </form>
    <p>Déjà un compte ? <a href="index.php?page=auth&action=login">Se connecter</a></p>
</div>
<?php require 'views/layout/footer.php'; ?>