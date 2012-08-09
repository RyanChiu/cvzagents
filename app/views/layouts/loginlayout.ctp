<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>
<title><?php echo $title_for_layout; ?>
</title>
<?php

/*for default whole page layout*/
echo $html->css('main');

/*for jQuery*/
echo $javascript->link('jQuery/Datepicker/jquery-1.3.2.min');

/*for cufon*/
echo $javascript->link('cufon/cufon-yui');
echo $javascript->link('cufon/Showcard_Gothic_400.font');

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
			<!-- Start Right Column -->
			<div id="rightcolumn">
				<!-- Start Main Content -->
				<div class="maincontent">
					<center>
						<b><font color="red"><?php $session->flash(); ?> </font> </b>
					</center>
					<div class="content-top"></div>
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
			<font size="2" color="white"><b>Copyright &copy; 2009 ChatVazoo All
					Rights Reserved.&nbsp;&nbsp;</b> </font>
		</div>
		<!-- End Footer -->
	</div>
	
	<!-- To avoid delays, initialize Cufón before other scripts at the bottom -->
	<script type="text/javascript"> Cufon.now(); </script>
</body>
</html>
