<h1>Companies</h1>
<?php
//echo '<br/>';
//echo print_r($rs, true);
?>
<?php
/*searching part*/
?>
<div style="width:100%;margin-top:5px;" id="search">
<?php
echo $form->create(null, array('controller' => 'trans', 'action' => 'lstcompanies', 'id' => 'frmSearch'));
?>
<table style="width:100%;border:0;">
	<caption>
	<?php echo $html->image('iconSearch.png', array('style' => 'width:16px;height:16px;')) . 'Search'; ?>
	</caption>
	<tr>
		<td class="search-label" style="width:105px;">Username:</td>
		<td>
		<div style="float:left;width:275px;">
		<?php echo $form->input('ViewCompany.username', array('label' => '', 'style' => 'width:260px;')); ?>
		</div>
		<div style="float:left;width:112px;">
		<?php echo $form->submit('Search', array('style' => 'float:left;width:96px;')); ?>
		</div>
		<div style="float:left;">
		<?php echo $form->submit('Clear', array('style' => 'float:left;width:64px;', 'onclick' => 'javascript:__zClearForm("frmSearch");')); ?>
		</div>
		</td>
	</tr>
</table>
<?php
echo $form->end();
?>
</div>
<br/>

<?php
/*showing the results*/
?>
<script type="text/javascript">
function __setActSusLink() {
	var checkboxes;
	checkboxes = document.getElementsByName("data[ViewCompany][selected]");
	var ids = "";
	for (var i = 0; i < checkboxes.length; i++) {
		if (checkboxes[i].checked && checkboxes[i].value != 0) {
			ids += checkboxes[i].value + ",";
		}
	}
	document.getElementById("linkActivateSelected").href =
		document.getElementById("linkActivateSelected_").href + "/ids:" + ids + "/status:1/from:0";
	document.getElementById("linkSuspendSelected").href =
		document.getElementById("linkSuspendSelected_").href + "/ids:" + ids + "/status:0/from:0";
}
function __setCurSelectedToBeInformed() {
	var checkboxes;
	checkboxes = document.getElementsByName("data[ViewCompany][selected]");
	var ids = "";
	for (var i = 0; i < checkboxes.length; i++) {
		if (checkboxes[i].checked && checkboxes[i].value != 0) {
			ids += checkboxes[i].value + ",";
		}
	}
	document.getElementById("hidCurSelectedToBeInformed").value = ids;
}
function __setInfLink() {
	document.getElementById("linkInform").href =
		document.getElementById("linkInform_").href + "/from:0"
			+ "/ids:" + document.getElementById("hidCurSelectedToBeInformed").value
			+ "/notes:" + document.getElementById("txtNotes").value;
}
function __checkAll() {
	var checkboxes;
	checkboxes = document.getElementsByName("data[ViewCompany][selected]");
	for (var i = 0; i < checkboxes.length; i++) {
		checkboxes[i].checked = document.getElementById("checkboxAll").checked;
	}
}
</script>

