<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 28/10/2015
 * @Time    : 11:45
 * @File    : all.php
 * @Version : 1.0
 */

App::getInstance()->menuActive = "7";
?>
<table class="table table-striped table-hover" style="background-color:white">
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
    foreach($list as $hit): ?>
        <tr id="row_<?= $hit->videoID ?>">
            <th style="padding-top:15px;" width="30%"><center><?= $hit->author ?></center></th>
            <th style="padding-top:15px;" width="35%"><center><?= $hit->title ?></center></th>
            <th style="padding-top:15px;" width="20%"><center><a target="_blank" href="https://youtube.com/watch?v=<?= $hit->videoID ?>">/watch?v=<?= $hit->videoID ?></a></center></th>
            <th style="padding-top:15px;" width="30%"><center><?= $hit->applicant ?></center></th>
            <th width="20%"><center><a data-toggle="modal" data-target="#report<?= $i ?>"><button class="btn btn-danger">Signaler</button></a></center></th>
        </tr>
        <div style="word-wrap: break-word;" class="modal fade" id="report<?= $i ?>" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!--haut-->
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <center><h4 class="modal-title">Signaler une musique</h4></center>
                    </div>
                    <!-- milieu -->
                    <div class="modal-body <?= $hit->videoID ?>">
                        <div class="alert alert-warning">
                            Si une musique ne respecte pas le règlement d'utilisation de ce service, alors indiquez-le nous via ce formulaire.<br><br>
                            Les raisons valables pour signaler une musique sont les suivantes : <br><br>
                            <ul class="center" style="width: 425px">
                                <li>Doublon : La musique existe déjà dans la playlist</li>
                                <li>Hors-Sujet : Cette demande n'est pas une musique.</li>
                                <li>Durée : Le remix dure plus de 3 heures.</li>
                                <li>Contenu : Le contenue de la musique est limite (incitation à la violence, apologie de crime de guerre etc...)</li>
                                <li>Style : Le style de la musique n'est pas approprié (des musique de Justin Bieber, One Direction ou du pornstep)</li>
                            </ul>
                        </div>
                        <h4 class="text-center center">Signaler <?= $hit->title ?> (musique demandée par <b><?= $hit->applicant ?></b>)</h4>
                        <input type="hidden" name="idToReport" value="<?= $hit->videoID ?>" />
                        <label>Motif</label>
                        <select class="form-control" id="motif_<?= $hit->videoID ?>" >
                            <option selected="" value="na">Choisissez un motif</option>
                            <option value="1">Doublon</option>
                            <option value="2">Hors-Sujet</option>
                            <option value="3">Durée</option>
                            <option value="4">Contenu</option>
                            <option value="5">Style</option>
                        </select><br>
                        <input type="submit" name="report" value="Signaler" class="btn btn-danger" onclick="report('<?= $hit->videoID ?>')" />
                    </div>
                    <!-- bas -->
                    <div class="modal-footer">
                        <?php if(!empty($_SESSION) AND $_SESSION['grade'] == "Adm") { ?>
                            <button onclick="ban('display_ban', '<?= $hit->applicant ?>', '<?= $hit->applicant_ip ?>', '<?= $hit->title ?> ')" class="btn btn-danger">Bannir</button>
                            <button type="button" class="btn btn-danger square-btn-adjust" >Supprimer</button>
                        <?php  }?>
                        <button type="button" class="btn btn-info square-btn-adjust" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <?php $i++;
    endforeach; ?>
    </tbody>
</table>

<nav>
    <ul class="pagination">
        <?php
        for($i=1; $i<=$rowPage; $i++){
            if($i==$pageActuelle){
                echo ' <li class="active">';
            }
            else{
                echo '<li>';
            }
            echo '<a href="?page=youtube.all&p='.$i.'">'.$i.'</a></li> ';
        }
        echo '</p>';
        ?>
    </ul>
</nav>