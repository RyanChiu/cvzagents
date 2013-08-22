<h1>Agents</h1>
<?php
/*
echo '<br/>';
echo print_r($url, true);
echo print_r($pass, true);
echo print_r($passedArgs, true);
*/
$userinfo = $session->read('Auth.Account');
?>
<?php
/*searching part*/
?>
<div style="width:100%;margin-top:5px;" id="search">
<?php
echo $form->create(null, array('controller' => 'trans', 'action' => 'lstagents', 'id' => 'frmSearch'));
?>
<table style="width:100%;border:0;">
	<caption>
	<?php echo $html->image('iconSearch.png', array('style' => 'width:16px;height:16px;')) . 'Search'; ?>
	</caption>
	<tr>
		<td class="search-label" style="width:105px;">Username:</td>
		<td>
		<div style="float:left;width:275px;">
		<?php echo $form->input('ViewAgent.username', array('label' => '', 'style' => 'width:260px;')); ?>
		</div>
		</td>
		<?php
		if ($userinfo['role'] == 0) {
		?>
		<td class="search-label" style="width:145px;">Office:</td>
		<?php
		} else {
		?>
		<td colspan="2" width="450px"></td>
		<?php
		}
		if ($userinfo['role'] == 0) {
			echo '<td>';
			echo $form->input('Company.id',
				array('label' => '', 'type' => 'select',
					'options' => $coms,	'style' => 'width:260px;'
				)
			);
			echo '</td>';
		}
		?>
	</tr>
	<tr>
		<td class="search-label" style="width:105px;">Last Name:</td>
		<td>
		<div style="float:left;width:275px;">
		<?php echo $form->input('ViewAgent.aglastname', array('label' => '', 'style' => 'width:260px;')); ?>
		</div>
		</td>
		<td class="search-label">Campaign ID:</td>
		<td>
		<div style="float:left;width:275px;">
		<?php echo $form->input('AgentSiteMapping.campaignid', array('label' => '', 'style' => 'width:260px;')); ?>
		</div>
		</td>
	</tr>
	<tr>
		<td class="search-label" style="width:105px;">First Name:</td>
		<td>
		<div style="float:left;width:275px;">
		<?php echo $form->input('ViewAgent.ag1stname', array('label' => '', 'style' => 'width:260px;')); ?>
		</div>
		</td>
		<td class="search-label">Status:</td>
		<td>
		<div style="float:left;width:275px;">
		<?php
		echo $form->input('ViewAgent.status',
			array(
				'label' => '', 'style' => 'width:260px;',
				'type' => 'select', 'options' => (array('-1' => 'All') + $status)
			)
		);
		?>
		</div>
		</td>
	</tr>
	<tr>
		<td class="search-label" style="width:105px;">Email:</td>
		<td>
		<div style="float:left;width:275px;">
		<?php echo $form->input('ViewAgent.email', array('label' => '', 'style' => 'width:260px;')); ?>
		</div>
		</td>
		<td class="search-label">Suspended Site:</td>
		<td>
		<?php
		echo $form->input('SiteExcluding.siteid',
			array('label' => '', 'type' => 'select',
				'options' => $sites,	'style' => 'width:260px;'
			)
		);
		?>
		</td>
	</tr>
	<tr>
		<td colspan="2"></td>
		<td colspan="2">
		<div style="float:left;width:112px;">
		<?php echo $form->submit('Search', array('style' => 'float:left;width:96px;')) ?>
		</div>
		<div style="float:left;">
		<?php echo $form->submit('Clear', array('style' => 'float:left;width:96px;', 'onclick' => 'javascript:__zClearForm("frmSearch");')); ?>
		</div>
		</td>
	</tr>
</table>
<?php
$companyid = 0;
if ($userinfo['role'] == 1) {//means an office
	$companyid = $userinfo['id'];
}
echo $form->input('ViewAgent.companyid', array('type' => 'hidden', 'value' => $companyid));
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
	checkboxes = document.getElementsByName("data[ViewAgent][selected]");
	var ids = "";
	for (var i = 0; i < checkboxes.length; i++) {
		if (checkboxes[i].checked && checkboxes[i].value != 0) {
			ids += checkboxes[i].value + ",";
		}
	}
	document.getElementById("linkActivateSelected").href =
		document.getElementById("linkActivateSelected_").href + "/ids:" + ids + "/status:1/from:1";
	document.getElementById("linkSuspendSelected").href =
		document.getElementById("linkSuspendSelected_").href + "/ids:" + ids + "/status:0/from:1";
}
function __setCurSelectedToBeInformed() {
	var checkboxes;
	checkboxes = document.getElementsByName("data[ViewAgent][selected]");
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
		document.getElementById("linkInform_").href + "/from:1"
			+ "/ids:" + document.getElementById("hidCurSelectedToBeInformed").value
			+ "/notes:" + document.getElementById("txtNotes").value;
}
function __checkAll() {
	var checkboxes;
	checkboxes = document.getElementsByName("data[ViewAgent][selected]");
	for (var i = 0; i < checkboxes.length; i++) {
		checkboxes[i].checked = document.getElementById("checkboxAll").checked;
	}
}
</script>

