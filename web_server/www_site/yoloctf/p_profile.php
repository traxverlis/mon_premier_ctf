<div class="col text-center">
<div class="col text-left"><h2>Mon Profile</h2><br><br></div>
<div class="col text-center"> 

<!---- UID, login, mail  --->

    <div class="">
      <div class="row chall-titre bg-secondary text-white">
        <div class="col-sm text-left">Identifiant</div>
      </div>
        <div class="form-group text-left row">
		  <label for="usr" class="col-2">Id</label>
		  <label for="usr" class="col-6" id="id" name="id">
              <?php echo isset($_SESSION['uid'])?htmlspecialchars($_SESSION['uid']):"XXXXXX"; ?>
          </label>
          <label for="usr" class="col-2"></label>
        </div>
		<div class="form-group text-left row">
          <label for="usr" class="col-2">Login</label>
          <input type="hidden" id="name_current" name="name_current" value="<?php echo isset($_SESSION['login'])?htmlspecialchars($_SESSION['login']):"Guest"; ?>">
		  <input type="text" class="col-6 form-control" id="name" name="name" value="<?php echo isset($_SESSION['login'])?htmlspecialchars($_SESSION['login']):"Guest"; ?>">
          <label for="usr" class="col-2"></label>
        </div>
        <div class="form-group text-left  row ">
		  <label for="usr" class="col-2">Mail</label>
          <input type="hidden" id="mail_current" name="mail_current" value="<?php echo isset($_SESSION['mail'])?htmlspecialchars($_SESSION['mail']):""; ?>">
		  <input type="text" class="col-6 form-control" id="mail" name="mail" value="<?php echo isset($_SESSION['mail'])?htmlspecialchars($_SESSION['mail']):""; ?>">
          <label for="usr" class="col-2"></label>
        </div>
        
        <div class="form-group text-right row ">
          <label for="usr" class="col-2"></label>
          <button type="submit" class="btn btn-primary" onclick="return onProfileSave()">Save</button>      
        </div> 


    </div>

<div class="form-group text-left  row ">
<hr>
</div>

<!---- status  --->
<div class="">
    <div class="row chall-titre bg-secondary text-white">
        <div class="col-sm text-left">Status du compte</div>
    </div>
    <div class="form-group text-left  row ">
		  <label for="usr" class="col-2">Status</label>
		  <label for="usr" class="col-6" id="status" name="status">
          <?php echo isset($_SESSION['status'])?htmlspecialchars($_SESSION['status']):"XXX"; ?>
      </label>
       <label for="usr" class="col-2"></label>
    </div>
        
      <?php if (isset($_SESSION['status']) and ($_SESSION['status']==='waiting_email_validation')) { ?>
        <div class="form-group text-right row ">
          <label for="usr" class="col-2"></label>
          <button type="submit" class="btn btn-primary" onclick="return onResendValidationMail()">Resend mail</button>      
        </div> 
      <?php } ?>
</div>

<div class="form-group text-left  row ">
<hr>
</div>

<!---- Password  --->
<div class="">
      <div class="row chall-titre bg-secondary text-white">
        <div class="col-sm text-left">Mot de passe</div>
      </div>
        <div class="form-group text-left  row ">
		  <label for="usr" class="col-2">Password</label>
		  <input type="password" class="col-6 form-control" id="password" name="password">
          <label for="usr" class="col-2"></label>
        </div>

        <div class="form-group text-right row ">
          <label for="usr" class="col-2"></label>
          <button type="submit" class="btn btn-primary" onclick="return onProfilePasswordChange()">Change</button>      
        </div> 
</div>

<div class="form-group text-left  row ">
<hr>
</div>

<!---- Join CTF  --->
<div class="">
      <div class="row chall-titre bg-secondary text-white">
        <div class="col-sm text-left">Rejoindre un CTF</div>
      </div>
        <div class="form-group text-left  row ">
		  <label for="usr" class="col-2">CTF</label>
		  <input type="text" class="col-6 form-control" id="ctf" name="ctf" value="<?php echo isset($_SESSION['ctf'])?htmlspecialchars($_SESSION['ctf']):""; ?>">
          <label for="usr" class="col-2"></label>
        </div>
       
        <div class="form-group text-right row ">
          <label for="usr" class="col-2"></label>
          <button type="submit" class="btn btn-primary" onclick="return onJoinCTF()">Join CTF</button>      
        </div> 

    
