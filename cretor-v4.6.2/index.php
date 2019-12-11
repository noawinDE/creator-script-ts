<?php
$HOST_QUERYG = $_POST['hostsy'];
////////////////////////////////////////////////////
//// Simple TeamSpeak Server Creator v1.0      ////
//// Copyright: @Noawin                       ////
//// GITHUB: Noawin                          ////
////////////////////////////////////////////////
	date_default_timezone_set('Europe/Paris'); //Change Here to your locale timezone!
	require_once("libraries/TeamSpeak3/TeamSpeak3.php");
	include 'data/config.php';
	if (isset($_POST["create"])) {
		$connect = "serverquery://".$USER_QUERY.":".$PASS_QUERY."@".$HOST_QUERYG.":".$PORT_QUERY."";
    		$ts3 = TeamSpeak3::factory($connect);
		$servername = $_POST['servername'];
		$slots = $_POST['slots'];
		$slotsRight = false;
		foreach([5,10,15,20,25,30,35,40] as $slot) {
			if($slots == $slot)
				$slotsRight = true;
		}
		if(!$slotsRight) {
		echo "Die Slots wurden nicht gefunden!";
die();		
		}
		$port = rand(12000,13000);
		$unixTime = time();
		$realTime = date('[Y-m-d]-[H:i]',$unixTime);
        $create_array = [
            "virtualserver_name" => $servername,
            "virtualserver_maxclients" => $slots,
            "virtualserver_name_phonetic" => $realTime,
            "virtualserver_hostbutton_tooltip" => $name,
            "virtualserver_hostbutton_url" => $hostlink,
            "virtualserver_hostbutton_gfx_url" => $hostlogo,
			"virtualserver_hostbanner_gfx_url" => $bannerbild,
			"virtualserver_hostbanner_url" => $hostlink,
			"virtualserver_default_client_description" => "",
			"virtualserver_default_channel_description" => "",
			"virtualserver_default_channel_topic" => "",
        ];
		
		if(!empty($port)) {
            array_merge($create_array, ["virtualserver_port" => $port]);
        }
        $curl = curl_init("https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($curl, CURLOPT_POST, 2);
        curl_setopt($curl, CURLOPT_POSTFIELDS, ['secret' => $GOOGLE_CAPTCHA_PRIVATEKEY, 'response' => $_POST['g-recaptcha-response']]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec ($curl);
        curl_close ($curl);
        if(json_decode($response, TRUE)['success']) {
            try {
                $new_ts3 = $ts3->serverCreate($create_array);
                $token = $new_ts3['token'];
                $createdport = $new_ts3['virtualserver_port'];
		
            } catch (Exception $e) {
                echo "Error (ID " . $e->getCode() . ") <b>" . $e->getMessage() . "</b>";
            }
        }else {
            die;
        }
		
	}
	$version = file_get_contents("./version");
?>
<!DOCTYPE html>
<html lang="de" class="no-js">
    <head>
	<meta content="freets, ts,free ts3, kostenlose teamspeak3 Server" name="keywords">
		<meta content="<?php echo $name; ?>, Free ts Service." name="description">
        <meta charset="UTF-8" />
        <title>TS Creator :: <?php echo $name; ?></title>
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" type="text/css" href="css/animate-custom.css" />
		<script src='https://www.google.com/recaptcha/api.js'></script>
	</head>
    <body>
        	  <center>
	  <div class="container1">

               <div id="container_demo" >
                    <div id="wrapper">
                        <div id="login" class="animate form">
							<?php if (isset($_POST["create"])): ?>
								<form  method="post" autocomplete="off"> 
									
									<h1>Server Created!</h1> 
									
									<p> 
										<label align="left" class="uname" data-icon="u" > Server Name</label><br>
										<input readonly class="name" type="text" value="<?php echo $servername; ?>"/><br>
									</p>
									
									<p> 
										<label align="left" class="uname" data-icon="u" > Server Admin Token</label><br>
										<input readonly  class="name" type="text" value="<?php echo $token; ?>"/><br>
									</p>
									
									<p> 
										<label align="left" class="uname" data-icon="u" > Server Adresse</label><br>
										<input class="name" readonly type="text" value="<?php echo $HOST_QUERYG; ?>:<?php echo $createdport; ?>"/><br>
									</p>
									<p> 
                                        <a  href="<?php echo "ts3server://$HOST_QUERYG?port=$createdport&token=$token"; ?>" target=""> <input class="dlbtn" type="button" value="Verbinden!"> </a>  
                                    </p>
								</form>
                            <?php else: ?>
								<form  method="post" autocomplete="off"> 
									<h1><?php echo $name; ?></h1> 
									<div class="note" id="echo">
  </div>
									<p class="name2"> 
										<label align="left" class="uname" data-icon="u" > Server Name</label><br>
										<input  name="servername" class="name" required="required" type="text" placeholder="Server Name"/><br>
									</p>
									

								   <p> 
								        <label align="left" class="youpasswd" > Host System</label><br>
										<select name="hostsy"  type="text"  onkeydown="return false" required="required"> 
										<option value="<?php echo $HOST_QUERY; ?>"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $HOST1; ?></font></font></option>
										<option value="<?php echo $HOST_QUERY1; ?>"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $HOST2; ?></font></font></option>
										<option value="<?php echo $HOST_QUERY2; ?>"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $HOST3; ?></font></font></option>
										<option value="<?php echo $HOST_QUERY3; ?>"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $HOST4; ?></font></font></option>
										<option value="<?php echo $HOST_QUERY4; ?>"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $HOST5; ?></font></font></option>
										<option value="<?php echo $HOST_QUERY5; ?>"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $HOST6; ?></font></font></option>
										<option value="<?php echo $HOST_QUERY6; ?>"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $HOST7; ?></font></font></option>
										</select><br>
										<label align="left" class="youpasswd" > Slots</label><br>
										<select name="slots"  type="text"  onkeydown="return false" required="required"> 
										<option value="5"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">5 Slots</font></font></option>
										<option value="10"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">10 Slots</font></font></option>
										<option value="15"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">15 Slots</font></font></option>
										<option value="20"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">20 Slots</font></font></option>
										<option value="25"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">25 Slots</font></font></option>
										<option value="30"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">30 Slots</font></font></option>
										<option value="32"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">32 Slots</font></font></option>
										</select><br>
									</p>
									<br>
									<p>
								<div class="g-recaptcha" data-sitekey="<?=$GOOGLE_CAPTCHA_PUBLICKEY?>"></div>
								</p>
								<br>

									<p class="name4" > 
										  <input type="submit" align="right" class="dlbtn" name="create" value="Erstellen!" /> 
									</p>
								</form>
								</div>
							<?php endif; ?>


      </div>
		</div>
		</center>
		</section>
<section>
	    <div class="footer">
            <div style="align-self: center; position: fixed; left: 5px;" align="left">Copyright &copy; 2019 - 2020 <a href="http://<?php echo $name; ?>.de/" style="display: inline-block; position: relative"><?php echo $name; ?></a></div>
            <div style="align-self: center; right: 5px" align="center">TeamSpeak Server Creator (<?php echo $version; ?>) by Noawinzp(NicoB.)</div>
			<div style="align-self: center; right: 5px" align="center">Open Source auf <a href="https://github.com/noawinzp/Simpel-Server-Creator-Skript" >Githup</a></div>
        </div>
			</section>
	</body>
</html>																							
