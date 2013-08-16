<h1>
<?php echo $rs['Site']['id'] != 3 ? $rs['Site']['sitename'] . ' Affiliate' : 'Extra';  /*HARD CODE*/?>
</h1>
<?php
/*
echo '<br/>';
echo print_r($url, true);
echo print_r($pass, true);
echo print_r($passedArgs, true);
*/
$userinfo = $session->read('Auth.Account');
if (empty($data) || (!empty($data) && empty($data['SiteManual']['html']))) {
	echo 'The manual is not ready for the moment, please contact your administrator for details.';
} else {
	echo $data['SiteManual']['html'];
}
?>