<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 16/07/2015
 * @Time    : 10:29
 * @File    : default.php
 * @Version : 1.0
 */

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">

        <title><?= App::getInstance()->title ?></title>

        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="http://allodssaob.cluster011.ovh.net/public/css/notification.css" type="text/css" rel="stylesheet">
        <link href="http://allodssaob.cluster011.ovh.net/public/css/UserInterface.css" type="text/css" rel="stylesheet">
        <link href="http://allodssaob.cluster011.ovh.net/public/css/defaultTemplate.css" type="text/css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Chewy' rel='stylesheet' type='text/css'>

        <script src="http://allodssaob.cluster011.ovh.net/public/js/versionJQuery.js"></script>
        <script src="http://allodssaob.cluster011.ovh.net/public/js/UserInterface.js"></script>
        <script src="http://allodssaob.cluster011.ovh.net/public/js/JQuery.js"></script>
        <script src="http://allodssaob.cluster011.ovh.net/public/js/notification.js" type="text/javascript"></script>

<!--        <link href="http://getbootstrap.com/examples/carousel/carousel.css" rel="stylesheet">-->
    </head>

    <!-- NAVBAR
    ================================================== -->
    <body>
        <div id="global">
            <div id="globalContainer">
                <div class="navbar-wrapper">
                    <div class="container">
                        <nav class="navbar navbar-inverse navbar-static-top">
                            <div class="container">
                                <div class="navbar-header">
                                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                    <a class="navbar-brand" href="?page=index"><?= App::getInstance()->name ?></a>
                                </div>
                                <div id="navbar" class="navbar-collapse collapse">
                                    <ul class="nav navbar-nav">
                                        <?php if(empty($_SESSION)): ?>
                                            <li <?php if (App::getInstance()->menuActive === "3"){ ?>class="active" <?php } ?>><a href="?page=users.register">Inscription</a></li>
                                        <?php endif; ?>
                                        <li <?php if (App::getInstance()->menuActive === "1"){ ?>class="active" <?php } ?>><a href="/">Accueil</a></li>
                                        <li <?php if (App::getInstance()->menuActive === "6"){ ?>class="active" <?php } ?>><a href="?page=youtube.playlist">Playlist en cours</a></li>
                                        <li <?php if (App::getInstance()->menuActive === "7"){ ?>class="active" <?php } ?>><a href="?page=youtube.all">Toutes les musiques</a></li>

                                        <li <?php if (App::getInstance()->menuActive === "4"){ ?>class="active" <?php } ?>><a href="?page=youtube.ask">Proposer une musique</a></li>
                                        <?php if($_SESSION): ?>
                                            <li <?php if (App::getInstance()->menuActive === "5"){ ?>class="active" <?php } ?>><a href="?page=youtube.queries">Historique des demandes</a></li>
                                        <?php endif; ?>

                                    </ul>
                                    <?php if(!empty($_SESSION)): ?>
                                        <ul class="nav navbar-nav navbar-right">
                                            <li class="dropdow">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= $_SESSION['pseudo']; ?>
                                                    <?php if(($_SESSION['grade']=="Adm") OR ($_SESSION['grade']=="Mod")):
                                                        echo getAllNotifs();
                                                        endif; ?>
                                                    <span class="caret"></span></a>
                                                <ul class="dropdown-menu">
                                                    <?php if($_SESSION['grade'] == "Adm"): ?>
                                                        <li><a href="?page=youtube.dj">Page DJ</a></li>
                                                        <li><a href="?page=youtube.reports">Voir les signalements <?= $reports = getReports() ?></a></li>
                                                        <li><a href="?page=youtube.approval">Musique en attente d'approbation <?= $approval = getApproval() ?></a></li>
                                                    <?php  endif;
                                                    if($_SESSION['grade'] == "Mod"): ?>
                                                    <li><a href="?page=youtube.reports">Voir les signalements <?= $reports = getReports() ?></a></li>
                                                    <li><a href="?page=youtube.approval">Musique en attente d'approbation <?= $approval = getApproval() ?></a></li>
                                                    <?php endif;    ?>
                                                    <li><a href="?page=users.manage">Mon compte</a></li>
                                                    <li role="separator" class="divider"></li>
                                                    <li><a href="?page=users.logout">Déconnexion</a>
                                                </ul>
                                            </li>
                                        </ul>
                                    <?php endif;
                                    if(empty($_SESSION)):
                                        include_once 'connexionTemplate.php';
                                    endif; ?>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>

                <div id="page-content-wrapper">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="container-fluid">
                                <div class="row" style="margin-top:100px">
                                    <?php include_once 'homeTemplate.php'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="center text-center">
                <p class="pull-right"><a href="#">Remonter la page</a></p>
                &copy; 2015 <a href="/">Radio Mumble</a> <strong><small>(Version 1.2.1)</small></strong>, Tous droits réservés. <a href="#" data-toggle="modal" data-target="#CGU">Mentions légales</a><br />
                <?= App::getInstance()->getAuthor() ?><br>
                Remerciements : Emmoragie pour l'idée design et le background. Et toute la communauté qui m'a soutenu pour ce projet!
            </footer>
        </div>


        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
<!--        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    </body>
</html>
