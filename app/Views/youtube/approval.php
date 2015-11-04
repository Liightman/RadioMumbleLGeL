<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 02/11/2015
 * @Time    : 12:36
 * @File    : approval.php
 * @Version : 1.0
 */

$i= 0;

foreach($approval as $hit){ $i++; }
?>

<div class="alert alert-info">
    Voici la liste des morceaux en attente. Veuillez valider une musique uniquement si elle respecte les règles.
</div>

<h2><b><?= $i ?></b> demandes d'approbation.</h2>
<table class="table table-striped table-hover"  style="background-color:white">
    <thead>
    <tr>
        <th align="center" width="30%"><center>Chaine Youtube</center></th>
        <th width="35%"><center>Titre</center></th>
        <th width="20%"><center>Lien Youtube</center></th>
        <th width="30%"><center>Proposée par</center></th>
        <th width="20%"><center>Action</center></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 0;
    foreach($approval as $hit): ?>
        <tr id="row_<?= $hit->videoID ?>">
            <th style="padding-top:15px;" width="30%"><center><?= $hit->author ?></center></th>
            <th style="padding-top:15px;" width="35%"><center><?= $hit->title ?></center></th>
            <th style="padding-top:15px;" width="20%"><center><a target="_blank" href="https://youtube.com/watch?v=<?= $hit->videoID ?>">/watch?v=<?= $hit->videoID ?></a></center></th>
            <th style="padding-top:15px;" width="30%"><center><?= $hit->applicant ?></center></th>
            <th width="20%"><center><button class="btn btn-success"  onclick="acceptApproval('<?= $hit->videoID ?>')">Valider</button><button class="btn btn-danger" onclick="ban('display_ban', '<?= $hit->applicant ?>', '<?= $hit->applicant_ip ?>', '<?= $hit->title ?>', '<?= $hit->videoID ?>')">Supprimer</button></center></th>
        </tr>
        <?php $i++;
    endforeach; ?>
    </tbody>
</table>

