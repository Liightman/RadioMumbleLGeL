<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 19/07/2015
 * @Time    : 13:58
 * @File    : manage.php
 * @Version : 1.0
 */

if($uploadAvatarError): ?>
    <script>
        notif({
            msg: "<b>Erreur:</b> Votre avatar ne respecte pas les conditions d'upload. Vérifiez qu'il ne soit pas plus grand que 1MB, qu'il soit au " +
            "format PNG,GIF,JPG ou JPEG.",
            width: 600,
            multiline: 1,
            timeout: 15000,
            type: "error"
        });
    </script>
<?php endif; ?>
    <div class="panel panel-default panel-article panel-block">
        <div class="row">
            <div class="col-xs-12">
                <div class="col-sm-4" style="padding:15px">
                    <table class="col-sm-12 ">
                        <tr>
                            <td id="monAvatar" style="padding: 10px 20px;">
                                <form method="post" enctype="multipart/form-data">
                                    <img style="margin-left: 20px;" id="uploadPreview1"  class="avatar-md round-corner media-object inline-block" src="<?= $_SESSION['avatar'] ?>">
                                    <button href="#import-img" type="button" class="btn btn-warning bout-md-width no-corner  btn-create-import-image">Choisssez un avatar<input id="uploadImage1" type="file" name="avatar" onchange="PreviewImage(1);" /></button>
                                    <br><br>
                                    <button type="submit" class="btn btn-success">Sauvegarder</button>
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-8" style="padding:15px">
                    <div class="col-sm-12">
                        <div id="identite">
                            <label class="label-edit-profil">Pseudo :</label>
                                    <span class="bold"><?= $info->pseudo ?>
                                        <button onclick="updateProfile('identite')" class="btn btn-primary pull-right" style="display:inline-block; padding:1px 12px">Modifier</button>
                                    </span>
                        </div><hr>
                        <div id="mail">
                            <label class="label-edit-profil">E-mail :</label>
                                    <span class="bold">Cette information est privée.
                                        <button onclick="updateProfile('mail')" class="btn btn-primary pull-right" style="display:inline-block; padding:1px 12px">Modifier</button>
                                    </span>
                        </div><hr>
                        <div id="password">
                            <label class="label-edit-profil">Mot de passe:</label>
                                    <span class="bold">Changé il y a
                                        <button onclick="updateProfile('password')" class="btn btn-primary pull-right" style="display:inline-block; padding:1px 12px">Modifier</button>
                                    </span>
                        </div><hr>
                        <label class="label-edit-profil">Langue :</label><span class="bold"><span title="Seul langue disponible pour le moment">Français*</span></span><br><hr>
                        <br /><br /><br /><br />
                        <hr>
                        <input type="submit" class="btn-primary pull-right btn btn-md" value="Sauvegarder" />
                        <input type="submit" class="btn-danger pull-left btn btn-md" value="Supprimer mon compte" />
                    </div>
                </div>
            </div>
        </div>

    <script type="text/javascript">
        function PreviewImage(no) {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("uploadImage"+no).files[0]);
            oFReader.onload = function (oFREvent) {
                document.getElementById("uploadPreview"+no).src = oFREvent.target.result;
            };
        }
    </script>
