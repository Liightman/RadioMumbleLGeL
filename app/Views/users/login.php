<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 17/07/2015
 * @Time    : 12:33
 * @File    : login.php
 * @Version : 2.0
 */

App::getInstance()->menuActive = "2";
if($errors):
?>
    <script>
        notif({
            msg: "<b>Erreur:</b> Les identifiants que vous avez saisi sont incorrects.",
            width: 600,
            timeout: 5000,
            type: "error"
        });
    </script>
<?php endif; ?>
<form method="post" autocomplete="off" >
    <?= $form->input('username', 'Pseudo'); ?>
    <?= $form->input('password', 'Mot de Passe', ['type' => 'Password']); ?>
    <?= $form->submit('Valider'); ?>
</form>