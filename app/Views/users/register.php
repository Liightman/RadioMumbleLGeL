<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 17/07/2015
 * @Time    : 19:06
 * @File    : register.php
 * @Version : 1.0
 */

App::getInstance()->menuActive = "3";
?>
    <h1 class="page-header">Inscription</h1>

    <?php if($errorDuplicateMail): ?>
        <div class="alert alert-danger">Cette adresse e-mail est déjà utilisée.</div>
    <?php endif;

    if($errorDuplicatePseudo): ?>
        <div class="alert alert-danger">Ce pseudo est déjà utilisé.</div>
    <?php endif;

    if($missingFields): ?>
        <div class="alert alert-danger">Tout les champs n'ont pas étaient rempli.</div>
    <?php endif;

    if($passDoesntMatch): ?>
        <div class="alert alert-danger">Les deux mots de passe sont différents.</div>
    <?php endif; ?>

    <form method="post" autocomplete="off">
        <?= $form->input('pseudo', 'Pseudo', ['class' => 'verifPseudo',  'onkeypress' => 'registrationVerify("pseudo")',  'onkeyup' => 'registrationVerify("pseudo")']); ?>
        <?= $form->input('mail', 'E-mail', ['class' => 'verifMail',  'onkeypress' => 'registrationVerify("mail")',  'onkeyup' => 'registrationVerify("mail")']); ?>
        <?= $form->input('password', 'Mot de Passe', ['type' => 'password', 'class' => 'verifPass',  'onkeypress' => 'registrationVerify("pass")',  'onkeyup' => 'registrationVerify("pass")']); ?>
        <?= $form->input('retypePassword', 'Confirmation', ['type' => 'password', 'class' => 'verifRetypePassword',  'onkeypress' => 'registrationVerify("retypePassword")',  'onkeyup' => 'registrationVerify("retypePassword")']); ?>

        <?= $form->submit('Valider'); ?>
    </form>
