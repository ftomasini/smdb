<html>
<head>
<title>pgwatch setup</title>
<style>
p.error {color:red; font-weight: bold;}
</style>
</head>
<body>
<h1>Setup</h1>
<h2>pgwatch - Cybertec Enterprise PostgreSQL Monitor</h2>
<?php

function req($varname, $default=""){
	return (isset($_REQUEST[$varname])) ?$_REQUEST[$varname] :$default;
}
$phase = isset($_REQUEST['phase']) ?$_REQUEST['phase'] :1;

$dbhost = req('dbhost', "localhost");
$dbport = req('dbport', 5432);
$dbuser = req('dbuser', "postgres");
$dbpass = req('dbpass', "");
$appdb  = req('appdb', "pgwatch");
$appuser = req('appuser', "pgwatch");
$apppass = req('apppass', "");

$_hostname = $_SERVER['SERVER_NAME'];
$docroot = $_SERVER['DOCUMENT_ROOT'];
$_folder = dirname(substr(__FILE__, strlen($docroot)));
$_folder = trim($_folder, "/");
$_folder = trim($_folder, "\\");

$hostname = req('hostname', $_hostname);
$folder = req('folder', $_folder);

switch ($phase){
case 1:
	break;
case 2:
	$conn = @pg_connect("host={$dbhost} port={$dbport} user={$dbuser} password={$dbpass}");
	if (!$conn){
		$phase = 1;
		$errmsg = "Connection failed by admin settings.";
		break;
	}
	break;
case 3:
	$conn = @pg_connect("host={$dbhost} port={$dbport} user={$dbuser} password={$dbpass}");
	if (!$conn){
		$phase = 1;
		$errmsg = "Connection failed by admin settings.";
		break;
	}
	echo "Admin connection estabilished.<br/>"; ob_flush(); flush();
	echo "Checking if user {$appuser} exists... "; ob_flush(); flush();
	$res = @pg_query($conn, "SELECT * FROM pg_user WHERE usename='{$appuser}'");
	if (pg_num_rows($res)){
		echo "already exists.<br/>"; ob_flush(); flush();
	}else{
		echo "doesn't exist yet.<br/>"; ob_flush(); flush();
		echo "Creating user {$appuser}... "; ob_flush(); flush();
		$res = @pg_query($conn, "CREATE USER {$appuser} LOGIN SUPERUSER PASSWORD '{$apppass}'");
		if (!$res){
			echo "failed.<br/>"; ob_flush(); flush();
			$errmsg = "Creating user {$appuser} failed: ".pg_last_error($conn);
			$phase = 2;
			break;
		}else{
			echo "succeeded.<br/>"; ob_flush(); flush();
		}
	 }

	echo "Checking if database {$appdb} exists... "; ob_flush(); flush();
	$res = @pg_query($conn, "SELECT * FROM pg_database WHERE datname='{$appdb}'");
	if (pg_num_rows($res)){
		echo "already exists.<br/>"; ob_flush(); flush();
	}else{
		echo "doesn't exist yet.<br/>"; ob_flush(); flush();
		echo "Creating database {$appdb}... "; ob_flush(); flush();
		$res = @pg_query($conn, $a="CREATE database {$appdb} WITH OWNER={$appuser}");
		if (!$res){
			echo "failed.<br/>"; ob_flush(); flush();
			$errmsg = "Creating database {$appdb} failed: ".pg_last_error($conn);
			$phase = 2;
			break;
		}else{
			echo "succeeded.<br/>"; ob_flush(); flush();
		}
	 }

	echo "Connecting to database {$appdb} by user {$appuser}... "; ob_flush(); flush();
	$conn2 = @pg_connect($a="host={$dbhost} port={$dbport} dbname={$appdb} user={$appuser} password={$apppass}");
	if (!$conn2){
		echo "Failed.<br/>"; ob_flush(); flush();
		$errmsg = "Connecting to new database by new user failed.";
		$phase = 2;
		break;
	}else{
		echo "estabilished.<br/>"; ob_flush(); flush();
	}

	$hostname = $_SERVER['SERVER_NAME'];
	$docroot = $_SERVER['DOCUMENT_ROOT'];
	$folder = dirname(substr(__FILE__, strlen($docroot)));
	$folder = trim($folder, "/");
	$folder = trim($folder, "\\");

	echo "Creating config/config.ini..."; ob_flush(); flush();
	$config_ini = file_get_contents("config/config.ini.template");
	$config_ini = str_replace('__DBHOST__', $dbhost, $config_ini);
	$config_ini = str_replace('__DBPORT__', $dbport, $config_ini);
	$config_ini = str_replace('__DBNAME__', $appdb, $config_ini);
	$config_ini = str_replace('__DBUSER__', $appuser, $config_ini);
	$config_ini = str_replace('__DBPASS__', $apppass, $config_ini);
	$config_ini = str_replace('__TMPDIR__', sys_get_temp_dir(), $config_ini);
	$config_ini = str_replace('__HOSTNAME__', $hostname, $config_ini);
	$config_ini = str_replace('__FOLDER__', $folder, $config_ini);
	$x=file_put_contents("config/config.ini", $config_ini);
	if (!$x){
		$errmsg = "Failed to create config/config.ini file.<br/>";
		$errmsg .= "Probably there is no write permission for the web server on \"config/\" folder.";
		break;
	}
	echo "done.<br/>"; ob_flush(); flush();

	$appurl = "http://" . $hostname . "/" . $folder . "/#page=configure";


	$setup_sql = file_get_contents("sql/setup.sql.template");
	$setup_sql = str_replace('__DBUSER__', $appuser, $setup_sql);

	echo "Checking if database is not empty..."; ob_flush(); flush();
	$res = pg_query($conn2, "SELECT count(*) FROM pg_tables WHERE schemaname='config'");
	$row = pg_fetch_assoc($res);
	if ($row['count'] == 0){

		echo "\"config\" schema is empty, probably database is empty.<br/>"; ob_flush(); flush();

		echo "Begin..."; ob_flush(); flush();
		if (!@pg_query($conn2, "BEGIN")){
			$errmsg = pg_last_error($conn2);
			break;
		};
		echo "done.<br/>"; ob_flush(); flush();

		echo "Creating database initial content..."; ob_flush(); flush();
		if (!@pg_query($conn2, $setup_sql)){
			$errmsg = pg_last_error($conn2);
			break;
		};
		echo "done.<br/>"; ob_flush(); flush();
		$errmsg = pg_last_notice($conn2);

		echo "Commit..."; ob_flush(); flush();
		if (!@pg_query($conn2, "COMMIT")){
			$errmsg = pg_last_error($conn2);
			break;
		};
		echo "done.<br/>"; ob_flush(); flush();

	}else{
		echo "\"config\" schema is not empty, probably database is not empty.<br/>"; ob_flush(); flush();
		echo "Skipping the creation of the rest.<br/>"; ob_flush(); flush();
	}


	echo "You can start working at: <a href='{$appurl}' target='pgwatch'>{$appurl}</a>";

	break;

}



