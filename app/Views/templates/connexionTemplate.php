<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 26/07/2015
 * @Time    : 17:24
 * @File    : connexionTemplate.php
 * @Version : 1.0
 */
?>

<ul class="nav navbar-nav navbar-right">
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Se connecter</b> <span class="caret"></span></a>
        <ul id="login-dp" class="dropdown-menu">
            <li>
                <div class="row">
                    <div class="col-md-12">
                        Se connecter
                        <form class="form" role="form" method="post" action="?page=users.loginInstant" accept-charset="UTF-8" id="login-nav">
                            <div class="form-group">
                                <label class="sr-only">Pseudo</label>
                                <input type="text" class="form-control" name="pseudo" placeholder="Votre Pseudo" required>
                            </div>
                            <div class="form-group">
                                <label class="sr-only">Mot de Passe</label>
                                <input type="password" class="form-control" name="password" placeholder="Mot de passe" required>
                                <div class="help-block text-right"><a href="">Vous avez oublier votre mot de passe?</a></div>
                            </div>
                                <div class="form-group">
                                    <button type="submit" href="?page=users.loginInstant"  class="btn btn-primary btn-block">Connexion</button>
                                </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"> Se souvenir de moi
                                </label>
                            </div>
                        </form>
                    </div>
                    <div class="bottom text-center">
                        Nouveau sur <?= App::getInstance()->name ?> ? <a href="?page=users.register"><b>Rejoignez nous!</b></a>
                    </div>
                </div>
            </li>
        </ul>
    </li>
</ul>
