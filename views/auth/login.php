<?php require 'views/layout/header.php'; ?>
<h2>Connexion</h2>
<?php if (isset($error)) echo "<div class='alert'>$error</div>"; ?>
<?php if (isset($msg)) echo "<div class='alert' style='background:#d4edda;color:#155724'>$msg</div>"; ?>

<form method="post" action="index.php?page=auth&action=check">
    <div style="margin-bottom: 10px;">
        <label>Email:</label><br>
        <input type="email" name="email" required style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 10px;">
        <label>Mot de passe:</label><br>
        <input type="password" name="password" required style="width: 100%; padding: 8px;">
    </div>
    <button type="submit" class="btn">Se connecter</button>
</form>
<?php require 'views/layout/footer.php'; ?>