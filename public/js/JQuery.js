/**
 * 	@Author Created by Llyam Garcia (Liightman) on 19/07/2015.
 * 	@File JQuery.js
 * 	@version 1.4
 */

    /**
     *	@Desc Déclarations des variables
     */
    var urlInteragir = "app/ajax/JQuery.php";

/**
 * @Desc Les functions
 */

    function actualListeningMusic(){
        $.post(urlInteragir,{"action": "inListening"}, function(data) {
            if (data != "error"){
                $(".actualMusic").empty().append('<div class="background"></div><div class="info"></div>');
                $(".background").append('<img src="'+data.thumbnails+'" alt="background image youtube" />');
                if(data.videoID != "undefined"){
                    if(data.staff === "yes"){
                        var applicant_ip = "("+data.applicant_ip+")";
                        var ban = '<button onclick="ban(\'display_ban\', \''+data.applicant+'\', \''+data.applicant_ip+'\', \''+data.title+'\')" class="btn btn-danger">Bannir</button>';
                    } else {
                        var applicant_ip = "";
                        var ban = '<a href="?page=youtube.playlist#row_'+data.videoID+'"><button class="btn btn-danger">Signaler</button></a>';

                    }
                    $(".info").append(data.forNight+'<h3>'+data.title+'</h3> <h4>Proposée par <b><span class="player">'+data.applicant+'</span> '+applicant_ip+'</b></h4>');
                    $('.info').append(ban);
                    $(".info").append('<div class="center text-center" style="margin-top: 20px;text-align:center;background-color:white; width:250px; border-radius: 15px"><br>' +
                        '<button onclick="Vote(\'pour\', \''+data.videoID+'\')" class="btn btn-success"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span></button> &nbsp; <button onclick="Vote(\'contre\', \''+data.videoID+'\')" class="btn btn-danger"><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span></button><br><br>' +
                        '<span style="color:green"><b>'+data.pour+'</b> votes pour</span> - <span style="color:red"><b>'+data.contre+'</b> votes contre</span></div> ');
                }

            }
        }, 'json');
    }

    function reloadPlayer(oldMusicID) {
        clearTimeout(timer);
        $.post(urlInteragir, {"action": "musicEard", "videoID": oldMusicID}, function (data) {
        });
        $.post(urlInteragir, {"action": "randomNewSong"}, function (data) {
            if (data != "error") {
                $(".applicant").empty().append("Musique proposée par "+data.applicant+" ("+data.applicant_ip+")");
                $(".videoyt").empty().append("<iframe width=\"420\" height=\"315\" src=\"https://www.youtube.com/embed/" + data.videoID + "?autoplay=1\" frameborder=\"0\" allowfullscreen></iframe><h4 style=\"cursor:pointer\" onclick=\"reloadPlayer('"+data.videoID +"')\">Musique Suivante</h4>");
            }
            timer = setTimeout(function () {
                reloadPlayer('' + data.videoID + '');
            }, data.duration);
        }, 'json');

    }

    function playMusic(){
        clearTimeout(timer);
        var $askedVideo = $(".ytID").val();

        $.post(urlInteragir, {"action": "play", "video": $askedVideo}, function (data) {
            if (data != "error") {
                $(".applicant").empty().append("Musique proposée par "+data.applicant+" ("+data.applicant_ip+")");
                $(".videoyt").empty().append("<iframe width=\"420\" height=\"315\" src=\"https://www.youtube.com/embed/" + data.videoID + "?autoplay=1\" frameborder=\"0\" allowfullscreen></iframe><h4 style=\"cursor:pointer\" onclick=\"reloadPlayer('"+data.videoID +"')\">Musique Suivante</h4>");
            }
            timer = setTimeout(function () {
                reloadPlayer('' + data.videoID + '');
            }, data.duration);
        }, 'json');
    }

    function report(videoID){
        var motif = $('#motif_'+videoID+' option:selected').text();
        $.post(urlInteragir, {"action": "report", "videoID": videoID, "motif": motif}, function (data) {
            if (data != "error") {
                if(data.fait == "ok"){
                    $('.'+videoID).empty().append('<div class="alert alert-success">Votre signalement a bien été pris en compte! <br>' +
                        'La musique en question a été supprimée temporairement le temps que Liightman examine le signalement.<br>' +
                        'Merci pour votre vigilance!');
                    $('#row_'+videoID).fadeOut( "slow" );
                    notif({
                        msg: "<b>Information:</b> Votre signalement a été enregistré! Merci",
                        width: 600,
                        timeout: 5000,
                        type: "info"
                    });
                } else if (data.fait == "error"){
                    notif({
                        msg: "<b>Erreur:</b> Une erreur est survenue lors de votre signalement.",
                        width: 600,
                        timeout: 5000,
                        type: "error"
                    });
                } else if (data.error == "not_logged"){
                    notif({
                        msg: "<b>Erreur:</b> Vous devez vous connecter pour signaler une musique.",
                        width: 600,
                        timeout: 5000,
                        type: "error"
                    });
                } else if(data.error == "doesn't exist"){
                    notif({
                        msg: "<b>Erreur:</b> Cette musique n'existe pas.",
                        width: 600,
                        timeout: 5000,
                        type: "error"
                    });
                } else if(data.error == "no motif"){
                    notif({
                        msg: "<b>Erreur:</b> Vous n'avez choisi aucun motif.",
                        width: 600,
                        timeout: 5000,
                        type: "error"
                    });
                }
            }
        }, 'json');

    }

    function Vote(vote,videoID){
        $.post(urlInteragir,{"action": "vote", "vote": vote, "videoID": videoID}, function(data) {
            if (data != "error"){
                if(data.fait === "OK"){
                    notif({
                        msg: "<b>Information:</b> Votre vote a bien été pris en compte!",
                        type: "success"
                    });
                }else if (data.fait === "NO"){
                    if(data.because === "ALREADY_VOTE"){
                        notif({
                            msg: "<b>Erreur:</b> Vous avez déjà voté pour cette musique!",
                            type: "error"
                        });
                    }else if(data.because === "NOT_LOGGED"){
                        notif({
                            msg: "<b>Erreur:</b> Vous devez être connecté pour voter!<br> (Ce afin d'évitez les spams vote)",
                            type: "error"
                        });
                    }
                }
            }
        }, 'json');
    }

    function ban(commande, pseudo, ip, title, videoID){

        if (commande == "display_ban") {
            $( "#dialog" ).empty();
            var options_ban = '<input  type="hidden" id="pseudoban" value="'+pseudo+'"/>'+
                'Choisir le motif : <select id="motif">'+
                '<option value="1"> Pseudo </option>'+
                '<option value="2"> Contenu de la musique </option>'+
                '<option value="3"> Style de la musique </option>'+
                '<option value="4"> Durée du remix </option>'+
                '<option value="5"> Hors-Sujet </option>'+
                '<option value="-1"> BANNIR DEFINITIVEMENT </option></select><br><br>';

            //var le_motif = '<br><h4>Votre commentaire à l\'égard de <b>'+pseudo_a_moderer+'</b></h4><textarea id="commentaire" style="margin: 2px; width: 500px; height: 120px; resize: none; overflow: auto;"></textarea>';
            $( "#dialog" ).append('<h4 class="text-center">Bannir '+pseudo+'</h4> Cela entrainera également la suppresion du morceau<br>');
            $( "#dialog" ).append(options_ban+'<span style="border:none;width: 500px; border-radius:0"class="btn btn-danger" onclick="ban(\'submit_ban\', \''+pseudo+'\', \''+ip+'\', \''+title+'\', \''+videoID+'\');"><span class="glyphicon glyphicon-exclamation-sign"></span> Bannir '+pseudo+'</span>');
            $( "#dialog" ).dialog( "option", "position", "center" );
            $( "#dialog" ).dialog( "open");

        }
        // Soumettre un ban
        else if (commande == "submit_ban") {
            if (confirm("Êtes-vous sur de vouloir bannir "+pseudo+" pour le motif choisi ?")) {
                var motif = $('#motif').val();
                //var commentaire = $('#commentaire').val();
                $.post(urlInteragir,
                    {"action": "bannir", "joueur": pseudo, "ip": ip, "motif": motif, "title": title /* "commentaire": commentaire*/},
                    function(data) {
                        if (data != "error") {
                            $('#row_'+videoID).fadeOut( "slow" );
                            $( "#dialog" ).empty();
                            $( "#dialog" ).append('Confirmation : l\'utilisateur a bien été banni pour le motif demandé.');
                            $( "#dialog" ).dialog( "option", "position", "center" );

                        }
                    }, 'json');
            }
        }
    }

    function delReport(videoID){
        if (confirm("Êtes-vous sur de vouloir autoriser cette musique?  ("+videoID+")")) {
            $.post(urlInteragir,
                {"action": "delReport", "videoID": videoID},
                function(data) {
                    if (data != "error") {
                        var newAllNotifs = $('.allNotifs').val() - 1;
                        $('#row_'+videoID).fadeOut( "slow" );
                        $('.allNotifs').empty().append(newAllNotifs);
                        $( "#dialog" ).empty();
                        $( "#dialog" ).append('Confirmation : Ce morceau a été autorisé.');
                        $( "#dialog" ).dialog( "option", "position", "center" );
                    }
                }, 'json');
        }
    }
    function acceptApproval(videoID){
        if (confirm("Êtes-vous sur de vouloir autoriser cette musique?  ("+videoID+")")) {
            $.post(urlInteragir,
                {"action": "acceptApproval", "videoID": videoID},
                function(data) {
                    if (data != "error") {
                        var newAllNotifs = $('.allNotifs').val() - 1;
                        $('#row_'+videoID).fadeOut( "slow" );
                        $('.allNotifs').empty().append(newAllNotifs);
                        $( "#dialog" ).empty();
                        $( "#dialog" ).append('Confirmation : Ce morceau a été autorisé.');
                        $( "#dialog" ).dialog( "option", "position", "center" );
                    }
                }, 'json');
        }
    }


