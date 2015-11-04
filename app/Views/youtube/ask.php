<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 26/10/2015
 * @Time    : 19:12
 * @File    : ask.php
 * @Version : 1.0
 */

App::getInstance()->menuActive = "4";
if($error):
    ?>
    <script>
        notif({
            msg: "<b>Erreur:</b> Vous n'avez pas rempli le formulaire!",
            width: 600,
            timeout: 5000,
            type: "error"
        });
    </script>

<?php endif;

if(!empty($addError)){
    if ($addError != "") {?>
    <div class="alert alert-danger">
        <?= $addError ?>
    </div>

<?php }
} else {
    if (isset($videoInfo)):
        if (is_array($videoInfo)) { ?>
            <div class="alert alert-success">
                Votre demande pour écouter <b><?= $videoInfo['title'] ?></b> a bien été prise en compte!<br>
                Elle est en attente d'approbation. Si elle est acceptée, vous pourrez l'écouter très bientôt dans la Radio Mumble!
            </div>
        <?php } else { ?>
            <div class="alert alert-danger">
                <?= $videoInfo ?>
            </div>

        <?php }
    endif;
} ?>

<div class="alert alert-warning center" style="width:450px">
    Tout abus de cet outil entraînera plusieurs restrictions!<br> Utilisez donc cet outil en prenant compte des règles!
</div>

Pour ajouter une musique à la playlist de la Radio Mumble, veuillez nous donner son identifiant.<br>
Pour avoir cette information, selectionnez la suite de caractère à la fin de l'url d'une vidéo YouTube comme ceci <br><br>
<img src="http://i.imgur.com/66muSXd.png" alt="How To Take a Youtube Video ID" /><br><br>

<form method="post" autocomplete="off" class="center" style="width:250px" >
    <?= $form->input('videoID', 'ID de la vidéo'); ?>
    <?php if(empty($_SESSION)): ?>
        <?= $form->input('pseudo', 'Votre Pseudo'); ?>
    <?php endif; ?>
    <small>En utilisant ce service, vous vous soummetez au <a  style="cursor:pointer" data-toggle="modal" data-target="#rules">règlement</a>, ainsi qu'aux <a style="cursor:pointer" data-toggle="modal" data-target="#CGU">Conditions Générales d'Utilisation</a></small>
    <?= $form->submit('Valider'); ?>
</form>

<div style="word-wrap: break-word;" class="modal fade" id="rules" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--haut-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <center><h4 class="modal-title">Règles sur l'utilisation de ce service</h4></center>
            </div>
            <!-- milieu -->
            <div class="modal-body">
                <p>
                    Merci de lire avec attentions les différents points cités ci-dessous avant d'utiliser les services proposés du présent site. En vous connectant sur ce site, vous acceptez sans réserve les présentes modalités.
                    <br /><br />
                <p style="color: #b51a00;"><span style="color: rgb(0, 0, 0);"><b>Utilisation du formulaire de demande de musique : </b></span></p>
                Tout utilisateur voulant faire part d'une demande de musique pour la Radio Mumble doit respecter les contraintes ci-dessous.<br><br>
                    <ul class="center text-center">
                        <li>Renseigner correctement, comme il l'est indiqué, l'identifiant de la vidéo youtube.</li>
                        <li>Ne faire des demandes que pour des musiques. Toutes vidéos n'ayant aucun rapport avec le domaine musicale sont à proscrire.</li>
                        <li>Les remix de plus de 3h sont interdits.</li>
                        <li>Toute musique qui porte sur l'incitation aux crimes de guerre, à la violence sont totalement prohibées.</li>
                        <li>Tout les styles de musique sont acceptés, sous couvert de ne pas choquer les jeunes utilisateurs (pornstep = interdit).</li>
                    </ul>
                </p>
                <h3>Tout contrevenant s'expose à des sanctions, temporaires, ou définitives selon la gravité et la récédive du soit-dit acte.</h3>
            </div>
            <!-- bas -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger square-btn-adjust" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>