</div>

<div class="form-group text-left  row ">
<hr>
</div>

<!---- Organiser un CTF  --->
<div class="">
      <div class="row chall-titre bg-secondary text-white"><div class="col-sm text-left">Créer un CTF</div></div>

      <div class="form-group text-left  row ">
      <?php   
      require_once('ctf_sql.php');
      $request = "SELECT * FROM ctfs WHERE UIDADMIN='$uid'";
      $result = $mysqli->query($request);
      $count  = $result->num_rows;
      if($count>0) {
          $row = $result->fetch_array();
          // id , creation_date datetime, UIDCTF VARCHAR(45) NULL, ctfname VARCHAR(200) NULL, UIDADMIN 
          $ctfname =  $row['ctfname'];
          $creation_date =  $row['creation_date'];
          $d = DateTime ($creation_date);
          echo "<label for='usr' class='col-2'>$ctfname</label>";
          echo "<label for='usr' class='col-2'>$creation_date</label>";
      }
      ?>
      </div>
      <div class="form-group text-left  row ">
		    <label for="usr" class="col-2">CTF</label>
		    <input type="text" class="col-6 form-control" id="createctf_name" name="createctf_name" value="<?php echo $ctfname; ?>">
        <label for="usr" class="col-2"></label>
      </div>
       
      <div class="form-group text-right row ">
        <label for="usr" class="col-2"></label>
        <button type="submit" class="btn btn-primary" onclick="return onCreateCTF()">Créer CTF</button>      
      </div> 

</div>

<div class="form-group text-left  row ">
<hr>
</div>

<script>
        function onProfileSave()
        {
            // Name
            var currentlogin_raw = $("#name_current").val();
            var newlogin_raw = $("#name").val();
            if (currentlogin_raw != newlogin_raw) {
                var newlogin = encodeURIComponent(newlogin_raw); 
                $.get( "cmd_ctf.php?setLogin="+newlogin, function( data, status ) {
                    $("#name_current").val(newlogin_raw);  
                    var ret = $.parseJSON(data);
                    alert(ret.message); 
                   
                })
                .fail(function(XMLHttpRequest, textStatus, errorThrown) {
                    var ret = JSON.parse(XMLHttpRequest.responseText);
                    alert(ret.message);
                });
            }

            // eMail
            
            var currentmail_raw = $("#mail_current").val();
            var newmail_raw = $("#mail").val();
            if (currentmail_raw != newmail_raw) {
                var newmail = encodeURIComponent(newmail_raw); 
                $.get( "cmd_ctf.php?setEmail="+newmail, function( data, status ) {
                    $("#mail_current").val(newmail_raw);  
                    var ret = JSON.parse(data);
                    alert(ret.message);          
                })
                .fail(function(XMLHttpRequest, textStatus, errorThrown) {
                    var ret = JSON.parse(XMLHttpRequest.responseText);
                    alert(ret.message);
                });
            }
            // reload page from server
            //window.location.reload(true); 
            return false;
        }
        function onProfilePasswordChange()
        {
            var newpassword_raw = $("#password").val();
            var newpassword = encodeURIComponent(newpassword_raw); 
            $.get( "cmd_ctf.php?setPassword="+newpassword, function( data, status ) {
                alert(data);              
              })
            .fail(function() {
                alert('Invalid password');      
            });
            
            //alert("onProfileSave");
            // reload page from server
            return false;
        }
        function onResendValidationMail()
        {
            $.get( "cmd_ctf.php?resendValidationMail", function( data, status ) {
                alert(data);              
              })
            .fail(function() {
            });
            
            return false;
        }
        function onJoinCTF()
        {
            // Check name is available

            // Check fields are filled
            
            //alert("onProfileSave");
            // reload page from server
            return false;
        }
        function onCreateCTF()
        {
          var ctfname_raw = $("#createctf_name").val();
          var ctfname = encodeURIComponent(ctfname_raw); 
           $.get( "cmd_ctf.php?create="+ctfname+"&content="+ctfname, function( data, status ) {
                alert(data);              
              })
            .fail(function() {
            });
            
            return false;
        }
    </script>