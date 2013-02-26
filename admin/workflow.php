<?php
/*
Author: Flox
FileName: workflow.php
Call: admin.php
Description: workflow manager
Version: 1.0
creation date: 28/11/2012
last update: 28/11/2012
*/
?>
<br />
<center>

	<img src="./images/internet.png" alt="server" />
	<br />
	<img src="./images/arrow_up.png" alt="server" />
	<br />
	<img src="./images/server_mail.png" alt="server" />
		<?php if ($rparameters['mail_smtp']=='') echo '<img src="./images/arrow_left_cross.png" alt="server" />'; else echo '<img src="./images/arrow_left.png" alt="server" />';?>
	<img src="./images/server.png" alt="server" />
		<?php if ($rparameters['ldap']=='0') echo '<img src="./images/arrow_right_cross.png" alt="server" />'; else echo '<img src="./images/arrow_right.png" alt="server" />';?>
	<img src="./images/server_ldap.png" alt="server" />
	<br />
	<img src="./images/arrow_down.png" alt="server" />
	<br />
	<img src="./images/computer.png" alt="server" />
</center>
<br />