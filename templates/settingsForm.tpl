{**
 * plugins/pubIds/urn_dnb/templates/settingsForm.tpl
 *
  * Copyright (c) 2015 Heidelberg University
  * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * DOI plugin settings
 *
 *}
<div id="description">{translate key="plugins.pubIds.doi.manager.settings.description"}</div>

<script type="text/javascript">
	$(function() {ldelim}
		// Attach the form handler.
		$('#doiSettingsForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>
<form class="pkp_form" id="doiSettingsForm" method="post" action="{url router=$smarty.const.ROUTE_COMPONENT op="plugin" category="pubIds" plugin=$pluginName verb="settings" save="true"}">
	{include file="common/formErrors.tpl"}
	{fbvFormButtons submitText="common.save"}
</form>
<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
