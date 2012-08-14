<?php
//echo print_r($results, true);
$userinfo = $session->read('Auth.Account');
$action = 'updagent';
$submittxt = 'Update';
$title = 'Update Agent';
if ($userinfo['role'] == 1) {
	$action = 'requestchg';
	$submittxt = 'Send Ruquest';
	$title = 'Request For Updating Agent';
}
?>
<h1><?php echo $title; ?></h1>
<?php
echo $form->create(null, array('controller' => 'trans', 'action' => $action));
if ($userinfo['role'] == 1) {
	echo $form->input('Requestchg.role', array('type' => 'hidden', 'value' => '2'));
	echo $form->input('Requestchg.type', array('type' => 'hidden', 'value' => 'upd'));
	echo $form->input('Requestchg.from', array('type' => 'hidden', 'value' => $curcom['Company']['manemail']));
	echo $form->input('Requestchg.offiname', array('type' => 'hidden', 'value' => $curcom['Company']['officename']));
}
?>
<table style="width:100%">
	<caption>Fields marked with an asterisk (*) are required.</caption>
	<tr>
		<td width="248px">Office : </td>
		<td>
		<div style="float:left">
		<?php
		if ($userinfo['role'] == 0) {// means an administrator
			echo $form->select('Agent.companyid', $cps, null, array('style' => 'width:390px;'));
		} else if ($userinfo['role'] == 1 ) {// means an office
			echo $form->input('Agent.companyshadow',
				array(
					'label' => '',
					'style' => 'width:390px;border:0px;background:transparent;',
					'readonly' => 'readonly',
					'value' => $cps[$results['Agent']['companyid']]
				)
			);
			echo $form->input('Agent.companyid', array('type' => 'hidden'));
		} else if ($userinfo['role'] == 2 ) {// means an agent
			echo $form->input('Agent.companyshadow',
				array(
					'label' => '',
					'style' => 'width:390px;border:0px;background:transparent;',
					'readonly' => 'readonly',
					'value' => $cps[$results['Agent']['companyid']]
				)
			);
			echo $form->input('Agent.companyid', array('type' => 'hidden'));
		}
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
		<!--  
		<td rowspan="16" align="center"><?php //echo $html->image('iconDollarsKey.png', array('width' => '160')); ?></td>
		-->
	</tr>
	<tr>
		<td>First Name : </td>
		<td>
		<div style="float:left">
		<?php
		if ($userinfo['role'] == 2) {//means an agent
			echo $form->input('Agent.ag1stname', array('label' => '', 'style' => 'width:390px;border:0px;background:transparent;', 'readonly' => 'readonly'));
		} else {
			echo $form->input('Agent.ag1stname', array('label' => '', 'style' => 'width:390px;'));
		}
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td>Last Name : </td>
		<td>
		<div style="float:left">
		<?php
		if ($userinfo['role'] == 2) {//means an agent
			echo $form->input('Agent.aglastname', array('label' => '', 'style' => 'width:390px;border:0px;background:transparent;', 'readonly' => 'readonly'));
		} else {
			echo $form->input('Agent.aglastname', array('label' => '', 'style' => 'width:390px;'));
		}
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td>Email : </td>
		<td>
		<div style="float:left">
		<?php
		if ($userinfo['role'] == 2) {//means an agent
			echo $form->input('Agent.email', array('label' => '', 'style' => 'width:390px;border:0px;background:transparent;', 'readonly' => 'readonly'));
		} else {
			echo $form->input('Agent.email', array('label' => '', 'style' => 'width:390px;'));
		}
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td>Username : </td>
		<td>
		<div style="float:left">
		<?php
		if ($userinfo['role'] == 2) {//means an agent
			echo $form->input('Account.username', array('label' => '', 'style' => 'width:390px;border:0px;background:transparent;', 'readonly' => 'readonly'));
		} else {
			echo $form->input('Account.username', array('label' => '', 'style' => 'width:390px;'));
		}
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td>Password : </td>
		<td>
		<div style="float:left">
		<?php
		echo $form->input('Account.password', array('label' => '', 'style' => 'width:390px;', 'type' => 'password'));
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td>Confirm Password : </td>
		<td>
		<div style="float:left">
		<?php
		echo $form->input('Account.originalpwd', array('label' => '', 'style' => 'width:390px;', 'type' => 'password'));
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td>Street Name &amp; Number : </td>
		<td>
		<div style="float:left">
		<?php
		echo $form->input('Agent.street', array('label' => '', 'style' => 'width:390px;'));
		?>
		</div>
		</td>
	</tr>
	<tr>
		<td>City : </td>
		<td>
		<div style="float:left">
		<?php
		echo $form->input('Agent.city', array('label' => '', 'style' => 'width:390px;'));
		?>
		</div>
		</td>
	</tr>
	<tr>
		<td>State &amp; Zip : </td>
		<td>
		<div style="float:left">
		<?php
		echo $form->input('Agent.state', array('label' => '', 'style' => 'width:390px;'));
		?>
		</div>
		</td>
	</tr>
	<tr>
		<td>Country : </td>
		<td>
		<div style="float:left">
		<?php
		echo $form->select('Agent.country', $cts, $results['Agent']['country'], array('style' => 'width:390px;'));
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td>Instant Messenger Contact : <br/><font size="1">(AIM, Yahoo, Skype, MSN, ICQ)</font></td>
		<td>
		<div style="float:left">
		<?php
		echo $form->input('Agent.im', array('label' => '', 'style' => 'width:390px;'));
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr>
		<td>Cell NO. : </td>
		<td>
		<div style="float:left">
		<?php
		echo $form->input('Agent.cellphone', array('label' => '', 'style' => 'width:390px;'));
		?>
		</div>
		<div style="float:left"><font color="red">*</font></div>
		</td>
	</tr>
	<tr> 
		<td>Associated Sites: </td> 
		<td> 
		<?php 
		$selsites = array_diff($sites, $exsites); 
		$selsites = array_keys($selsites); 
		echo $form->select('SiteExcluding.siteid', 
		  $sites, 
		  $selsites, 
		  array( 
		    'multiple' => 'checkbox', 
		  ) 
		); 
		if ($userinfo['role'] == 2) {//means an agent
		?>
			<div id="msgbox_nochange" style="display:none;float:left;background-color:#ffffcc;">
			<font color="red">
			Sorry, you can't do this.If you want to, please contact your office or administrator.
			</font>
			</div>
			<script type="text/javascript">
			jQuery(":checkbox").click(
					function () {
						jQuery("#msgbox_nochange").show("normal");
						return false;
					}
			);
			jQuery("#msgbox_nochange").click(
					function () {
						jQuery(this).toggle("normal");
					}
			);
			</script>
		<?php	
		}
		?> 
		</td> 
	</tr>
	<tr>
		<td>
		<label id="labelUAS">Activated</label>
		<?php
		if ($userinfo['role'] == 2) {//means an agent
			echo $form->input('Account.status', array('type' => 'hidden'));
		} else {
			echo $form->checkbox('Account.status', array('style' => 'border:0px;width:16px;'));
		}
		?>
		</td>
		<td>
		<?php
		echo $form->submit($submittxt, array('style' => 'width:112px;'));
		?>
		</td>
	</tr>
</table>
<script type="text/javascript"> 
jQuery(":checkbox").attr({
	style: "border: 0px; width: 16px; margin-left: 2px; vertical-align: middle;"
});

<?php if ($userinfo['role'] == 2) {//means an agent ?>
	jQuery("#labelUAS").hide();
<?php } else {?>
	jQuery("#labelUAS").show();
<?php } ?>

jQuery("#AccountStatus").click(function() {
	if (jQuery("#AccountStatus").attr("checked")) {
		jQuery("#AccountStatus").val(1);
	} else {
		jQuery("#AccountStatus").val(0);
	}
});

if (jQuery("#AccountStatus").val() == "-1") {
	jQuery("#labelUAS").text("Approved");
	jQuery("#AccountStatus").attr("checked", false);
	jQuery("#AccountStatus").val(-1);
	jQuery("#AccountStatus_").val(-1);
} else {
	jQuery("#labelUAS").text("Activated");
}
</script>
<?php
echo $form->input('Account.id', array('type' => 'hidden'));
echo $form->input('Account.role', array('type' => 'hidden', 'value' => '2'));//the value 2 as being an agent
echo $form->input('Agent.id', array('type' => 'hidden'));
echo $form->end();
?>
