{**
 * plugins/pubIds/urn_dnb/templates/settingsForm.tpl
 *
 * Copyright (c) 2015 Heidelberg University
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * URN DNB plugin settings
 *
 *}
<div id="description">{translate key="plugins.pubIds.urnDNB.manager.settings.description"}</div>

<script type="text/javascript">
	$(function() {ldelim}
		// Attach the form handler.
		$('#urnDNBSettingsForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>
<form class="pkp_form" id="urnDNBSettingsForm" method="post" action="{url router=$smarty.const.ROUTE_COMPONENT op="plugin" category="pubIds" plugin=$pluginName verb="settings" save="true"}">
	{include file="common/formErrors.tpl"}
	{fbvFormArea id="enableUrnSettingsFormArea" class="border" title="plugins.pubIds.urnDNB.manager.settings.urnDNBPrefix"}
		{fbvFormSection}
			<p class="pkp_help">{translate key="plugins.pubIds.urnDNB.manager.settings.urnDNBPrefixPattern"}</p>
			{fbvElement type="text" label="plugins.pubIds.urnDNB.manager.settings.urnDNBPrefix" required="true" id="urnDNBPrefix" value=$urnDNBPrefix maxlength="40" size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}
	{/fbvFormArea}
	{fbvFormArea id="urnDNBSuffixPatternFormArea" class="border" title="plugins.pubIds.urnDNB.manager.settings.urnDNBSuffix"}
		{fbvFormSection label="plugins.pubIds.urnDNB.manager.settings.urnDNBSuffixDescription" list="true"}
			{if $urnDNBSuffix eq "pattern"}
				{assign var="checked" value=true}
			{else}
				{assign var="checked" value=false}
			{/if}
			{fbvElement type="radio" id="urnDNBSuffix" name="urnDNBSuffix" value="pattern" checked=$checked label="plugins.pubIds.urnDNB.manager.settings.urnDNBSuffixPatternDescription"}
		{/fbvFormSection}
		{fbvFormSection}
			<p class="pkp_help">{fieldLabel name="urnDNBSuffixPattern" key="plugins.pubIds.urnDNB.manager.settings.urnDNBSuffixPattern.example"}</p>
			{fbvElement type="text" label="plugins.pubIds.urnDNB.manager.settings.urnDNBSuffixPattern" id="urnDNBSuffixPattern" value=$urnDNBSuffixPattern maxlength="40" inline=true size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}
		{fbvFormSection list="true"}
			{if !in_array($urnDNBSuffix, array("pattern", "publisherId", "customId"))}
				{assign var="checked" value=true}
			{else}
				{assign var="checked" value=false}
			{/if}
			{fbvElement type="radio" id="urnDNBSuffixDefault" name="urnDNBSuffix" required="true" value="default" checked=$checked label="plugins.pubIds.urnDNB.manager.settings.urnDNBSuffixDefault"}
			<span class="instruct">{translate key="plugins.pubIds.urnDNB.manager.settings.urnDNBSuffixDefault.description"}</span>
			{if $urnDNBSuffix eq "customId"}
				{assign var="checked" value=true}
			{else}
				{assign var="checked" value=false}
			{/if}
		{/fbvFormSection}
	{/fbvFormArea}
	{fbvFormArea id="urnDNBSuffixReassignFormArea" class="border" title="plugins.pubIds.urnDNB.manager.settings.urnDNBReassign"}
		{fbvFormSection}
			<span class="instruct">{translate key="plugins.pubIds.urnDNB.manager.settings.urnDNBReassign.description"}</span><br/>
			{include file="linkAction/linkAction.tpl" action=$clearPubIdsLinkAction contextId="urnDNBSettingsForm"}
		{/fbvFormSection}
	{/fbvFormArea}
	{fbvFormButtons submitText="common.save"}
</form>
<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
