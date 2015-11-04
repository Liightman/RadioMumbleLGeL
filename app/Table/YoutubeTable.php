<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 25/10/2015
 * @Time    : 25:54
 * @File    : YoutubeTable.php
 * @Version : 1.0
 */

namespace App\Table;
use \Core\Table\Table;


class YoutubeTable extends Table {

    protected $table = "youtube";

    public function getAll(){
        return $this->query("SELECT * FROM youtube WHERE reported = 'N' AND deleted = 'N' AND accepted = 'Y'", null, true, false);
    }

    public function getList($premiereEntree,$musicPerPage){
        return $this->query("SELECT * FROM youtube  WHERE reported = 'N' AND deleted = 'N' AND accepted = 'Y' ORDER BY id DESC LIMIT {$premiereEntree}, {$musicPerPage}");
    }

    public function checkIfAlreadyAsked($data){
        return $this->query("SELECT * FROM youtube WHERE videoID = '{$data}'");
    }

    public function chooseRandomMusic(){
        if($this->query("SELECT * FROM youtube WHERE inListening='Y'", null, true, false) == 1){
            return $this->query("SELECT * FROM youtube WHERE inListening='Y'");
        } else {
            if((date('H', time()) == 23) OR (date('H', time()) == 00 ) OR (date('H', time()) == 01 ) OR (date('H', time()) == 02 ) OR (date('H', time()) == 03 ) OR (date('H', time()) == 04 ) OR (date('H', time()) == 05 ) OR (date('H', time()) == 06 ) OR (date('H', time()) == 07 )){
                return $this->query("SELECT * FROM youtube WHERE alreadyEar = 'N' AND reported = 'N' AND deleted = 'N' AND forNight = 'Y' AND accepted='Y' ORDER BY RAND() DESC LIMIT 0,1");
            } else {
                return $this->query("SELECT * FROM youtube WHERE alreadyEar = 'N' AND reported = 'N' AND deleted = 'N' AND forNight = 'N' AND accepted='Y' ORDER BY RAND() DESC LIMIT 0,1");
            }

        }
    }

    public function checkIfMatch($string) {
        return $this->query("SELECT * FROM youtube WHERE title LIKE '%{$string}%' ORDER BY title");
    }

    public function inListening($videoID){
        return $this->query("UPDATE youtube SET inListening='Y' WHERE videoID = '{$videoID}'");
    }

    public function rowAskedMusic(){
        return $this->query("SELECT * FROM youtube WHERE applicant='{$_SESSION['pseudo']}' AND reported = 'N' AND deleted = 'N' AND accepted='Y' ORDER BY id DESC", null, true, false);
    }

    public function getAskedMusic(){
        return $this->query("SELECT * FROM youtube WHERE applicant='{$_SESSION['pseudo']}' AND reported = 'N' AND deleted = 'N' AND accepted='Y' ORDER BY id DESC");
    }

    public function queryOwner($videoID){
        if($this->query("SELECT * FROM youtube WHERE videoID = '{$videoID}' AND applicant = '{$_SESSION['pseudo']}' AND reported = 'N' AND deleted = 'N'")){
            $this->deleteAskedMusic($videoID);
        } else {
            return false;
        }
    }

    public function deleteAskedMusic($id){
        return $this->query("DELETE FROM youtube WHERE videoID = ?", [$id], false, true);
    }

    public function getCurrentPlaylist(){
        if((date('H', time()) == 23) OR (date('H', time()) == 00 ) OR (date('H', time()) == 01 ) OR (date('H', time()) == 02 ) OR (date('H', time()) == 03 ) OR (date('H', time()) == 04 ) OR (date('H', time()) == 05 ) OR (date('H', time()) == 06 ) OR (date('H', time()) == 07 )){
            return $this->query("SELECT * FROM youtube WHERE alreadyEar = 'N' AND reported = 'N' AND deleted = 'N' AND forNight = 'Y' AND accepted='Y' ORDER BY id DESC");
        } else {
            return $this->query("SELECT * FROM youtube WHERE alreadyEar = 'N' AND reported = 'N' AND deleted = 'N' AND forNight = 'N' AND accepted='Y' ORDER BY id DESC");
        }

    }

    public function getApproval(){
        return $this->query("SELECT * FROM youtube WHERE accepted='N' AND deleted = 'N' ORDER BY id DESC");
    }

    public function getReports(){
        return $this->query("SELECT * FROM youtube WHERE reported = 'Y' AND deleted = 'N' ORDER BY id DESC");
    }
}
