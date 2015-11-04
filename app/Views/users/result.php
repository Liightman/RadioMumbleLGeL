<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 17/07/2015
 * @Time    : 18:38
 * @File    : result.php
 * @Version : 1.0
 */

if ($successRegister) : ?>

    <div class="alert alert-success">
       <h1>
           Votre inscription sur <?= $website ; ?> a été validée aves succès!
       </h1>
        <h3>Bienvenue parmis nous!</h3>
    </div>


<?php endif;

if ($errorRegister) : ?>

    <div class="alert alert-danger">
       <h1>
           Une erreur s'est produite lors de votre inscription sur <?= $website ; ?>
       </h1>
        <h3>Navré de ce désagrément rencontré... Veuillez réessayer de vous inscrire.</h3>
    </div>


<?php endif;

if ($success) : ?>

    <div class="alert alert-success">
       <h1>
          Vos informations ont correctement étaient actualisées.
       </h1>
    </div>


<?php endif;

if ($error) : ?>

    <div class="alert alert-danger">
       <h1>
           Une erreur s'est produite lors de l'actualisation de vos informations.
       </h1>
        <h3>Navré de ce désagrément rencontré... Veuillez retenter l'opération.</h3>
    </div>


<?php endif; ?>