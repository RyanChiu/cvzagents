<?php
$userinfo = $session->read('Auth.Account');
?>
<h1>Sites</h1>
<br/>
<div style="margin-bottom:3px">
<?php
echo $form->button('Add Site',
	array(
		'onclick' => 'javascript:location.href="'
			. $html->url(array('controller' => 'links', 'action' => 'addsite')) . '"',
		'style' => 'width:160px;'
	)
);
?>
</div>
<table style="width:100%">
	<thead>
	<tr>
		<th><?php echo $exPaginator->sort('Campaigns', 'ViewSite.hostname') . '<br/><font size="1">(for admin only)</font>'; ?></th>
		<th><?php echo $exPaginator->sort('Site Name', 'ViewSite.sitename') . '<br/><font size="1">(for office or agent)</font>'; ?></th>
		<th><?php echo $exPaginator->sort('Site Type', 'ViewSite.type'); ?></th>
		<?php
		if ($userinfo['id'] == 2) {
		?>
		<th><?php echo $exPaginator->sort('Abbreviation', 'ViewSite.abbr') . '<br/><font size="1">(for admin only)</font>'; ?></th>
		<?php
		}
		?>
		<th><?php echo $exPaginator->sort('Sale Types', 'ViewSite.typestotal'); ?></th>
		<th><?php echo $exPaginator->sort('Status', 'ViewSite.status'); ?></th>
		<th>Change</th>
	</tr>
	</thead>
	<?php
	foreach ($rs as $r) :
	?>
	<tr>
		<td><?php echo $r['ViewSite']['hostname'];	?></td>
		<td><?php echo $r['ViewSite']['sitename'];	?></td>
		<td><?php echo $r['ViewSite']['type'];	?></td>
		<?php
		if ($userinfo['id'] == 2) {
		?>
		<td><?php echo $r['ViewSite']['abbr'];	?></td>
		<?php
		}
		?>
		<td>
		<?php
		echo $html->link(
			$r['ViewSite']['typestotal'] . '&nbsp;' . $html->image('iconList.gif', array('border' => 0)),
			array('controller' => 'links', 'action' => 'lsttypes', 'id' => $r['ViewSite']['id']),
			array('title' => 'Click to view the types of the site.'),
			false, false
		);
		?>
		</td>
		<td>
		<?php
		echo in_array($r['ViewSite']['status'], array(0, 1)) ? $status[$r['ViewSite']['status']] : $status[0];
		?>
		</td>
		<td align="center">
		<?php
		echo $html->link(
			$html->image('iconEdit.png', array('border' => 0, 'width' => 16, 'height' => 16)) . '&nbsp;',
			array('controller' => 'links', 'action' => 'updsite', 'id' => $r['ViewSite']['id']),
			array('title' => 'Click to edit this site.'),
			false, false
		);
		echo $html->link(
			$html->image('iconActivate.png', array('border' => 0, 'width' => 16, 'height' => 16)) . '&nbsp;',
			array('controller' => 'links', 'action' => 'activatesite', 'id' => $r['ViewSite']['id']),
			array('title' => 'Click to activate the site.'),
			false, false
		);
		echo $html->link(
			$html->image('iconSuspend.png', array('border' => 0, 'width' => 16, 'height' => 16)) . '&nbsp;',
			array('controller' => 'links', 'action' => 'suspendsite', 'id' => $r['ViewSite']['id']),
			array('title' => 'Click to suspend the site.'),
			false, false
		);
		?>
		</td>
	</tr>
	<?php
	endforeach;
	?>
</table>
