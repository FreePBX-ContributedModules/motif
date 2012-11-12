<?php
echo "<h2>"._("Google Voice [Motif]")."</h2>";

$type = $action == 'add' ? 'Add' : 'Edit';

if($action == 'edit') { 
	echo "<h2>Account: ".$form_number."</h2>"; 
	echo "<a href='config.php?display=motif&action=delete&id=".$id."'><img src='images/user_delete.png' /> Delete Account ".$form_number."</a>";
}
?>
<script>
	function editM_onsubmit() {
		return true;
	}
</script>
    <form autocomplete="off" name="editM" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" onsubmit="return editM_onsubmit();">
	<?php if($action == 'edit') { echo '<input type="hidden" name="id" value="'.$id.'">'; } ?>
        <table>
            <tr>
                <td colspan="2"><h5>Typical Settings</h5><hr></td>
            </tr>
            <tr>
                <td><a href="#" class="info"><?php echo _("Google Voice Username")?><span><?php echo _("This is your google voice login.<br />If don't you supply '@domain' we will append @gmail.com")?></span></a></td>
                <td><input type="text" name="username" value="<?php echo isset($form_username) ? $form_username : ''; ?>"></td>
            </tr>
            <tr>
                <td><a href="#" class="info"><?php echo _("Google Voice Password")?><span><?php echo _("This is your Google Voice Password")?></span></a></td>
            	<td><input type="password" name="password" value="<?php echo isset($form_password) ? $form_password : ''; ?>"></td>
			</tr>
			<tr>
                <td><a href="#" class="info"><?php echo _("Google Voice Phone Number")?><span><?php echo _("This is your Google Voice Phone Number <br />10 Digit Format")?></span></a></td>
            	<td><input type="text" name="number" value="<?php echo isset($form_number) ? $form_number : ''; ?>"></td>
			</tr>
			<tr>
                <td><a href="#" class="info"><?php echo _($type." Trunk")?><span><?php echo _("Automatically Add this Account as a Trunk")?></span></a></td>
            	<td><input type="checkbox" name="trunk" <?php echo isset($form_trunk) && $form_trunk ? 'CHECKED' : ''; ?>></td>
			</tr>
			<tr>
                <td><a href="#" class="info"><?php echo _($type." Outbound Routes")?><span><?php echo _("Automatically Add Outbound Routes for this Account")?></span></a></td>
            	<td><input type="checkbox" name="obroute" <?php echo isset($form_obroute) && $form_obroute ? 'CHECKED' : ''; ?>></td>
			</tr>
			<!-- not configured
			<tr>
                <td><a href="#" class="info"><?php echo _($type. " Inbound Routes")?><span><?php echo _("Automatically Add Inbound Routes for this Account")?></span></a></td>
            	<td><input type="checkbox" name="ibroute" <?php echo isset($form_ibroute) && $form_ibroute ? 'CHECKED' : ''; ?>></td>
			</tr>
			-->
			<tr>
                <td><a href="#" class="info"><?php echo _("Send Unanswered to GoogleVoice Voicemail")?><span><?php echo _("Send unanswered calls to google voicemail after 25 seconds<br />Note: You MUST route this to a device that can answer. Example: IVRs and Phone directories can NOT answer")?></span></a></td>
            	<td><input type="checkbox" name="gvm" <?php echo isset($form_gvm) && $form_gvm ? 'CHECKED' : ''; ?>></td>
			</tr>
        </table>
		<br />
        <table>
            <tr>
                <td colspan="2"><h5>Advanced Settings</h5><hr></td>
            </tr>
            <tr>
                <td colspan="2">None At This Time</td>
            </tr>
        </table>
		<br />
		<?php if($action == 'edit') { ?>
        <table>
            <tr>
                <td colspan="2"><h5>Detailed Status</h5><hr></td>
            </tr>
            <tr>
                <td><u>Status:</u></td>
				<td><?php if($status['connected']) { ?><div style="background-color:green; color:white; height: 20px; width: 80px; text-align: center">Connected</div><?php } else { ?><div style="background-color:red; color:white; height: 20px; width: 80px; text-align: center">Disconnected</div><?php } ?></td>
            </tr>
			<tr>
				<td colspan="2"><u>Buddies:</u></td>
			</tr>
			<tr>
				<td colspan="2">
					<ul>
			<?php foreach($buddies as $list) {?>
                <li><?php echo $list ?></li>
			<?php } ?>
					</ul>
				</td>
			</tr>
        </table>
		<?php } ?>
		<br />
		<br />
		<input type="submit" value="Submit">
    </form>
