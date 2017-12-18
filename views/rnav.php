<div id="toolbar-grid">
	<a href="?display=motif&amp;action=addaccount" id="add-account" class="btn btn-add">
		<i class="fa fa-user-plus"></i> <span><?php echo _('Add')?></span>
	</a>
</div>
<table data-toolbar="#toolbar-grid" data-url="ajax.php?module=motif&amp;command=getaccounts" data-cache="false" data-toggle="table" data-maintain-selected="true" data-show-columns="true" data-pagination="true" data-search="true" class="table table-striped" id="table-users" data-type="users">
	<thead>
		<tr>
			<th data-checkbox="true"></th>
			<th data-sortable="true" data-field="phonenum"><?php echo _("DID") ?></th>
			<th data-sortable="true" data-field="username"><?php echo _("Username") ?></th>
			<th data-formatter="motifactions"><?php echo _("Action") ?></th>
		</tr>
	</thead>
</table>
