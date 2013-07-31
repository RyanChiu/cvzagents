<div style="margin-bottom:10px;border-top:0;border-left:0;border:right:0;border-bottom:solid blue 1px;width:100%;color:blue;">
<?php echo $s; ?>
</div>
<?php
$logpath = APP . "tmp" . DS . "canals.log";
$from = "from canal $n: ";
$now = new DateTime("now", new DateTimeZone("GMT"));
$now = " [" . $now->format("Y-m-d H:i:s") . " GMT]\n";
if (empty($_POST)) {
	error_log(
		$from . "Nothing posted here" . $now,
		3, 
		$logpath
	);
} else {
	error_log(
		$from . "\n" . print_r($_POST, true) . $now,
		3,
		$logpath 
	);
}
?>