<?php
$userinfo = $session->read('Auth.Account');
//echo str_replace("\n", "<br>", print_r($rs[0], true));
?>
<h1>Click Logs</h1>

<?php
echo $this->element('timezoneblock');
?>

<div style="width:100%;margin-top:5px;" id="search">
<?php
echo $form->create(null, array('controller' => 'links', 'action' => 'lstclickouts'));
?>
<table style="width:100%">
<caption>
<?php echo $html->image('iconSearch.png', array('style' => 'width:16px;height:16px;')) . 'Search'; ?>
</caption>
<tr>
	<td class="search-label" style="width:105px;">Office:</td>
	<td>
		<div style="float:left;margin-right:20px;">
		<?php
			if ($userinfo['role'] != 2) {
				echo $form->input('Stats.companyid',
					array('label' => '',
						'options' => $coms, 'type' => 'select',
						'value' => $selcom,
						'style' => 'width:110px;'
					)
				);
				echo $ajax->observeField('StatsCompanyid',
					array(
						'url' => array('controller' => 'stats', 'action' => 'switchagent'),
						'update' => 'StatsAgentid',
						'loading' => 'Element.hide(\'divAgentid\');Element.show(\'divAgentidLoading\');',
						'complete' => 'Element.show(\'divAgentid\');Element.hide(\'divAgentidLoading\');',
						'frequency' => 0.2
					)
				);
			} else {
				echo $form->input('Stats.companyid',
					array('label' => '',
						'type' => 'hidden',
						'value' => $selcom
					)
				);
				echo $coms[$selcom];
			}
		?>
		</div>
	</td>
	<td class="search-label" style="width:65px;">Agent:</td>
	<td>
		<div style="float:left;margin-right:20px;">
		<?php
			if ($userinfo['role'] != 2) {
				echo $form->input('Stats.agentid',
					array('label' => '',
						'options' => $ags, 'type' => 'select',
						'value' => $selagent,
						'style' => 'width:110px;',
						'div' => array('id' => 'divAgentid')
					)
				);
			} else {
				echo $form->input('Stats.agentid',
					array('label' => '',
						'type' => 'hidden',
						'value' => $selagent
					)
				);
				echo $ags[$selagent];
			}
		?>
		</div>
		<div id="divAgentidLoading" style="float:left;width:100px;margin-right:20px;display:none;">
		<?php echo $html->image('iconAttention.gif') . '&nbsp;Loading...'; ?>
		</div>
	</td>
	<td class="search-label" style="width:65px;">Site:</td>
	<td>
		<?php
		echo $form->input('Stats.siteid',
			array('label' => '',
				'options' => $sites, 'type' => 'select',
				'value' => $selsite,
				'style' => 'width:110px;',
				'div' => array('id' => 'divSiteid')
			)
		);
		?>
	</td>
	<td class="search-label" style="width:65px;">IP From:</td>
	<td>
		<?php
		echo $form->input('ViewClickout.fromip',
			array(
				'label' => '',
				'value' => $fromip,
				'style' => 'width: 130px;',
				'div' => array('id' => 'divIpfrom')
			)
		);
		?>
	</td>
</tr>
<tr>
	<td class="search-label" style="width:65px;">Date:</td>
	<td colspan="5">
		<div style="float:left;width:50px;">
			<b>Start:</b>
		</div>
		<div style="float:left;margin-right:20px;">
		<?php
		echo $form->input('ViewClickout.startdate',
			array('label' => '', 'id' => 'datepicker_start', 'style' => 'width:80px;', 'value' => $startdate));
		?>
		</div>
		<div style="float:left;width:40px;">
			<b>End:</b>
		</div>
		<div style="float:left;margin-right:46px;">
		<?php
		echo $form->input('ViewClickout.enddate',
			array('label' => '', 'id' => 'datepicker_end', 'style' => 'width:80px', 'value' => $enddate));
		?>
		</div>
	</td>
	<td colspan="2">
	<?php
	echo $form->submit('Search', array('style' => 'width:110px;'));
	?>
	</td>
	
</tr>
</table>
<?php
echo $form->end();
?>
</div>
<br/>

<table style="width:100%">
<caption>
Start Date:<?php echo $startdate; ?>&nbsp;&nbsp;End Date:<?php echo $enddate; ?>&nbsp;&nbsp;|&nbsp;&nbsp;
Office:<?php echo $coms[$selcom]; ?>&nbsp;&nbsp;Agent:<?php echo $ags[$selagent]; ?>
<br/>
<font color="red" size="2"><b>(Click on IP to see an address for a world map, where your link was opened.)</b></font>
</caption>
<thead>
<tr>
	<th><b><?php echo $exPaginator->sort('Office', 'ViewClickout.officename'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('Agent', 'ViewClickout.username'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('Site', 'ViewClickout.sitename'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('Type', 'ViewClickout.typename'); ?></b></th>
	<th><b>Link</b></th>
	<th><b><?php echo $exPaginator->sort('Click Time', 'ViewClickout.clicktime'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('IP From', 'ViewClickout.fromip'); ?></b></th>
	<th><b><?php echo $exPaginator->sort('Referer', 'ViewClickout.referer'); ?></b></th>
</tr>
</thead>
<?php
$i = 0;
foreach ($rs as $r):
?>
<tr <?php echo $i % 2 == 0 ? '' : 'class="odd"'; ?>>
	<td><?php echo $r['ViewClickout']['officename']; ?></td>
	<td><?php echo $r['ViewClickout']['username']; ?></td>
	<td><?php echo $r['ViewClickout']['sitename']; ?></td>
	<td><?php echo $r['ViewClickout']['typename']; ?></td>
	<td>
	<?php
		if ($r['ViewClickout']['typename'] != '') {
			echo 'http://'. $_SERVER['HTTP_HOST']
				. $html->url(array('controller' => 'trans', 'action' => 'go'))
				. '/' . $r['ViewClickout']['siteid']
				. '/' . $r['ViewClickout']['typeid']
				. '/' . $r['ViewClickout']['username'];
		} else {
			echo '-';
		}
	?>
	</td>
	<td><?php echo $r['ViewClickout']['clicktime']; ?></td>
	<td>
		<a href="http://whatismyipaddress.com/ip/<?php echo $r['ViewClickout']['fromip']; ?>" target="findip_window">
			<?php echo $r['ViewClickout']['fromip']; ?>
		</a>
	</td>
	<td><?php echo $r['ViewClickout']['referer']; ?></td>
</tr>
<?php
$i++;
endforeach;
?>
</table>

<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
<?php
echo $this->element('paginationblock');
?>
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery(function() {
		jQuery('#datepicker_start').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});
	});
});
</script>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery(function() {
		jQuery('#datepicker_end').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});
	});
});
</script>