/**
 *  Functions concernant la partie utilisateurs
 */
        /**
         * Function qui permet d'effectuer des recherches
         */
        function research(){
            var term = $("#search").val();
            if(term != "") {
                $.post(urlInteragir,{"action": "search", "term": term}, function(data) {
                    if (data != "error"){
                        if (data != ""){
                            $("#resultSearch").empty().append(data).show();
                        } else {
                            $("#resultSearch").empty().append('<li class="Typeahead">Désolé, aucun résultat ne correspond à votre recherche</li>').show();
                        }
                    }
                }, 'json');
            } else {
                $("#resultSearch").empty().hide();
            }

        }

        /**
         * Fonction pour afficher les formulaires afin de modifier les information de son compte
         */
        function updateProfile(action){
            $.post(urlInteragir,{"action": "getFormulaireToChangeProfile", "askFor": action}, function(data) {
                $("#" + action + "").empty().append(data);
            }, 'json');
        }
        /**
         * Fonction pour  modifier les information de son compte
         */
        function updateInfoProfile(action){
            if (action === "identite") {
                var newInfo = $(".NewPseudo").val();
            } else if (action === "password"){
                var oldInfo = $(".OldPass").val();
                var newInfo = $(".NewPass").val();
                var newInfo2 = $(".confNewPass").val();
            } else  if (action === "mail") {
                var oldInfo = $(".OldMail").val();
                var newInfo = $(".NewMail").val();
            }
            if (newInfo === "") {
                notif({
                    msg: "<b>Erreur:</b> Vous n'avez pas rempli tous les champs nécessaires.",
                    type: "error"
                 });
            } else {
                $.post(urlInteragir,{"action": "updateProfile", "askFor": action, "newInfo": newInfo, "newInfoOption": newInfo2, "oldInfo": oldInfo}, function(data) {
                    if(data!="error"){
                        if(data.erreur === "none"){
                            if(data.fait === "ok"){
                                $("#" + action + "").empty().append("Information mises à jour.");
                                notif({
                                    msg: "<b>Information:</b> Vos informations ont étaient mises à jour",
                                    type: "success"
                                });
                            }
                        } else {
                            notif({
                                msg: "<b>Erreur:</b> "+data.erreur+"",
                                type: "error"
                            });
                        }
                    }
                }, 'json');
            }

        }

/**
 *	Préparation de la page avec Document.ready
 */
$(document).ready(function() {
    setInterval(actualListeningMusic, 2000);

    $('body').prepend("<div id=\"dialog\" title=\"Interaction\"></div>");

    $( "#dialog" ).dialog({
        autoOpen: false,
        show: { effect: 'fade' },
        hide: { effect: 'fade' },
        modal: true,
        width: 650,
        close: function(event, ui) { $('#wrap').show(); },
        open: function(event, ui) { $('.ui-widget-overlay').bind('click', function(){ $("#dialog").dialog('close'); }); }
    });
});