######## html of phase 1:
switch ($phase){
case 1:
?>
<h3>Phase 1: Database admin settings</h3>
<p>These settings are for only the setup process, to create new database, new user, etc.</p>
<p>Please enter the details of the target database server and an administrator user
who has privileges to create a database and a new user (if neccessary).</p>
<?php if ($errmsg) echo "<p class='error'>",$errmsg,"</p>"; ?>
<form>
<table>
<tr><td><label for="dbhost">Database host:</label></td>
	<td><input type="text" id="dbhost" name="dbhost" value=<?php echo "\"$dbhost\""; ?> /></td>
</tr>
<tr><td><label for="dbport">Database port:</label></td>
	<td><input type="text" id="dbport" name="dbport" value=<?php echo "\"$dbport\""; ?> /></td>
</tr>
<tr><td><label for="dbuser">Database user:</label></td>
	<td><input type="text" id="dbuser" name="dbuser" value=<?php echo "\"$dbuser\""; ?> /></td>
</tr>
<tr><td><label for="dbpass">Database password:</label></td>
	<td><input type="text" id="dbpass" name="dbpass" value=<?php echo "\"$dbpass\""; ?> /></td>
</tr>
</table>
<input type="hidden" name="phase" value="2"/>
<input type="submit" value="Next &gt;"/>
</form>
<?php
break;
case 2:
?>
<h3>Phase 2: Application base settings</h3>
<p>Please enter the details of the database connection the application will use to store its data into.</p>
<p>In case of any of the database or the user don't exist they will be created automatically.</p>
<p>If they exist but there are privilege problems then the setup won't solve it automatically.</p>
<?php if ($errmsg) echo "<p class='error'>",$errmsg,"</p>"; ?>
<form>
<table>
<tr><td>Application host:</td><td><?php echo $dbhost; ?></td></tr>
<tr><td>Application port:</td><td><?php echo $dbport; ?></td></tr>
<tr><td><label for="appdb">Application database:</label></td>
	<td><input type="text" id="appdb" name="appdb" value=<?php echo "\"$appdb\""; ?> /></td>
</tr>
<tr><td><label for="appuser">Application user:</label></td>
	<td><input type="text" id="appuser" name="appuser" value=<?php echo "\"$appuser\""; ?> /></td>
</tr>
<tr><td><label for="apppass">Application password:</label></td>
	<td><input type="text" id="apppass" name="apppass" value=<?php echo "\"$apppass\""; ?> /></td>
</tr>
<tr><td><label for="hostname">Host name:</label></td>
	<td><input type="text" id="hostname" name="hostname" value=<?php echo "\"$hostname\""; ?> /></td>
</tr>
<tr><td><label for="apppass">Application folder:</label></td>
	<td><input type="text" id="folder" name="folder" value=<?php echo "\"$folder\""; ?> /></td>
</tr>
</table>
<input type="hidden" name="dbhost" value=<?php echo "\"$dbhost\""; ?>/>
<input type="hidden" name="dbport" value=<?php echo "\"$dbport\""; ?>/>
<input type="hidden" name="dbuser" value=<?php echo "\"$dbuser\""; ?>/>
<input type="hidden" name="dbpass" value=<?php echo "\"$dbpass\""; ?>/>
<input type="hidden" name="phase" value="3"/>
<input type="submit" value="Start!"/>
</form>
<?php
break;
case 3:
if ($errmsg) echo "<p class='error'>",$errmsg,"</p>";
break;
}
?>
</body>
<html>
