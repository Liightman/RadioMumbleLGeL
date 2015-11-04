<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 26/10/2015
 * @Time    : 22:13
 * @File    : dj.php
 * @Version : 1.0
 */

$stamp = $music[0]->duration;
$formated_stamp = str_replace(array("PT","H","M","S"), array("",":",":",""),$stamp);

$isHour = stripos($stamp, "H");
if($isHour === false){
    $time = "0:".$formated_stamp;
} else {
    $time = $formated_stamp;
}

$parsed = date_parse($time);
$seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];

/*echo "<pre>";
var_dump($formated_stamp);
var_dump($time);
var_dump(strlen($formated_stamp));
var_dump($parsed);
var_dump($seconds);
echo "</pre>";*/

/*echo $formated_stamp;
echo $seconds;*/
?>
<div style="color:white">
    <h3 class="applicant">Musique proposée par <?= $music[0]->applicant ?> (<?= $music[0]->applicant_ip ?>) </h3>
    <div class="videoyt">
        <iframe width="420" height="315" src="https://www.youtube.com/embed/<?= $music[0]->videoID ?>?autoplay=1" frameborder="0" allowfullscreen></iframe>
        <h4 style="cursor:pointer" onclick="reloadPlayer('<?= $music[0]->videoID ?>')">Musique Suivante</h4>
        <br>
        <input type="submit" name="delete" value="Retirer cette musique de la liste et l'interdire" class="btn btn-danger" />
    </div><br><br>
    Choisir une musique à jouer : <input style="color:black" type="text" class="ytID" /> <button class="btn btn-success" onclick="playMusic()">JOUER</button>
</div>

<script>
    timer = setTimeout(function () {
        reloadPlayer('<?= $music[0]->videoID ?>');
    },  <?= $seconds ?>000);
</script>