<div style="margin-bottom:3px">
<?php
echo $form->button('Add Office',
	array(
		'onclick' => 'javascript:location.href="'
			. $html->url(array('controller' => 'trans', 'action' => 'regcompany')) . '"',
		'style' => 'width:160px;'
	)
);
?>
</div>
<table style="width:100%">
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
	<th><b><?php echo $exPaginator->sort('Office', 'ViewCompany.officename'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('Total Agents', 'ViewCompany.agenttotal'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('Username', 'ViewCompany.username4m'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('Password', 'ViewCompany.originalpwd'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('Registered', 'ViewCompany.regtime'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('Status', 'ViewCompany.status'); ?></b></th>
	<th><b>Operation</b></th>
</tr>
</thead>
<?php
$i = 0;
foreach ($rs as $r):
?>
<tr <?php echo $i % 2 == 0 ? '' : 'class="odd"'; ?>>
	<td>
	<?php
	echo $form->checkbox('ViewCompany.selected',
		array('value' => $r['ViewCompany']['companyid'],
			'style' => 'border:0px;width:16px;',
			'onclick' => 'javascript:__setActSusLink();'
		)
	);
	?>
	</td>
	<td>
	<?php
	/*
	echo $html->link(
		$r['ViewCompany']['officename'],
		array('controller' => 'trans', 'action' => 'lstagents', 'id' => $r['ViewCompany']['companyid']),
		array('title' => 'Click to the agents.')
	);
	*/
	echo $r['ViewCompany']['officename'];
	?>
	</td>
	<td align="center">
	<?php
	echo $html->link(
		$r['ViewCompany']['agenttotal'] . '&nbsp;' . $html->image('iconList.gif', array('border' => 0)),
		array('controller' => 'trans', 'action' => 'lstagents', 'id' => $r['ViewCompany']['companyid']),
		array('title' => 'Click to the agents.'),
		false, false
	);
	?>
	</td>
	<td><?php echo $r['ViewCompany']['username']; ?></td>
	<td><?php echo $r['ViewCompany']['originalpwd']; ?></td>
	<td><?php echo $r['ViewCompany']['regtime']; ?></td>
	<td><?php echo $status[$r['ViewCompany']['status']]; ?></td>
	<td align="center">
	<?php
	echo $html->link(
		$html->image('iconEdit.png', array('border' => 0, 'width' => 16, 'height' => 16)) . '&nbsp;',
		array('controller' => 'trans', 'action' => 'updcompany', 'id' => $r['ViewCompany']['companyid']),
		array('title' => 'Click to edit this record.'),
		false, false
	);
	echo $html->link(
		$html->image('iconActivate.png', array('border' => 0, 'width' => 16, 'height' => 16)) . '&nbsp;',
		array('controller' => 'trans', 'action' => 'activatem', 'ids' => $r['ViewCompany']['companyid'], 'status' => 1, 'from' => 0),
		array('title' => 'Click to activate the user.'),
		false, false
	);
	echo $html->link(
		$html->image('iconSuspend.png', array('border' => 0, 'width' => 16, 'height' => 16)) . '&nbsp;',
		array('controller' => 'trans', 'action' => 'activatem', 'ids' => $r['ViewCompany']['companyid'], 'status' => 0, 'from' => 0),
		array('title' => 'Click to suspend the user.'),
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
	array('id' => 'linkActivateSelected', 'title' => 'Click to activate the selected users.'),
	false, false
);
echo $html->link(
	'',
	array('controller' => 'trans', 'action' => 'activatem'),
	array('id' => 'linkActivateSelected_')
);
/*suspend selected*/
echo $html->link(
	$html->image('iconSuspend.png', array('border' => 0, 'width' => 16, 'height' => 16)) . '&nbsp;&nbsp;',
	array('controller' => 'trans', 'action' => 'activatem'),
	array('id' => 'linkSuspendSelected', 'title' => 'Click to suspend the selected users.'),
	false, false
);
echo $html->link(
	'',
	array('controller' => 'trans', 'action' => 'activatem'),
	array('id' => 'linkSuspendSelected_')
);
/*inform selected --*/
/*undim this block to function it
echo $html->link(
	$html->image('iconInform.png',
		array('id' => 'open_message',
			'border' => 0, 'width' => 16, 'height' => 16,
			'onclick' => 'javascript:__setCurSelectedToBeInformed();__setInfLink();'
		)
	),
	'#',
	array('title' => 'Click to inform the selected users.'),
	false, false
);
*/
?>
</div>

<!-- ~~~~~~~~~~~~~~~~~~~the floating message box for "inform selected"~~~~~~~~~~~~~~~~~~~ -->
<div id="message_box">
	<table style="width:100%">
	<thead><tr><th>
		<div style="float:left">Please enter your notes below.</div>
		<?php echo $html->image('iconClose.png', array('id' => 'close_message', 'style' => 'float:right;cursor:pointer')); ?>
	</th></tr></thead>
	<tr><td><textarea id="txtNotes" style="width:99%" rows="6" onchange="javascript:__setInfLink();"></textarea></td></tr>
	<tr><td>
		<?php
		echo $form->input('', array('type' => 'hidden', 'id' => 'hidCurSelectedToBeInformed'));
		echo $html->link(
			'',
			array('controller' => 'trans', 'action' => 'informem'),
			array('id' => 'linkInform_')
		);
		echo $html->link(
			'Inform',
			array('controller' => 'trans', 'action' => 'informem'),
			array('id' => 'linkInform'),
			false, false
		);
		?>
	</td></tr>
	</table>
</div>

<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<?php
echo $this->element('paginationblock');
?>
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->