<?php
//echo print_r($rs, true);
App::import('vendor', 'ExtraKits', array('file' => 'extrakits.inc.php'));
$userinfo = $session->read('Auth.Account');
?>
<h1>Link Codes</h1>
<br/>
<div style="float:right">
<?php
if ($userinfo['role'] == 0) {//means an administrator
	echo $html->link(
		'Configure Sites...',
		array('controller' => 'links', 'action' => 'lstsites')
	);
}
?>
</div>
<!--  
<small>(You're from:<?php //echo __getclientip(); ?>, and you'll be <?php //echo __isblocked(__getclientip()) ? 'blocked.' : 'passed.'; ?>)</small>
-->
<?php
echo $form->create(null, array('controller' => 'links', 'action' => 'lstlinks'))
?>
<table style="width:100%">
<caption>
	Please Select An Agent &amp; Generate Link Codes
	<br/>
	<font style="color:red;">
	<?php
	if (!empty($suspsites)) {
		echo '>>Site "' . implode(",", $suspsites) . '"' . (count($suspsites) > 1 ? ' are' : ' is')
			. ' suspended for now.';
	}
	?>
	</font>
</caption>
<tr>
	<td width="31%" align="right">
	<?php
	echo $form->input('Site.id',
		array('options' => $sites, 'style' => 'width:170px;', 'label' => 'Site:', 'type' => 'select')
	);
	?>
	</td>
	<td width="40%" align="center">
	<?php
	echo $form->input('ViewAgent.id',
		array('options' => $ags, 'style' => 'width:290px;', 'label' => 'Agent:', 'type' => 'select')
	);
	?>
	</td>
	<td width="29%">
	<?php
	echo $form->submit('Generate Link Codes', array('style' => 'width:180px;'));
	?>
	</td>
</tr>
</table>
<?php
echo $form->end();
?>

<br/>
<?php
if (!empty($rs)) {
?>
	<table style="width:100%;border:0;">
	<?php
	foreach ($rs as $r):
		if (array_key_exists('ViewLink', $r)) {
	?>
		<tr>
			<td align="center">
			<?php
			echo $r['ViewLink']['sitename'] . '_' . $r['ViewLink']['typename'] . ':&nbsp;&nbsp;&nbsp;';
			echo '<b>';
			echo $html->url(
				array('controller' => 'trans', 'action' => 'golink',
					'to' => __codec($r['ViewLink']['id'] . ',' . $r['ViewLink']['agentid'], 'E')
				),
				true
			);
			echo '</b>';
			?>
			</td>	
		</tr>
	<?php
		} else if (array_key_exists('AgentSiteMapping', $r)) {
			foreach ($types as $type) {
	?>
		<tr>
			<td align="center">
			<?php
			echo $sites[$r['AgentSiteMapping']['siteid']] . '_' . $type['Type']['typename'] . ':&nbsp;&nbsp;&nbsp;';
			echo '<b>';
			echo $html->url(array('controller' => 'trans', 'action' => 'go'), true) . '/'
				. $r['AgentSiteMapping']['siteid'] . '/'
				. $type['Type']['id']. '/'
				. $ags[$r['AgentSiteMapping']['agentid']];
			echo '</b>';
			?>
			</td>
		</tr>
	<?php
			}
		}
	endforeach;
	?>
	</table>
<?php
}
?>
