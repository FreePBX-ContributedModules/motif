<div class="alert alert-danger" role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only"><?php echo _("Google Voice Functionality") ?>:</span>
  <?php echo _("The google voice functionality is no longer functional as it requires unsupported patches to Asterisk to accomidate functional changes to the GV service")?>
</div>
<?php
echo FreePBX::Motif()->showPage();
