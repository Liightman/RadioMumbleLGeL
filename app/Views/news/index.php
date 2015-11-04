<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 16/07/2015
 * @Time    : 10:24
 * @File    : index.php
 * @Version : 1.0
 */

App::getInstance()->menuActive = "1";
foreach ($news as $new) :  ?>
    <div class="col-lg-4">
        <img class="img-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Image d'article" width="140" height="140">
        <h2><?= $new->titre; ?></h2>
        <p><?= $new->extrait; ?></p>

    </div>
<?php
endforeach;
?>


<h2 class="text-center">En ce moment en écoute : </h2>
<div class="center text-center actualMusic">
    <h3>CHARGEMENT...</h3>
    <div class="background"></div>
    <div class="info"></div>
</div>