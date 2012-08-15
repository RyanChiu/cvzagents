<?php
$userinfo = $session->read('Auth.Account');
$role = -1;//means everyone
if ($userinfo) {
	$role = $userinfo['role'];
}

$menuitemscount = 0;
$curmenuidx = 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>
<title><?php echo $title_for_layout; ?>
</title>
<?php
/*for default whole page layout*/
echo $html->css('main');

/*for tables*/
echo $html->css('tables');

/*for jQuery datapicker*/
echo $html->css('jQuery/Datepicker/green');
echo $javascript->link('jQuery/Datepicker/jquery-1.3.2.min');
echo $javascript->link('jQuery/Datepicker/jquery-ui-1.7.custom.min');

/*for self-developed zToolkits*/
echo $javascript->link('zToolkits');

/*for DropDownTabs*/
//echo $html->css('DropDownTabs/glowtabs');
echo $html->css('DropDownTabs/halfmoontabs');
echo $javascript->link('DropDownTabs/dropdowntabs');

/*for TinyDropdown*/
echo $html->css('TinyDropdown/style');
echo $javascript->link('TinyDropdown/script');

/*for CKEditor*/
echo $javascript->link('ckeditor/ckeditor');

/*for fancybox*/
echo $html->css('fancybox/jquery.fancybox-1.3.3', null, array('media' => 'screen'));
echo $javascript->link('fancybox/jquery.fancybox-1.3.3.pack');

/*for cufon*/
echo $javascript->link('cufon/cufon-yui');
echo $javascript->link('cufon/Showcard_Gothic_400.font');

/*for AJAX*/
echo $javascript->link('ajax/prototype');
echo $javascript->link('ajax/scriptaculous');

echo $scripts_for_layout;

?>
<script type="text/javascript">
	Cufon.replace(".header");
