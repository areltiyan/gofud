<?php
###Ini Copyright###
###https://github.com/osyduck/Gojek-Register###
##recode arel

include ("function.php");

function nama()
	{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://ninjaname.horseridersupply.com/indonesian_name.php");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$ex = curl_exec($ch);
	// $rand = json_decode($rnd_get, true);
	preg_match_all('~(&bull; (.*?)<br/>&bull; )~', $ex, $name);
	return $name[2][mt_rand(0, 14) ];
	}
function register($no)
	{
	$nama = nama();
	$email = str_replace(" ", "", $nama) . mt_rand(100, 999);
	$data = '{"name":"' . $nama . '","email":"' . $email . '@gmail.com","phone":"+' . $no . '","signed_up_country":"ID"}';
	$register = request("/v5/customers", "", $data);
	//print_r($register);
	if ($register['success'] == 1)
		{
		return $register['data']['otp_token'];
		}
	  else
		{
      save("error_log.txt", json_encode($register));
		return false;
		}
	}
function verif($otp, $token)
	{
	$data = '{"client_name":"gojek:cons:android","data":{"otp":"' . $otp . '","otp_token":"' . $token . '"},"client_secret":"83415d06-ec4e-11e6-a41b-6c40088ab51e"}';
	$verif = request("/v5/customers/phone/verify", "", $data);
	if ($verif['success'] == 1)
		{
		return $verif['data']['access_token'];
		}
	  else
		{
      save("error_log.txt", json_encode($verif));
		return false;
		}
	}
	function login($no)
	{
	$nama = nama();
	$email = str_replace(" ", "", $nama) . mt_rand(100, 999);
	$data = '{"phone":"+'.$no.'"}';
	$register = request("/v4/customers/login_with_phone", "", $data);
	//print_r($register);
	if ($register['success'] == 1)
		{
		return $register['data']['login_token'];
		}
	  else
		{
      save("error_log.txt", json_encode($register));
		return false;
		}
	}
function veriflogin($otp, $token)
	{
	$data = '{"client_name":"gojek:cons:android","client_secret":"83415d06-ec4e-11e6-a41b-6c40088ab51e","data":{"otp":"'.$otp.'","otp_token":"'.$token.'"},"grant_type":"otp","scopes":"gojek:customer:transaction gojek:customer:readonly"}';
	$verif = request("/v4/customers/login/verify", "", $data);
	if ($verif['success'] == 1)
		{
		return $verif['data']['access_token'];
		}
	  else
		{
      save("error_log.txt", json_encode($verif));
		return false;
		}
	}
function claim($token, $kode)
	{
	$data = '{"promo_code":"'.$kode.'"}';
	$claim = request("/go-promotions/v1/promotions/enrollments", $token, $data);
	if ($claim['success'] == 1)
		{
		return $claim['data']['message'];
		}
	  else
		{
      save("error_log.txt", json_encode($claim));
		return false;
		}
	}
echo "Choose Login or Register? Login = 1 & Register = 2: ";
$type = trim(fgets(STDIN));
if($type == 2){
echo "It's Register Way\n";
echo "Input 62 For ID and 1 For US Phone Number\n";
echo "Enter Number: ";
$nope = trim(fgets(STDIN));
$register = register($nope);
if ($register == false)
	{
	echo "Failed to Get OTP, Use Unregistered Number!\n";
	}
  else
	{
	echo "Enter Your OTP: ";
	// echo "Enter Number: ";
	$otp = trim(fgets(STDIN));
	$verif = verif($otp, $register);
	if ($verif == false)
		{
		echo "Failed to Registering Your Number!\n";
		}
	  else
		{
		echo "Ready to Claim 07\n";
		$claim = claim($verif, "GOFOODBOBA07");
		if ($claim == false)
			{
			echo "Failed to Claim Voucher 07, Try to Claim Manually\n";
			}
else
			{
			echo $claim . "\n";
			}
echo "Sleep 10 detik\n";
   Sleep(10);
echo "Ready to Claim 19\n";
$claim2 = claim($verif, "GOFOODBOBA19");
   if ($claim2 == false)
    {
     echo "Failed to Claim Voucher 19, Try to Claim Manually\n";
}
		  else
			{
			echo $claim2 . "\n";
			}
echo "Sleep 10 detik\n";
   Sleep(10);
echo "Ready to Claim 10\n";
$claim3 = claim($verif, "GOFOODBOBA10");
   if ($claim3 == false)
    {
     echo "Failed to Claim Voucher 10, Try to Claim Manually\n";
}
		  else
			{
			echo $claim3 . "\n";
			}
echo "Sleep 10 detik\n";
   Sleep(10);
echo "Ready to Claim GORIDE\n";
$claim4 = claim($verif, "COBAINGOJEK");
   if ($claim4 == false)
    {
     echo "Failed to Claim Voucher COBAINGOJEK, Try to Claim Manually\n";
}
		  else
			{
			echo $claim4 . "\n";
			}
echo "Sleep 10 detik\n";
   Sleep(10);
echo "Ready to Claim GORIDE 2\n";
$claim5 = claim($verif, "AYOCOBAGOJEK");
   if ($claim5 == false)
    {
     echo "Failed to Claim Voucher AYOCOBAGOJEK, Try to Claim Manually\n";
}
		  else
			{
			echo $claim5 . "\n";
			}
		}
	}
}else if($type == 1){
echo "It's Login Way\n";
echo "Input 62 For ID and 1 For US Phone Number\n";
echo "Enter Number: ";
$nope = trim(fgets(STDIN));
$login = login($nope);
if ($login == false)
	{
	echo "Failed to Get OTP!\n";
	}
  else
	{
	echo "Enter Your OTP: ";
	// echo "Enter Number: ";
	$otp = trim(fgets(STDIN));
	$verif = veriflogin($otp, $login);
	if ($verif == false)
		{
		echo "Failed to Login with Your Number!\n";
		}
	  else
		{
		echo "Ready to Claim 07\n";
		$claim = claim($verif, "GOFOODBOBA07");
		if ($claim == false)
			{
			echo "Failed to Claim Voucher 07, Try to Claim Manually\n";
			}
else
			{
			echo $claim . "\n";
			}
echo "Sleep 10 detik\n";
   Sleep(10);
echo "Ready to Claim 19\n";
$claim2 = claim($verif, "GOFOODBOBA19");
   if ($claim2 == false)
    {
     echo "Failed to Claim Voucher 19, Try to Claim Manually\n";
}
		  else
			{
			echo $claim2 . "\n";
						}
echo "Sleep 10 detik\n";
   Sleep(10);
echo "Ready to Claim 10\n";
$claim3 = claim($verif, "GOFOODBOBA10");
   if ($claim3 == false)
    {
     echo "Failed to Claim Voucher 10, Try to Claim Manually\n";
}
		  else
			{
			echo $claim3 . "\n";
			}
echo "Sleep 10 detik\n";
   Sleep(10);
echo "Ready to Claim GORIDE\n";
$claim4 = claim($verif, "COBAINGOJEK");
   if ($claim4 == false)
    {
     echo "Failed to Claim Voucher COBAINGOJEK, Try to Claim Manually\n";
}
		  else
			{
			echo $claim4 . "\n";
			}
echo "Sleep 10 detik\n";
   Sleep(10);
echo "Ready to Claim GORIDE 2\n";
$claim5 = claim($verif, "AYOCOBAGOJEK");
   if ($claim5 == false)
    {
     echo "Failed to Claim Voucher AYOCOBAGOJEK, Try to Claim Manually\n";
}
		  else
			{
			echo $claim5 . "\n";
			}
		}
	}
}
?>
