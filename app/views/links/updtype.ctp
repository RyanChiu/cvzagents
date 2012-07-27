<h1>Update Type</h1>
<br/>
<?php
//echo print_r($results, true);
$userinfo = $session->read('Auth.Account');
echo $form->create(null, array('controller' => 'links', 'action' => 'updtype'));
?>
<table style="width:100%">
	<caption>Fields marked with an asterisk (*) are required.</caption>
	<tr>
		<td width="15%">Type Name:</td>
		<td>
		<div style="float:left">
		<?php
		echo $form->input('Type.typename', array('label' => '', 'style' => 'width:590px;'));
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td width="15%">Type URL:</td>
		<td>
		<div style="float:left">
		<?php
		echo $form->input('Type.url', array('label' => '', 'style' => 'width:590px;'));
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<!--
	<tr>
		<td width="15%">Payout:</td>
		<td>
		<div style="float:left">
		<?php
		echo $form->input('Type.price', array('label' => '', 'style' => 'width:590px;'));
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td width="15%">Earning:</td>
		<td>
		<div style="float:left">
		<?php
		echo $form->input('Type.earning', array('label' => '', 'style' => 'width:590px;'));
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	-->
	<tr>
		<td>
		<?php
		echo 'Activated' . $form->checkbox(
			'Type.status',
			array('style' => 'border:0px;width:16px;')
		);
		?>
		</td>
		<td>
		<?php echo $form->submit('Update', array('style' => 'width:112px;')); ?>
		</td>
	</tr>
</table>
<?php
echo $form->input('Type.id', array('type' => 'hidden'));
echo $form->end();
?>
