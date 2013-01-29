<div id="content_login">
    <form action="" method="post">
        <p><label for="login">identifiant : </label><input type="text" name="login" id="login" /></p>
        <p><label for="password">mot de passe : </label><input type="password" name="password" id="password" /></p>
        <p><input type="submit" name="login_btn" id="login_btn" value="> se connecter" class="button"/></p>
    </form>
    <?php if(!empty($_POST['login'])){ ?>
    <p class="alerte">Vous n'avez pas accès à cette page<br />ou vous vous êtes trompé d'identifiant/mot de passe !</p>
    <?php } ?>
</div>