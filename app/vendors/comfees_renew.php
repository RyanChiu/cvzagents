<?php
/*
 * Excecute this script everytime after the table fees,
 * companies or com_fees being changed immediately!
 * This script will renew the table tmp_com_fees.
 * It takes no parameter.
 */
include 'zmysqlConn.class.php';

$zconn = new zmysqlConn;
/*1.empty the table*/
$sql = "TRUNCATE TABLE `tmp_com_fees`";
mysql_query($sql, $zconn->dblink)
	or die ("Something wrong with: " . mysql_error());
/*2.rebuild all the office fees*/
$sql = "select id from companies";
$rs = mysql_query($sql, $zconn->dblink)
	or die ("Something wrong with: " . mysql_error());
$coms = mysql_num_rows($rs);
$ins = 0;
while ($row = mysql_fetch_assoc($rs)) {
	$sql = "
		insert into tmp_com_fees (feeid, companyid, ownprice)
		SELECT 
		fees.id AS feesid, companies.id AS companyid, fees.price AS ownprice
		FROM fees	LEFT JOIN companies
		ON companies.id = "
		. $row['id'];
	mysql_query($sql, $zconn->dblink)
		or die ("Something wrong with: " . mysql_error());
	$ins++;
}
echo $coms == $ins ? "All done.($ins)\n" : $coms - $ins . " office(s) missed.\n";
/*3.change the own price for some offices that in com_fees*/
$sql = "select * from com_fees";
$rs = mysql_query($sql, $zconn->dblink)
	or die ("Something wrong with: " . mysql_error());
$upts = 0;
while ($row = mysql_fetch_assoc($rs)) {
	$sql = "
		update tmp_com_fees set feeid = %d, companyid = %d, ownprice = %.2f
		where feeid = %d and companyid = %d
	";
	$sql = sprintf($sql, $row['feeid'], $row['companyid'], $row['ownprice'], $row['feeid'], $row['companyid']);
	mysql_query($sql, $zconn->dblink)
		or die ("Something wrong with: " . mysql_error());
	$upts++;
}
echo $upts . " office fee(s) changed.\n";
?>