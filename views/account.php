<div class="container-fluid">
	<h1><?php echo _("Google Voice Account")?></h1>
	<div class="display full-border">
		<div class="row">
			<div class="col-sm-12">
				<div class="fpbx-container">
					<div class="display full-border" id="certpage">
						<form class="fpbx-submit" name="frm_motif" action="config.php?display=motif" method="post" enctype="multipart/form-data" <?php echo !empty($id) ? 'data-fpbx-delete="config.php?display=motif&amp;action=delete&amp;id='.$account.'"' : ''?>>
							<input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''?>">
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="phonenum"><?php echo _("Google Voice Phone Number")?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="phonenum"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" name="phonenum" id="phonenum" value="<?php echo isset($phonenum) ? $phonenum : ''; ?>">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="phonenum-help" class="help-block fpbx-help-block"><?php echo _("This is your Google Voice Phone Number <br />10 Digit Format")?></span>
									</div>
								</div>
							</div>
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="username"><?php echo _("Google Voice Username")?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="username"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" name="username" id="username" value="<?php echo isset($username) ? $username : ''; ?>">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="username-help" class="help-block fpbx-help-block"><?php echo _("This is your google voice login. If don't you supply '@domain' we will append @gmail.com")?></span>
									</div>
								</div>
							</div>
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3 control-label">
													<label for="authmode"><?php echo _("Authentication Mode")?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="authmode"></i>
												</div>
												<div class="col-md-9 radioset">
													<input type="radio" id="authmode1" name="authmode" value="plaintext" <?php echo ($authmode == 'plaintext') ? 'checked' : ''?>>
													<label for="authmode1"><?php echo _('Plain Text')?></label>
													<input type="radio" id="authmode2" name="authmode" value="oauth2" <?php echo ($authmode == 'plaintext') ? '' : 'checked'?> <?php echo !$showOauth2 ? 'disabled="true"' : ''?>>
													<label for="authmode2"><?php echo _('OAuth 2')?></label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="authmode-help" class="help-block fpbx-help-block"><?php echo _("Authentication Mode to Use for Google")?></span>
									</div>
								</div>
							</div>
							<div class="element-container oauth <?php echo ($authmode == 'plaintext') ? 'hidden' : ''?>">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="refresh_token"><?php echo _("Google Voice Refresh Token")?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="refresh_token"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" name="refresh_token" id="refresh_token" value="<?php echo isset($refresh_token) ? $refresh_token : ''; ?>">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="refresh_token-help" class="help-block fpbx-help-block"><?php echo _("This is your Google Voice OAuth 2 Refresh Token")?></span>
									</div>
								</div>
							</div>
							<div class="element-container oauth <?php echo ($authmode == 'plaintext') ? 'hidden' : ''?>">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="oauth_clientid"><?php echo _("Google Voice OAuth Client ID")?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="oauth_clientid"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" name="oauth_clientid" id="oauth_clientid" value="<?php echo isset($oauth_clientid) ? $oauth_clientid : ''; ?>">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="oauth_clientid-help" class="help-block fpbx-help-block"><?php echo _("This is your Google Voice OAuth 2 Client ID")?></span>
									</div>
								</div>
							</div>
							<div class="element-container oauth <?php echo ($authmode == 'plaintext') ? 'hidden' : ''?>">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="oauth_secret"><?php echo _("Google Voice OAuth Secret")?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="oauth_secret"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" name="oauth_secret" id="oauth_secret" value="<?php echo isset($oauth_secret) ? $oauth_secret : ''; ?>">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="oauth_secret-help" class="help-block fpbx-help-block"><?php echo _("This is your Google Voice OAuth 2 Secret")?></span>
									</div>
								</div>
							</div>
							<div class="element-container plaintext <?php echo ($authmode != 'plaintext') ? 'hidden' : ''?>">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="password"><?php echo _("Google Voice Password")?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="password"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" name="password" id="password" value="<?php echo isset($password) ? $password : ''; ?>">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="password-help" class="help-block fpbx-help-block"><?php echo _("This is your Google Voice Password")?></span>
									</div>
								</div>
							</div>
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3 control-label">
													<label for="trunk"><?php echo _("Configure Trunk")?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="trunk"></i>
												</div>
												<div class="col-md-9 radioset">
													<input type="radio" id="trunk1" name="trunk" value="yes" <?php echo ($settings['trunk']) ? 'checked' : ''?>>
													<label for="trunk1"><?php echo _('Yes')?></label>
													<input type="radio" id="trunk2" name="trunk" value="no" <?php echo ($settings['trunk']) ? '' : 'checked'?>>
													<label for="trunk2"><?php echo _('No')?></label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="trunk-help" class="help-block fpbx-help-block"><?php echo _("Automatically Add this Account as a Trunk")?></span>
									</div>
								</div>
							</div>
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3 control-label">
													<label for="obroute"><?php echo _("Configure Outbound Routes")?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="obroute"></i>
												</div>
												<div class="col-md-9 radioset">
													<input type="radio" id="obroute1" name="obroute" value="yes" <?php echo ($settings['obroute']) ? 'checked' : ''?>>
													<label for="obroute1"><?php echo _('Yes')?></label>
													<input type="radio" id="obroute2" name="obroute" value="no" <?php echo ($settings['obroute']) ? '' : 'checked'?>>
													<label for="obroute2"><?php echo _('No')?></label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="obroute-help" class="help-block fpbx-help-block"><?php echo _("Automatically Add Outbound Route for this Account")?></span>
									</div>
								</div>
							</div>
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3 control-label">
													<label for="obroute"><?php echo _("Use Google Voicemail")?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="gvm"></i>
												</div>
												<div class="col-md-9 radioset">
													<input type="radio" id="gvm1" name="gvm" value="yes" <?php echo ($settings['gvm']) ? 'checked' : ''?>>
													<label for="gvm1"><?php echo _('Yes')?></label>
													<input type="radio" id="gvm2" name="gvm" value="no" <?php echo ($settings['gvm']) ? '' : 'checked'?>>
													<label for="gvm2"><?php echo _('No')?></label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="gvm-help" class="help-block fpbx-help-block"><?php echo _("Send unanswered calls to Google voicemail after 25 seconds<br />Note: You MUST route this to a device that can answer. Example: IVRs and Phone directories can NOT answer")?></span>
									</div>
								</div>
							</div>
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3 control-label">
													<label for="obroute"><?php echo _("Always Answer (IVR Mode)")?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="greeting"></i>
												</div>
												<div class="col-md-9 radioset">
													<input type="radio" id="greeting1" name="greeting" value="yes" <?php echo ($settings['greeting']) ? 'checked' : ''?>>
													<label for="greeting1"><?php echo _('Yes')?></label>
													<input type="radio" id="greeting2" name="greeting" value="no" <?php echo ($settings['greeting']) ? '' : 'checked'?>>
													<label for="greeting2"><?php echo _('No')?></label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="greeting-help" class="help-block fpbx-help-block"><?php echo _("Add a stealth greeting so Google Voice is forced to pass the call when you want unanswered calls to go to GoogleVoice Voicemail (above). WARNING: The PBX will always answer, however if the PBX goes offline then GoogleVoice Voicemail will pick the call up.")?></span>
									</div>
								</div>
							</div>
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="statusmessage"><?php echo _("Google Voice Status Message")?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="statusmessage"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" name="statusmessage" id="statusmessage" value="<?php echo isset($statusmessage) ? $statusmessage : ''; ?>">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="statusmessage-help" class="help-block fpbx-help-block"><?php echo _("This is your Google Voice Status Message that buddies will see")?></span>
									</div>
								</div>
							</div>
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="priority"><?php echo _("XMPP Priority")?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="priority"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" name="priority" id="priority" value="<?php echo isset($priority) ? $priority : ''; ?>">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="priority-help" class="help-block fpbx-help-block"><?php echo _("This is the priority of where google will route an inbound call. A higher number has a higher priority. We believe that:<ul><li>Windows Gtalk client is 20</li><li>GMail is 24</li><li>Pidgin would be 0 or 1</li></ul>The range of acceptable values is -128 to +127. Anything else will be reset to the highest or lowest value.")?></span>
									</div>
								</div>
							</div>
							<?php if(isset($id)) {?>
								<div class="element-container">
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="form-group">
													<div class="col-md-3">
														<label class="control-label" for="status"><?php echo _("Status")?></label>
														<i class="fa fa-question-circle fpbx-help-icon" data-for="status"></i>
													</div>
													<div class="col-md-9">
														<span class="label label-<?php echo ($connected) ? 'success' : 'warning'?>">
															<?php echo ($connected) ? _('Connected') : _('Disconnected')?>
														</span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<span id="status-help" class="help-block fpbx-help-block"><?php echo _("Status of your account")?></span>
										</div>
									</div>
								</div>
							<?php } ?>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
