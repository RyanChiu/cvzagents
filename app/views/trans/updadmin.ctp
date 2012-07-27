<h1>Profile</h1>
<br/>
<?php
//echo print_r($rs, true);
$userinfo = $session->read('Auth.Account');
echo $form->create(null, array('controller' => 'trans', 'action' => 'updadmin'));
?>
<table style="width:100%">
	<caption>Fields marked with an asterisk (*) are required.</caption>
	<tr>
		<td>Password : </td>
		<td>
		<div style="float:left">
		<?php
		echo $form->input('Account.password', array('label' => '', 'type' => 'password', 'style' => 'width:390px;'));
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td>Confirm password :</td>
		<td>
		<div style="float:left">
		<?php
		echo $form->input('Account.originalpwd', array('label' => '', 'type' => 'password', 'style' => 'width:390px;'));
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td>Email Address :</td>
		<td>
		<div style="float:left">
		<?php
		echo $form->input('Admin.email', array('label' => '', 'style' => 'width:390px;'));
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td></td>
		<td><?php echo $form->submit('Update', array('style' => 'width:112px;')); ?></td>
	</tr>
</table>
<?php
echo $form->input('Account.id', array('type' => 'hidden'));
echo $form->input('Admin.id', array('type' => 'hidden'));
echo $form->end();
?>
