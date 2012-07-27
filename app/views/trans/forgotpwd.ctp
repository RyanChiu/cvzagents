<center>
<b><font color="red"><?php $session->flash('auth'); ?></font></b>
<font color="red"><?php $session->flash(); ?></font>
</center>
<?php
echo $form->create(null, array('controller' => 'trans', 'action' => 'forgotpwd'));
?>
<table style="width:100%">
<tr>
	<td colspan="2" align="center" style="color:white;">
		<b>Forgot your password?</b>
		<br/>
		<b>
		Just enter your username&amp;email address below,
		and the password will be sent.</b>
	</td>
</tr>
<tr><td>&nbsp;</td><td></td></tr>
<tr>
<td align="right" style="width:45%;color:white;"><b>Your Username:</b></td>
<td align="left">
<?php
echo $form->input('Forgot.username', array('label' => '', 'style' => 'width:160px;'));
?>
</td>
</tr>
<tr><td>&nbsp;</td><td></td></tr>
<tr>
<td align="right" style="color:white;"><b>Email Address:</b></td>
<td align="left">
<?php
echo $form->input('Forgot.email', array('label' => '', 'style' => 'width:160px;'));
?>
</td>
</tr>
<tr><td>&nbsp;</td><td></td></tr>
<tr>
<td align="center" colspan="2">
<?php
echo $form->submit('iconForgotpwd.png', array('style' => 'border:0px;width:67px;height:43px;'));
echo '<br/>'
	. $html->link(
		'I didn\'t forget, let me log in!',
		array('controller' => 'trans', 'action' => 'login'),
		null, false, false
	);
?>
</td>
</tr>
<tr>
<td align="center" colspan="2">&nbsp;</td>
</tr>
</table>
<?php
echo $form->end();
?>
