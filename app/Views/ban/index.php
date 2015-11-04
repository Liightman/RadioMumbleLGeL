<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 30/10/2015
 * @Time    : 13:31
 * @File    : index.php
 * @Version : 1.0
 */

if($info){ ?>

    <?php switch ($info->motif):
    case "Pseudo":
        $infoSupp = "Toute usurpation de pseudo et/ou non-renseignement d'un pseudo existant sur LGeL est interdit(e)";
        $tpsBan = "24 heures";
        break;
    case "Hors-Sujet":
        $infoSupp = "Ne faire des demandes que pour des musiques. Toutes vidéos n'ayant aucun rapport avec le domaine musicale sont à proscrire.";
        $tpsBan = "1 heure";
        break;
    case "Contenu de la musique":
        $infoSupp = "Toute musique qui porte sur l'incitation aux crimes de guerre, à la violence sont totalement prohibées.";
        $tpsBan = "10 jours";
        break;
    case "Style de la musique":
        $infoSupp = "Tout les styles de musique sont acceptés, sous couvert de ne pas choquer les jeunes utilisateurs (pornstep = interdit)";
        $tpsBan = "2 jours";
        break;
    case "Durée du remix":
        $infoSupp = "Les remix de plus de 3h sont interdits.";
        $tpsBan = "3 heures";
        break;

endswitch; ?>
    <div class="alert alert-danger">
        <h1>Vous êtes temporairement banni. Il vous ai donc impossible de demander des musiques.</h1>
        <h2><b>Motif : <?= $info->motif ?></b> (sanction appliquée concernant votre demande pour la musique <b> <?= $info->musique ?></b>)</h2><br>
        <h3>Selon le règlement (que vous avez accepté en utilisant ce service et que vous êtes censé avoir lu) :</h3>
        <h4><?= $infoSupp ?></h4>
        <h2>Cette sanction dure <b><?= $tpsBan ?></b></h2>

    </div>
<?php } ?>
