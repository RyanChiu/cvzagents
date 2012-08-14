<h1>New members</h1>
<?php
$userinfo = $session->read('Auth.Account');
?>
<br/>

<?php
/*showing the results*/
?>
<script type="text/javascript">
function __setActSusLink() {
	var checkboxes;
	checkboxes = document.getElementsByName("data[Account][selected]");
	var ids = "";
	for (var i = 0; i < checkboxes.length; i++) {
		if (checkboxes[i].checked && checkboxes[i].value != 0) {
			ids += checkboxes[i].value + ",";
		}
	}
	document.getElementById("linkActivateSelected").href =
		document.getElementById("linkActivateSelected_").href + "/ids:" + ids + "/status:1/from:2";
}
function __checkAll() {
	var checkboxes;
	checkboxes = document.getElementsByName("data[Account][selected]");
	for (var i = 0; i < checkboxes.length; i++) {
		checkboxes[i].checked = document.getElementById("checkboxAll").checked;
	}
}
</script>
<table style="width: 100%;">
<thead>
<tr>
	<th><b>
	<?php
	echo $form->checkbox('',
		array('id' => 'checkboxAll', 'value' => -1,
			'style' => 'border:0px;width:16px;',
			'onclick' => 'javascript:__checkAll();__setActSusLink();'
		)
	);
	?>
	</b></th>
	<th><b><?php echo $exPaginator->sort('Username', 'Account.username4m'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('Role', 'Account.role'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('Registered', 'Account.regtime'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('Status', 'Account.status'); ?></b></th>
	<th><b>Operation</b></th>
</tr>
</thead>
<?php
$i = 0;
foreach ($rs as $r):
?>
<tr <?php echo $i % 2 == 0? '' : 'class="odd"'; ?>>
	<td>
	<?php
	echo $form->checkbox('Account.selected',
		array('value' => $r['Account']['id'],
			'style' => 'border:0px;width:16px;',
			'onclick' => 'javascript:__setActSusLink();'
		)
	);
	echo '<font size="1">' . ($i + 1 + $limit * ($paginator->current() - 1)) . '</font>';
	?>
	</td>
	<td><?php echo $r['Account']['username']; ?></td>
	<td><?php echo $r['Account']['role'] == 1 ? "Office manager" : "Agent"; ?></td>
	<td><?php echo $r['Account']['regtime']; ?></td>
	<td><?php echo $status[$r['Account']['status']]; ?></td>
	<td align="center">
	<?php
	echo $html->link(
			$html->image('iconActivate.png', array('border' => 0, 'width' => 16, 'height' => 16)) . '&nbsp;',
			array('controller' => 'trans', 'action' => 'activatem', 'ids' => $r['Account']['id'], 'status' => 1, 'from' => 2),
			array('title' => 'Click to approve the account.'),
			false, false
	);
	?>
	</td>
</tr>
<?php
$i++;
endforeach;
?>
</table>

<div style="margin-top:3px;">
<font color="green">With selected :&nbsp;</font>
<?php
/*activate selected*/
echo $html->link(
	$html->image('iconActivate.png', array('border' => 0, 'width' => 16, 'height' => 16)) . '&nbsp;&nbsp;',
	array('controller' => 'trans', 'action' => 'activatem'),
	array('id' => 'linkActivateSelected', 'title' => 'Click to approve the selected accounts.'),
	false, false
);
echo $html->link(
	'',
	array('controller' => 'trans', 'action' => 'activatem'),
	array('id' => 'linkActivateSelected_')
);
?>
</div>

<script type="text/javascript">
jQuery(":checkbox").attr({style: "border:0px;width:16px;vertical-align:middle;"}); 
</script>
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<?php
echo $this->element('paginationblock');
?>
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->