<div style="margin-bottom:3px">
<?php
if (in_array($userinfo['role'], array(0, 1))) {//means an administrator or an office
	//echo $form->button($userinfo['role'] == 0 ? 'Add Agent' : 'Request New Agent',
	echo $form->button('Add Agent',
		array(
			'onclick' => 'javascript:location.href="' .
				$html->url(array('controller' => 'trans', 'action' => 'regagent')) . '"',
			'style' => 'width:160px;'
		)
	);
}
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
	<th><b><?php echo $exPaginator->sort('Username', 'ViewAgent.username4m'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('Password', 'ViewAgent.originalpwd'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('Office', 'ViewAgent.officename'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('Registered', 'ViewAgent.regtime'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('Last Login', 'ViewAgent.lastlogintime'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('Login Times', 'ViewAgent.logintimes'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('Status', 'ViewAgent.status'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('Campaigns', 'ViewAgent.campaigns'); ?></b></th>
	<th><b>Operation</b></th>
	<th>
	<?php
	echo $html->link(
		$html->image('viewit.png', array('border' => 0)),
		array('controller' => 'trans', 'action' => 'lstchatlogs'),
		array('title' => 'Click to all the chat logs.'),
		false, false
	);
	?>
	</th>
</tr>
</thead>
<?php
$i = 0;
foreach ($rs as $r):
?>
<tr <?php echo $i % 2 == 0? '' : 'class="odd"'; ?>>
	<td>
	<?php
	echo $form->checkbox('ViewAgent.selected',
		array('value' => $r['ViewAgent']['id'],
			'style' => 'border:0px;width:16px;',
			'onclick' => 'javascript:__setActSusLink();'
		)
	);
	echo '<font size="1">' . ($i + 1 + $limit * ($paginator->current() - 1)) . '</font>';
	?>
	</td>
	<td><?php echo $r['ViewAgent']['username']; ?></td>
	<td><?php echo $r['ViewAgent']['originalpwd']; ?></td>
	<td><?php echo $r['ViewAgent']['officename']; ?></td>
	<td><?php echo $r['ViewAgent']['regtime']; ?></td>
	<td>
	<?php
	$lastintm = $r['ViewAgent']['lastlogintime'];
	echo empty($lastintm) ? '-' : $lastintm;
	?>
	</td>
	<td align="center">
	<?php
	if ($r['ViewAgent']['logintimes'] == 0) {
		echo $r['ViewAgent']['logintimes'];
	} else {
		echo $html->link(
			$r['ViewAgent']['logintimes'] . '&nbsp;' . $html->image('iconList.gif', array('border' => 0)),
			array('controller' => 'trans', 'action' => 'lstlogins', 'id' => $r['ViewAgent']['id']),
			array('title' => 'Click to the login logs'), false, false
		);
	}
	?>
	</td>
	<td><?php echo $status[$r['ViewAgent']['status']]; ?></td>
	<td align="center">
	<?php
	echo $html->link(
		$r['ViewAgent']['campaigns'] . '&nbsp;' . $html->image('iconList.gif', array('border' => 0)),
		array('controller' => 'links', 'action' => 'lstcampaigns', 'id' => $r['ViewAgent']['id']),
		array('title' => 'Click to the campaigns.'), false, false
	);
	?>
	</td>
	<td align="center">
	<?php
	echo $html->link(
		$html->image('iconEdit.png', array('border' => 0, 'width' => 16, 'height' => 16)) . '&nbsp;',
		array('controller' => 'trans', 'action' => 'updagent', 'id' => $r['ViewAgent']['id']),
		array('title' => 'Click to edit this record.'),
		false, false
	);
	echo $html->link(
		$html->image('iconActivate.png', array('border' => 0, 'width' => 16, 'height' => 16)) . '&nbsp;',
		array('controller' => 'trans', 'action' => 'activatem', 'ids' => $r['ViewAgent']['id'], 'status' => 1, 'from' => 1),
		array('title' => 'Click to activate the user.'),
		false, false
	);
	echo $html->link( 
		$html->image('iconSuspend.png', array('border' => 0, 'width' => 16, 'height' => 16)) . '&nbsp;',
		array('controller' => 'trans', 'action' => 'activatem', 'ids' => $r['ViewAgent']['id'], 'status' => 0, 'from' => 1),
		array('title' => 'Click to suspend the user.'),
		"Are you sure?", false
	);
	?>
	</td>
	<td align="center">
	<?php
	echo $html->link(
		$html->image('chatlogs.png', array('border' => 0)),
		array('controller' => 'trans', 'action' => 'lstchatlogs', 'id' => $r['ViewAgent']['id']),
		array('title' => 'Click to the chat logs.'),
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
	"Are you sure?", false
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
<script type="text/javascript">
jQuery(":checkbox").attr({style: "border:0px;width:16px;vertical-align:middle;"}); 
</script>
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<?php
echo $this->element('paginationblock');
?>
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->