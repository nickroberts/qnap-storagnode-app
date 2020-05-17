<?php
include "identityLib.php";
# ------------------------------------------------------------------------
#  Set variables
# ------------------------------------------------------------------------
$platformBase   = $_SERVER['DOCUMENT_ROOT'];
$moduleBase     = $platformBase . dirname($_SERVER['PHP_SELF']) ;
$scriptsBase    = $moduleBase . '/scripts' ;

$identityGenBinary = "/share/Public/identity.bin/identity" ;
$logFile = "/share/Public/identity/logs/storj_identity.log" ;

$data = loadConfig("${moduleBase}/config.json");
# Update config json file if updates provided
$inputs = loadConfig("php://input");
if (isset($inputs['authkey']) || isset($inputs['identity'])){
	// Saving Identity Path and Auth Key in JSON file.
	if(isset($inputs["authkey"])) { $data['AuthKey'] = $inputs["authkey"]; }
	if(isset($inputs["identity"])) { $data['Identity'] = $inputs["identity"]; }
	storeConfig($data, "config.json");
}

$identityGenScriptPath = $scriptsBase . DIRECTORY_SEPARATOR . 'generateIdentity.sh' ;
$Path = $data["Identity"] . "/storagenode";
$identityFilePath = "${Path}/identity.key" ;
$urlToFetch = "https://github.com/storj/storj/releases/latest/download/identity_linux_amd64.zip" ;
$identitypidFile   = $moduleBase  . DIRECTORY_SEPARATOR . 'identity.pid' ;

# ------------------------------------------------------------------------


    date_default_timezone_set('Asia/Kolkata');
    $date = Date('Y-m-d H:i:s');
    $output = "" ;
    $configFile = "config.json";

    $inputs = loadConfig("php://input");


    logMessage( "================== identity.php invoked ================== ");
    if (isset($_POST["createidval"])){
		logMessage("Identity php called for creation purpose identityString : " . $_POST['identityString']);
                if( checkIdentityProcessRunning($identitypidFile) == true )  {
                    logMessage("Identity process is already running!!\n");
                    echo "Identity Process is already running!\n" ;
		    return ;
                } else {
                    logMessage("Identity process not found running, STARTING a new one!!\n");
		}
		// Saving Identity Path and Auth Key in JSON file.
		$data['AuthKey'] = $_POST["identityString"];
		$data['Identity'] = $_POST["identitypath"];
		storeConfig($data, $configFile);
		
		if(identityExists($data) && validateExistence($data)) {
		    logMessage("Identity Key File and others already available");
		    echo "Identity Key File and others already available";
		    return ;
		} else {
			logMessage("Identity Key doesn't exists. Going to start identity generation ");
		}

		if(!isset($_POST["identityString"]))  {
		    logMessage("Identity String not provided");
		    echo "Identity String not provided";
		    return ;
		}
		$identityString = $_POST["identityString"] ;
		logMessage("value of identityString($identityString)");
		$identityPath = $_POST["identitypath"] ;
		logMessage("value of identityPath($identityPath)");

		$cmd = "$identityGenScriptPath $identityString $identityPath > ${logFile}.a 2>&1 & "; 
	
		$programStartTime = Date('Y-m-d H:i:s');
		logMessage("Launching command $cmd and capturing log in $logFile ");
		exec($cmd, $output );
		logMessage("Launched command (@ $programStartTime) ");

		$data['LogFilePath'] = $logFile;
		$data['idGenStartTime'] = $programStartTime ;
		updateConfig($data, $configFile);

		$file = escapeshellarg($logFile);
		$lastline =  `tail -c160 $file | sed -e 's#\\r#\\n#g' | tail -1 ` ;


	    logMessage("Invoked identity generation program ($identityGenScriptPath) ");
	    echo $lastline;

    } else if (isset($_POST["status"]) || isset($inputs['status'])) {
	    logMessage("Identity php called for fetching STATUS!");

	    $file = $data['LogFilePath'];
	    $pid = file_get_contents("identity.pid");
	    $prgStartTime = $data['idGenStartTime'] ;
	    $file = escapeshellarg($file);
	    $lastline =  `tail -c160 $file | sed -e 's#\\r#\\n#g' | tail -1 ` ;

	    if( identityExists($data) && validateExistence($data)) {
		logMessage("STATUS: Identity exists ! returning message");
		    logMessage("identity available at ${identityFilePath}");
		echo "identity available at $identityFilePath " ;
	    } else if($lastline == "Done"){	# EXACT Check to be figured out 
		    logMessage("STATUS: Identity generation completed. Returning message");
		    logMessage("identity available at ${identityFilePath}");
		    echo "identity available at ${identityFilePath}" ;
	    }else{
	    	$lastline = preg_replace('/\n$/', '', $lastline);
		logMessage("STATUS: Identity generation in progress (LOG: $lastline)");
		echo "Identity generation STATUS($date):<BR> " .
			"Process ID: $pid , " .
			    "Started at:  $prgStartTime <BR>" . $lastline ;
	    }

    }
    else if (isset($inputs['authkey']) || isset($inputs['identity'])){
    	$authkey = $inputs["authkey"];
    	$identity = $inputs["identity"];
		logMessage("Identity php called for creation purpose identityString : " . $authkey);
		if(identityExists($data) && validateExistence($data)) {
		    logMessage("Identity Key File and others already available");
		    echo "Identity Key File and others already available";
		    return ;
		} else {
			logMessage("Identity Key doesn't exists. Going to start identity generation ");
		}

		if(!isset($authkey))  {
		    logMessage("Identity String not provided");
		    echo "Identity String not provided";
		    return ;
		}
		$identityString = $authkey ;
		logMessage("value of identityString($identityString)");
		$identityPath = $identity ;
		logMessage("value of identityPath($identityPath)");

                if( checkIdentityProcessRunning($identitypidFile) == true )  {
                    logMessage("Identity process is already running!!\n");
                    echo "Identity Process is already running!\n" ;
		    return ;
                } else {
                    logMessage("Identity process not found running, STARTING a new one!!\n");
		}

		$cmd = "$identityGenScriptPath $identityString $identityPath > ${logFile}.a 2>&1 & "; 
	
		$programStartTime = Date('Y-m-d H:i:s');
		logMessage("Launching command $cmd and capturing log in $logFile ");
		exec($cmd, $output );
		logMessage("Launched command (@ $programStartTime) ");

		$jsonString = file_get_contents($configFile);
		$data = json_decode($jsonString, true);
		$data['LogFilePath'] = $logFile;
		
		$data['idGenStartTime'] = $programStartTime ;
		$newJsonString = json_encode($data);
		$file = $data['LogFilePath'];
		$file = escapeshellarg($file);
	    $lastline = `tail -c 59 $file `;
		file_put_contents($configFile, $newJsonString);

		logMessage("Invoked identity generation program ($identityGenScriptPath) ");
		echo "<b>Identity creation process is starting.</b><br>";
	    echo $lastline;

    }
    else if (isset($data['identityCreationProcessCheck'])){
        echo checkIdentityProcessRunning($identitypidFile) ? "true" : "false" ;
    }
    else if (isset($_POST["file_exist"])) {
	logMessage("Identity php called for finding file existence");
	checkIdentityFileExistence($data);
    } else if(isset($_POST['isstopAjax']) && ($_POST['isstopAjax'] == 1)){
	// Stop Identity
	killIdentityProcess($identitypidFile);
        exec("echo > $logFile ");
    } else {
	logMessage("Identity php called (PURPOSE NOT CLEAR)!");
    }
return (0);


?>
