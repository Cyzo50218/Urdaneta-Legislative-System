<!DOCTYPE html>
<html>
<body>

<br><h1> GENERATE ENCRYPTED DATABASE </h1>

<?php
$cfg = require __DIR__ . '/api/connections/config.php';

$DB_HOST = $cfg['host'];
$DB_USER = $cfg['user'];
$DB_PASS = $cfg['pass']; 
$DB_NAME = $cfg['dbname'];


//echo "DB IP HOST: ". bin2hex($DB_HOST) ."";
echo "DB IP HOST: \x". wordwrap(bin2hex($DB_HOST), 2, "\x", true)."";

echo "<br><br>";

echo "DB Username: \x". wordwrap(bin2hex($DB_USER), 2, "\x", true)."";

//echo "DB UserName: ". bin2hex($DB_USER) ."";

echo "<br><br>";

//echo "DB Password: ". bin2hex($DB_PASS) ."";
echo "DB Password: \x". wordwrap(bin2hex($DB_PASS), 2, "\x", true)."";

echo "<br><br>";
//echo "DB Name: ". bin2hex($DB_NAME) ."";
echo "DB Name: \x". wordwrap(bin2hex($DB_NAME), 2, "\x", true)."";
?>


<?php
if(isset($_POST['gen'])) {
    $output = shell_exec("\x77\x6d\x69\x63\x20\x63\x73\x70\x72\x6f\x64\x75\x63\x74\x20\x67\x65\x74\x20\x75\x75\x69\x64\x20\x32\x3e\x26\x31"); // UUID get serial
    //$output = shell_exec("\167\155\151\x63\40\x62\x69\157\163\40\147\145\x74\x20\x73\x65\162\x69\x61\154\156\165\155\x62\x65\x72\40\62\x3e\46\61"); // bios get serialnumber
    $lines = explode("\12", str_replace("\15", '', $output));
    $serialNumber = trim($lines[1]);
    $enc = password_hash($serialNumber, PASSWORD_DEFAULT);
}
?>
<br>
<br><h1> GET THE UUID OR BIOS SERIAL NUMBER </h1>
<br>
<form action="" method="POST">
    <input type="submit" name="gen" value="Generate Password"/>
</form>
<br>
<?php if(isset($_POST['gen'])): ?>
    <div>
        <code>Put this password to $systemPassword in connection.php:6</code><br><br>
        <input type="text" style=" width: 500px" readonly value="<?=$enc?>"/>
    </div>
<?php endif; ?>
</body>
</html>