</script>
</head>
<body bgcolor="#ffffff">
	<div class="wrapper">
		<!-- Start Border-->
		<div id="border">
			<!-- Start Header -->
			<div class="header">
				<div style="float: left; padding: 0px 0px 0px 16px;">
					<p>&nbsp;</p>
					<p>
						<span lang="EN-US"
							style="font-size: 20.0pt; line-height: 115%; color: #FFC000">THE</span><span
							lang="EN-US"
							style="font-size: 72.0pt; line-height: 115%; color: red">Z</span><span
							lang="EN-US"
							style="font-size: 48.0pt; line-height: 115%; color: #B2A1C7; mso-themecolor: accent4; mso-themetint: 153">o</span><span
							lang="EN-US"
							style="font-size: 36.0pt; line-height: 115%; color: #FFC000">o</span><span
							lang="EN-US"
							style="font-size: 36.0pt; line-height: 115%; color: #92D050">
							@</span>
					</p>
				</div>
				<div style="float: right; padding: 0px 0px 0px 0px;">
					<p>
						<span lang="EN-US"
							style="font-size: 72.0pt; line-height: 115%; color: red">C</span><span
							lang="EN-US"
							style="font-size: 72.0pt; line-height: 115%; color: #B2A1C7; mso-themecolor: accent4; mso-themetint: 153">h</span><span
							lang="EN-US"
							style="font-size: 72.0pt; line-height: 115%; color: #FFC000">a</span><span
							lang="EN-US"
							style="font-size: 72.0pt; line-height: 115%; color: red">t</span><span
							lang="EN-US"
							style="font-size: 36.0pt; line-height: 115%; color: #92D050">V</span><span
							lang="EN-US"
							style="font-size: 36.0pt; line-height: 115%; color: #95B3D7; mso-themecolor: accent1; mso-themetint: 153">a</span><span
							lang="EN-US"
							style="font-size: 72.0pt; line-height: 115%; color: #92D050">z</span><span
							lang="EN-US"
							style="font-size: 48.0pt; line-height: 115%; color: #B2A1C7; mso-themecolor: accent4; mso-themetint: 153">o</span><span
							lang="EN-US"
							style="font-size: 28.0pt; line-height: 115%; color: #FFC000">o
						</span>
						<span lang="EN-US" style="font-size: 12.0pt; line-height: 115%; color: #ffc000">International</span>
					</p>
				</div>
			</div>
			<!-- End Header -->
			<!-- Start Navigation Bar -->
			<div id="nav-bar">
				<!--the level menu -->
				<div id="moonmenu" class="halfmoon">
					<ul>
						<?php
						if ($role != -1) {//means everyone
							$menuitemscount++;
							//if cur route matches this menu item, then set the number to inform the js code
							if (strpos($this->here, 'trans') === false) {
							} else {
								$curmenuidx = $menuitemscount - 1;
							}
						?>
						<li>
						<?php
						echo $html->link('<span><font>HOME</font></span>',
								array('controller' => 'trans', 'action' => 'index'),
								null, false, false);
						?>
						</li>
						<?php
						}
						?>
						<?php
						if ($role == 0) {//means an administrator
							$menuitemscount++;
							//if cur route matches this menu item, then set the number to inform the js code
							if (strpos($this->here, 'addnews') === false
									&& strpos($this->here, 'updalerts') === false) {
							} else {
								$curmenuidx = $menuitemscount - 1;
							}
						?>
						<li>
						<?php
						echo $html->link('<span><font>NEWS</font></span>',
								array('controller' => 'trans', 'action' => 'addnews'),
								array('rel' => 'dropmenu_admin_news'),
								false, false);
						?>
						</li>
						<?php
						}
						?>
						<?php
						if ($role == 0) {//means an administrator
							$menuitemscount++;
							//if cur route matches this menu item, then set the number to inform the js code
							if (strpos($this->here, 'lstcompanies') === false
									&& strpos($this->here, 'updcompany') === false
									&& strpos($this->here, 'regcompany') === false) {
							} else {
								$curmenuidx = $menuitemscount - 1;
							}
						?>
						<li>
						<?php
						echo $html->link('<span><font>OFFICE</font></span>',
								array('controller' => 'trans', 'action' => 'lstcompanies', 'id' => -1),
								array('rel' => 'dropmenu_admin_company'),
								false, false);
						?>
						</li>
						<?php
						}
						?>
						<?php
						if ($role == 0) {//means an administrator
							$menuitemscount++;
							//if cur route matches this menu item, then set the number to inform the js code
							if (strpos($this->here, 'lstagents') === false && strpos($this->here, 'updagent') === false
									&& strpos($this->here, 'regagent') === false) {
							} else {
								$curmenuidx = $menuitemscount - 1;
							}
						?>
						<li>
						<?php
						echo $html->link('<span><font>AGENT</font></span>',
								array('controller' => 'trans', 'action' => 'lstagents', 'id' => -1),
								array('rel' => 'dropmenu_admin_agent'),
								false, false);
						?>
						</li>
						<?php
						}
						?>
						<?php
						if ($role == 1) {//means an office
							$menuitemscount++;
							//if cur route matches this menu item, then set the number to inform the js code
							if (strpos($this->here, 'lstagents') === false && strpos($this->here, 'updagent') === false
									&& strpos($this->here, 'regagent') === false
									&& strpos($this->here, 'requestchg') === false
									&& strpos($this->here, 'lstchatlogs') === false) {
							} else {
								$curmenuidx = $menuitemscount - 1;
							}
						?>
						<li>
						<?php
						echo $html->link('<span><font>AGENT</font></span>',
								array('controller' => 'trans', 'action' => 'lstagents', 'id' => $userinfo['id']),
								array('rel' => 'dropmenu_com_agent'),
								false, false);
						?>
						</li>
						<?php
						}
						?>
						<?php
						if ($role == 0) {//means an administrator
							$menuitemscount++;
							//if cur route matches this menu item, then set the number to inform the js code
							if (strpos($this->here, 'lstnewmembers') === false) {
							} else {
								$curmenuidx = $menuitemscount - 1;
							}
						?>
						<li>
						<?php
						echo $html->link('<span><font>NEW MEMBERS</font></span>',
								array('controller' => 'trans', 'action' => 'lstnewmembers'),
								null, false, false);
						?>
						</li>
						<?php
						}
						?>
						<?php
						if ($role != -1) {//means everyone
							$menuitemscount++;
							//if cur route matches this menu item, then set the number to inform the js code
							if (strpos($this->here, 'lstlinks') === false
									&& strpos($this->here, 'lstsites') === false && strpos($this->here, 'addsite') === false
									&& strpos($this->here, 'updsite') === false
									&& strpos($this->here, 'lsttypes') === false && strpos($this->here, 'updtype') === false) {
							} else {
								$curmenuidx = $menuitemscount - 1;
							}
						?>
						<li>
						<?php
						echo $html->link('<span><font>LINKS</font></span>',
								array('controller' => 'links', 'action' => 'lstlinks'),
								array('rel' => ($role == 0 ? 'dropmenu_links' : '')),
								false, false);
						?>
						</li>
						<?php
						}
						?>
						<!--  
						<li>
						<?php
						/*
						$menuitemscount++;
						echo $html->link('<span>LIVE MODELS</span>',
							'#models_div',
							array('rel' => 'dropmenu_onlinemodels', 'class' => 'iml_models'),
							false, false
						);
						*/
						?>
						</li>
						-->
						<?php
						if ($role != -1) {//menas everyone
							$menuitemscount++;
							//if cur route matches this menu item, then set the number to inform the js code
							if (strpos($this->here, 'stats') === false) {
							} else {
								$curmenuidx = $menuitemscount - 1;
							}
						?>
						<li>
						<?php
						echo $html->link('<span><font>STATS</font></span>',
								array('controller' => 'stats', 'action' => 'statscompany', 'clear' => -2),
								null, false, false
						);
						?>
						</li>
						<?php
						}
						?>

						<?php
						if ($role != -1) {//menas everyone
							$menuitemscount++;
							//if cur route matches this menu item, then set the number to inform the js code
							if (strpos($this->here, 'addchatlogs') === false
									&& strpos($this->here, 'lstchatlogs') === false
									&& strpos($this->here, 'lstlogins') === false
									&& strpos($this->here, 'lstclickouts') === false) {
							} else {
								$curmenuidx = $menuitemscount - 1;
							}
						?>
						<li>
						<?php
						echo $html->link('<span><font>LOGS</font></span>',
								"#",
								array('rel' => 'dropmenu_logs'),
								false, false);
						?>
						</li>
						<?php
						}
						?>

						<?php
						if ($role == 1 || $role == 2) {//means an office or an agent
							$menuitemscount++;
							//if cur route matches this menu item, then set the number to inform the js code
							if (strpos($this->here, 'contactus') === false) {
							} else {
								$curmenuidx = $menuitemscount - 1;
							}
						?>
						<li>
						<?php
						echo $html->link('<span><font>GET HELP</font></span>',
								array('controller' => 'trans', 'action' => 'contactus'),
								null, false, false);
						?>
						</li>
						<?php
						}
						?>
						<?php
						if ($role == 0) {//means an administrator
							$menuitemscount++;
							//if cur route matches this menu item, then set the number to inform the js code
							if (strpos($this->here, 'updadmin') === false) {
							} else {
								$curmenuidx = $menuitemscount - 1;
							}
						?>
						<li>
						<?php
						echo $html->link('<span><font>PROFILE</font></span>',
								array('controller' => 'trans', 'action' => 'updadmin'),
								null, false, false);
						?>
						</li>
						<?php
						}
						?>
						<?php
						if ($role == 1) {//means an office
							$menuitemscount++;
							//if cur route matches this menu item, then set the number to inform the js code
							if (strpos($this->here, 'updcompany') === false) {
							} else {
								$curmenuidx = $menuitemscount - 1;
							}
						?>
						<li>
						<?php
						echo $html->link('<span><font>PROFILE</font></span>',
								array('controller' => 'trans', 'action' => 'updcompany', 'id' => $userinfo['id']),
								null, false, false);
						?>
						</li>
						<?php
						}
						?>
						<?php
						if ($role == 2) {//means an agent
							$menuitemscount++;
							//if cur route matches this menu item, then set the number to inform the js code
							if (strpos($this->here, 'updagent') === false) {
							} else {
								$curmenuidx = $menuitemscount - 1;
							}
						?>
						<li>
						<?php
						echo $html->link('<span><font>PROFILE</font></span>',
								array('controller' => 'trans', 'action' => 'updagent', 'id' => $userinfo['id']),
								null, false, false);
						?>
						</li>
						<?php
						}
						?>
						<?php
						if ($role == 0) {//means an administrator
							$menuitemscount++;
							//if cur route matches this menu item, then set the number to inform the js code
							if (strpos($this->here, 'toolbox') === false) {
							} else {
								$curmenuidx = $menuitemscount - 1;
							}
						?>
						<li>
						<?php 
						echo $html->link('<span><font>HOW TO SELL</font></span>',
								"#",
								array('rel' => 'dropmenu_toolbox_admin'),
								false, false);
						?>
						</li>
						<?php 
						} else {//means an office or an agent
							$menuitemscount++;
							//if cur route matches this menu item, then set the number to inform the js code
							if (strpos($this->here, 'toolbox') === false) {
							} else {
								$curmenuidx = $menuitemscount - 1;
							}
						?>
						<li>
						<?php 
						echo $html->link('<span><font>HOW TO SELL</font></span>',
								"#",
								array('rel' => 'dropmenu_toolbox_normal'),
								false, false);
						?>
						</li>
						<?php 
						}
						?>
						<li>
						<?php
						echo $html->link('<span><font>LOGOUT</font></span>',
								array('controller' => 'trans', 'action' => 'logout'),
								null, false, false);
						?>
						</li>
					</ul>
				</div>
				<!--admin drop down menu -->
				<?php
				if ($role == 0) {//means an administrator
				?>
				<div id="dropmenu_admin_news" class="dropmenudiv_e"
					style="width: 70px;">
					<?php
					echo $html->link('<font><b>Alerts</b></font>',
							array('controller' => 'trans', 'action' => 'updalerts'),
							null, false, false
					);
					?>
				</div>
				<div id="dropmenu_links" class="dropmenudiv_e" style="width: 120px;">
					<?php
					echo $html->link('<font><b>Link</b></font>',
							array('controller' => 'links', 'action' => 'lstlinks'),
							null, false, false
					);
					echo $html->link('<font><b>Configure Site</b></font>',
							array('controller' => 'links', 'action' => 'lstsites'),
							null, false, false
					);
					?>
				</div>
				<div id="dropmenu_admin_agent" class="dropmenudiv_e"
					style="width: 130px;">
					<?php
					echo $html->link('<font><b>Manage Agent</b></font>',
							array('controller' => 'trans', 'action' => 'lstagents', 'id' => -1),
							null, false, false
					);
					?>
				</div>
				<div id="dropmenu_admin_company" class="dropmenudiv_e"
					style="width: 135px;">
					<?php
					echo $html->link('<font><b>Manage Office</b></font>',
							array('controller' => 'trans', 'action' => 'lstcompanies', 'id' => -1),
							null, false, false
					);
					?>
				</div>
				<div id="dropmenu_toolbox_admin" class="dropmenudiv_e"
					style="width: 180px;">
					<?php
					echo $html->link('<font><b>Update LC-Dating</b></font>',
							array('controller' => 'trans', 'action' => 'updtoolbox', 'site' => 1),
							null, false, false
					);
					?>
				</div>
				<?php
				}
				?>
				<!--office drop down menu -->
				<?php
				if ($role == 1) {// means an office
				?>
				<div id="dropmenu_com_agent" class="dropmenudiv_e"
					style="width: 130px;">
					<?php
					echo $html->link('<font><b>Manage Agent</b></font>',
							array('controller' => 'trans', 'action' => 'lstagents', 'id' => $userinfo['id']),
							null, false, false
					);
					?>
				</div>
				<?php
				}
				?>
				<!--agent drop down menu -->

				<!--office and agent drop down menu -->
				<?php
				if ($role == 1 || $role == 2) {//means an office or an agent
				?>
				<div id="dropmenu_toolbox_normal" class="dropmenudiv_e"
					style="width: 120px;">
					<?php
					/*
					echo $html->link('<font><b>HMS</b></font>',
							array('controller' => 'trans', 'action' => 'toolbox', 'site' => 2),
							null, false, false
					);
					*/
					echo $html->link('<font><b>LC-Dating</b></font>',
							array('controller' => 'trans', 'action' => 'toolbox', 'site' => 1),
							null, false, false
					);
					?>
				</div>
				<?php 
				}
				?>

				<!--everyone drop down menu -->
				<div id="dropmenu_onlinemodels" class="dropmenudiv_e"
					style="width: 110px;">
					<a class="iml_models" href="#models_div"> <font><b>IMLIVE</b> </font>
					</a>
				</div>
				<div id="dropmenu_logs" class="dropmenudiv_e" style="width: 135px;">
					<?php
					if ($role == 2) {
						echo $html->link('<font><b>Submit Chat Log</b></font>',
								array('controller' => 'trans', 'action' => 'addchatlogs'),
								null, false, false
						);
					}
					echo $html->link('<font><b>Chat Log</b></font>',
							array('controller' => 'trans', 'action' => 'lstchatlogs', 'id' => -1),
							null, false, false
					);
					echo $html->link('<font><b>Click Log</b></font>',
							array('controller' => 'links', 'action' => 'lstclickouts', 'id' => -1),
							null, false, false
					);
					if ($role != 2) {
						echo $html->link('<font><b>Login Log</b></font>',
								array('controller' => 'trans', 'action' => 'lstlogins', 'id' => -1),
								null, false, false
						);
					}
					?>
				</div>
				<!--5th drop down menu -->
			</div>
			<!-- End Navigation Bar -->
			<!-- Start Left Column -->
			<!-- Start Right Column -->
			<div id="rightcolumn">
				<!-- Start Main Content -->
				<div class="maincontent">
					<center>
						<b><font color="red"><?php $session->flash(); ?> </font> </b>
					</center>
					<div class="content-top">
						<div style="float: right; text-align: right;">
							<?php
							//echo $html->image('iconLips.png', array('width' => '40px'));
							?>
						</div>
						<div
							style="float: right; text-align: right; padding: 6px 20px 0px 0px;">
							<input type="text" value="" id="iptClock"
								style="width: 240px; text-align: right; border: 0px; background: transparent; font-family: Arial; font-weight: bold; color: #ffffff;"
								readonly="readonly"
								onmouseover="jQuery('#divTimezoneTip').slideDown();"
								onmouseout="jQuery('#divTimezoneTip').slideUp();" />
						</div>
						<div
							style="float: right; margin: 6px 6px 0px 0px; display: none; color: white;"
							id="divTimezoneTip">
							<script language="javascript">
				        	document.write("Your timezone: " + calculate_time_zone() + "");
				        	</script>
						</div>
						<script language="javascript">
			        	function __zShowClock() {
			        		var now = new Date();
				        	now.setHours(now.getHours() + 8);
				        	var nowStr = now.toUTCString();
				        	nowStr = nowStr.replace("GMT", "Manila");
			        		jQuery("#iptClock").val(nowStr);
			        		setTimeout("__zShowClock()", 1000);
			        	}
			        	__zShowClock();
				        </script>
					</div>
					<div class="content-mid">

						<?php echo $content_for_layout; ?>

					</div>
					<div class="content-bottom"></div>
				</div>
				<!-- End Main Content -->
			</div>
			<!-- End Right Column -->
		</div>
		<!-- End Border -->
		<!-- Start Footer -->
		<div id="footer">
			<font size="2" color="white"><b>Copyright &copy; 2010 ChatVazoo All
				Rights Reserved.&nbsp;&nbsp;</b> </font>
		</div>
		<!-- End Footer -->
	</div>

	<!-- for "agent must read" -->
	<?php
	if (in_array($userinfo['role'], array(0, 1, 2)) && !$session->check('switch_pass')) {
	?>
	<div style="display: none">
		<a id="attentions_link" href="#attentions_for_agents">show attentions</a>
	</div>
	<div style="display: none">
		<div id="attentions_for_agents" style="width: 800px;">
			<!--  
		<p class="p-blink" style="font:italic bolder 24px/100% Georgia;color:red;margin:0px 0px 6px 0px;">
		ATTENTION, EVERYONES: 
		</p>
		-->
			<script type="text/javascript" language="javascript">
		/*//we just don't blink the title of the alerts here
		colors = new Array(6);
		colors[0] = "#ff0000";
		colors[1] = "#ff2020";
		colors[2] = "#ff4040";
		colors[3] = "#ff6060";
		colors[4] = "#ff8080";
		colors[5] = "#ffffff";
		var clr_i = 0;
		function __blinkIt() {
			if (clr_i < colors.length) {
				jQuery(".p-blink").css("color", colors[clr_i]);
				clr_i++;
				setTimeout("__blinkIt()", 200);
			} else {
				clr_i = 0;
				setTimeout("__blinkIt()", 1200);
			}
		}
		__blinkIt();
		*/
		</script>
			<p style="padding: 3px 3px 3px 3px;">
				<?php
				echo !empty($alerts) ? $alerts : '';
				?>
			</p>

			<hr style="margin: 6px 0px 6px 0px" />
			<hr style="margin: 6px 0px 6px 0px" />

			<?php
			if (!empty($excludedsites)) {
			?>
			<p style="font-weight: bold; font-size: 14px; color: red;">
				YOUR
				<?php echo '"' . implode("\", \"", $excludedsites) . '"'; ?>
				LINKS HAVE BEEN SUSPENDED, PLEASE CONTACT <a
					href="mailto:support@chatvazoo.com"><font color="red">CVZ
						SUPPORT</font> </a> FOR MORE INFO.<br /> <a
					href="mailto:support@chatvazoo.com"><font color="red">support@chatvazoo.com</font>
				</a>
			</p>
			<div style="margin: 12px 2px 2px 2px; font-weight: bolder;">REASONS
				FOR TEMPORARY SUSPENSION</div>
			<p style="font-size: 14px; color: red;">
				1.SENDING LOW QUALITY SALES, CUSTOMERS WHO DO NOT SPEND MONEY ON THE
				SITE.<br /> <br /> 2.TOO MANY SALES, ON THE SAME SITE, SAME DAY. WE
				NEED TO MAKE SURE AGENT IS NOT GETTING IN ANY TROUBLE.3 OR MORE
				SALES, CAN BE FLAGGED, AGENT MAYBE CHEATING. IF HE IS SELLING TOO
				FAST.<br /> <br /> 3.LYING TO THE CUSTOMER THAT THE SITE IS FREE.<br />
				<br /> 4.TELLING CUSTOMER YOU WILL MEET HIM.<br />
			</p>
			<?php
			}
			?>

			<p style="text-align: center; margin: 9px 0px 0px 9px;">
				<?php
				echo $html->link('<font style="font-weight:bold;">Enter</font>',
						array('controller' => 'trans', 'action' => 'pass'),
						array('onclick' => 'javascript:jQuery.fancybox.close();',),
						false, false
				);
				?>
			</p>
		</div>
	</div>
	<script type="text/javascript" language="javascript">
		jQuery(document).ready(function() {
			jQuery("a#attentions_link").fancybox({
				'type': 'inline',
				'overlayOpacity': 0.6,
				'overlayColor': '#0A0A0A',
				'modal': true
			});
			jQuery("a#attentions_link").click();
		});
	</script>
	<?php
	}
	?>

	<!-- for "LIVE MODELS" -->
	<div style="display: none;">
		<div id="models_div">
			<?php
			echo $html->image('indicator_medium.gif',
				array(
					'id' => 'models_loading_img',
					'style' => 'margin:220px 0px 0px 390px;'
				)
			);
			?>
			<iframe style="width: 800px; height: 460px;" frameborder="0"
				id="models_iframe"> </iframe>
		</div>
	</div>
	<script type="text/javascript" language="javascript">
		jQuery(document).ready(function() {
			jQuery("a.iml_models").fancybox({
				'autoDimensions' : false,
				'hideOnContentClick': false,
				'overlayOpacity': 0.6,
				'overlayColor': '#0A0A0A',
				'width': 800,
				'height': 460,
				'onStart': function() {
					jQuery("#models_iframe").attr("src", "<?php echo $html->url(array('controller' => 'pages', 'action' => 'iml_online'), true); ?>");
					jQuery("#models_iframe").css("display", "none");
					jQuery("#models_iframe").load(function() {
						jQuery("#models_iframe").css("display", "block");
						jQuery("#models_loading_img").css("display", "none");
					})
				}
			});
		});
	</script>

	<!-- for tab menu -->
	<script type="text/javascript">
		//SYNTAX: tabdropdown.init("menu_id", [integer OR "auto"])
		tabdropdown.init("moonmenu", <?php echo $curmenuidx; ?>);
	</script>
	
	<!-- To avoid delays, initialize Cufón before other scripts at the bottom -->
	<script type="text/javascript"> Cufon.now(); </script>
</body>
</html>
