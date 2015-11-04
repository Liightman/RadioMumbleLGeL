<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 27/10/2015
 * @Time    : 11:47
 * @File    : query.php
 * @Version : 1.0
 */

App::getInstance()->menuActive = "5";
?>

<center><h3>Historique des musiques proposées</h3></center>
<?php if($erreur != false) {
    echo $erreur;
} else { ?>
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th align="center" width="40%"><center>Chaine Youtube</center></th>
            <th width="40%"><center>Titre</center></th>
            <th width="40%"><center>Lien Youtube</center></th>
            <th width="20%"><center>Action</center></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($askedHits as $hit): ?>
            <tr>
                <th style="padding-top:15px;" width="40%"><center><?= $hit->author ?></center></th>
                <th style="padding-top:15px;" width="40%"><center><?= $hit->title ?></center></th>
                <th style="padding-top:15px;" width="40%"><center><a target="_blank" href="https://youtube.com/watch?v=<?= $hit->videoID ?>">/watch?v=<?= $hit->videoID ?></a></center></th>
                <th width="20%"><center><form method="post"><input type="hidden" name="idToDelete" value="<?= $hit->videoID ?>" /><input type="submit" name="delete" value="Retirer" class="btn btn-danger" /></form></center></th>
            </tr>

            <?php
        endforeach; ?>
        </tbody>
    </table>
<?php } ?>

