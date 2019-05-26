<?php

 
$string = file_get_contents("./db/challenges.json");
$challenges = json_decode($string, true);
$string = file_get_contents("db/flags.json");
$flags = json_decode($string, true);
$string = file_get_contents("db/files.json");
$files = json_decode($string, true);


function getChallengeCount(){
  global $challenges;
  return $challenges['count'];
}

function dumpChallengesNames(){
  global $challenges;
  foreach ($challenges['results'] as $c) {
    print_r($c['id']);
    print("\n");
    print_r($c['name']);
    print("\n");
  }
}


function isFlagValid($id, $flag){
  global $flags;
  foreach ($flags['results'] as $f) {
    if ($f['challenge_id']==$id) {
      //print "Found id";
      $a = trim($f['content']);
      $b = trim($flag);
      //var_dump ($a);
      //var_dump ($b);
      if (strcasecmp($a,$b)==0) {
        return true;
      }
      
    } 
  }  
  
  return false;
}


function getCategories(){
  global $challenges;
  $categories = array();
  foreach ($challenges['results'] as $c) {
    if (!in_array($c['category'], $categories)) {
      $categories[] = $c['category'];
    }
    
  }
  return $categories;
}

function debug() {
  global $challenges;
  global $flags;
  global $files;

  print_r($files);

  print_r($files['count']);
  print_r($files['results'][0]);
  foreach ($files['results'] as $f) {
      print_r($f['id']);
      print("\n");
      print_r($f['location']);
      print("\n");
  }
  print getChallengeCount();
}


function getChallengeFileLocation($challengeId) {
  global $files;

  foreach ($files['results'] as $f) {
    //echo $f['challenge_id']."==".$challengeId."\n";
    if ($f['challenge_id']==$challengeId) {
      //echo "ok";
      return $f['location'];
    }    
  }
  return "";
}

function html_dump_cat($cat) {
  global $challenges;
  global $files;
  global $Parsedown;

  foreach ($challenges['results'] as $c) {
    if ($c['category']==$cat) {
      print '<div class="container">';
        
        // titre
        print '<div class="row chall-titre bg-secondary text-white">';
        print ($c['name']);
        print "</div>";
        // Description
        print '<div class="container chall-desc">';
        $desc = $c['description'];
        $server="";
        if ($cat==="Ghost in the Shell") {$server="ctf-shell"."_".$_SESSION['uid'];}
        if ($cat==="Privilege Escalation") {$server="ctf-escalation"."_".$_SESSION['uid'];}
        if ($cat==="SQLi") {$server="ctf-sqli"."_".$_SESSION['uid'];}
        if ($cat==="Buffer overflows") {$server="ctf-buffer"."_".$_SESSION['uid'];}
        if ($cat==="File Upload") {$server="ctf-transfert"."_".$_SESSION['uid'];}
        if (isset($c['docker'])){
          if (($c['docker'])!="") {
            $server=$c['docker']."_".$_SESSION['uid'];
            $desc = str_replace("IPSERVER", $server, $desc);
          }
        }        
        

        if (isset($_SERVER['HTTP_HOST'])) {
          $desc = str_replace("{IP_SERVER}", $_SERVER['HTTP_HOST'], $desc);
        }
        print $Parsedown->text($desc);
        print "</div>";
        // Files
        foreach ($files['results'] as $f) {
          if ($f['challenge_id']===$c['id']) {
            print '<div class="row chall-desc bg-light">';
            print '
            <a href="downloadfile.php?id='.$f['challenge_id'].'" download>
            <button  class="btn btn-primary">Download '.basename($f['location']).'</button>
            </a>';
            print "</div>";
          }
        }
        // Server
        if (isset($c['docker'])){
          if (($c['docker'])!="") {
            echo $c['docker'];
            ctf_div_server_status($c['docker']);
          }
        }

        // Flag
        print '<div class="row chall-desc bg-light">';
        print '
            <div class="col-md-auto text-left"><label for="usr">Flag:</label></div>
            <div class="col text-left"><input type="text" class="form-control" id="flag_'.$c['id'].'" name="code" onLoad="ctf_onload('.$c['id'].', \'#flag_'.$c['id'].'\')"></div>
            <script>$("#flag_'.$c['id'].'").ready(function(){ ctf_onload('.$c['id'].', \'#flag_'.$c['id'].'\') })</script>
            <div class="col-2 text-right"><button type="Submit" class="btn btn-primary" onclick="ctf_validate('.$c['id'].', \'#flag_'.$c['id'].'\')">Submit</button></div>';
        print "</div>";
        print '<div class="row chall-spacer">  </div>';
      print "</div>";
    }
  }     
}


function ctf_div_server_status($id) {

//echo '</br>';
//echo 'HTTP_CLIENT_IP='.$_SERVER['HTTP_CLIENT_IP'].'</br>';
//echo 'HTTP_X_FORWARDED_FOR='.$_SERVER['HTTP_X_FORWARDED_FOR'].'</br>';  // Ok IP src, placé par traefik
//echo 'REMOTE_ADDR='.$_SERVER['REMOTE_ADDR'].'</br>';
echo 'HTTP_HOST='.$_SERVER['HTTP_HOST'].'</br>';
echo '     
<p>Pour rentrer sur le serveur il faut ouvrir votre terminal dans un nouvel onglet, démarrer votre serveur dédié et vous connecter dessus avec ssh.</p>
</br>
<p id="ServerStatus'.$id.'">Server status : stopped</p>
</br>
<p><button type="button" class="btn btn-default btn-warning" id="StartServer'.$id.'" value="StartServer">Start Server</button>
<button type="button" class="btn btn-default btn-warning" id="StopServer'.$id.'" value="StopServer">Stop Server</button></p>

<script>
$(document).ready(function() {
    $("#StartServer'.$id.'").click(function(){
        $.get("containers_cmd.php?create='.$id.'",function(data) { 
            if (data=="ko") {
                $("#ServerStatus'.$id.'").html("Server status: Problem... Cant start");
            } else  {
               var resp = JSON.parse(data);
               //$("#ServerStatus'.$id.'").html(resp.Name); 
               var txt = "Server status: Running as "+resp.Name;
               if (resp.Port  !== undefined ) { 
                 if (resp.Port  !=0) {
                   txt = txt + "</br>Host="+window.location.host; 
                   txt = txt + "</br>Port="+resp.Port; 
                   //txt = txt + "</br>Accès possible en : ssh user@"+window.location.host+" -p "+resp.Port;
                 }
               }
               $("#ServerStatus'.$id.'").html(txt);
            }
        });

    }); 

});
$(document).ready(function() {
    $("#StopServer'.$id.'").click(function(){
        $.get("/stop/",function(data) { $("#ServerStatus'.$id.'").html(data); });
    }); 
});

</script>
';

}
?>