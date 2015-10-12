{**
 * plugins/pubIds/urn_dnb/templates/settingsForm.tpl
 *
  * Copyright (c) 2015 Heidelberg University
  * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * URN plugin settings
 *
 *}
<div id="description">{translate key="plugins.pubIds.urn.manager.settings.description"}</div>

<script type="text/javascript">
	$(function() {ldelim}
		// Attach the form handler.
		$('#urnSettingsForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>
<form class="pkp_form" id="urnSettingsForm" method="post" action="{url router=$smarty.const.ROUTE_COMPONENT op="plugin" category="pubIds" plugin=$pluginName verb="settings" save="true"}">
	{include file="common/formErrors.tpl"}
	{fbvFormArea id="enableUrnSettingsFormArea" class="border" title="plugins.pubIds.urn.manager.settings.urnSettings"}
		{fbvFormSection}
			<p class="pkp_help">{translate key="plugins.pubIds.urn.manager.settings.urnPrefixPattern"}</p>
			{fbvElement type="text" label="plugins.pubIds.urn.manager.settings.urnPrefix" required="true" id="urnPrefix" value=$urnPrefix maxlength="40" size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}
	{/fbvFormArea}
	{fbvFormArea id="urnSuffixPatternFormArea" class="border" title="plugins.pubIds.urn.manager.settings.urnSuffix"}
		{fbvFormSection label="plugins.pubIds.urn.manager.settings.urnSuffixDescription" list="true"}
			{if $urnSuffix eq "pattern"}
				{assign var="checked" value=true}
			{else}
				{assign var="checked" value=false}
			{/if}
			{fbvElement type="radio" id="urnSuffix" name="urnSuffix" value="pattern" checked=$checked label="plugins.pubIds.urn.manager.settings.urnSuffixPattern"}
		{/fbvFormSection}
		{fbvFormSection}
			<p class="pkp_help">{fieldLabel name="urnSuffixPattern" key="plugins.pubIds.urn.manager.settings.urnSuffixPattern.example"}</p>
			{fbvElement type="text" label="plugins.pubIds.urn.manager.settings.urnSuffixPattern.publicationFormats" id="urnPublicationFormatSuffixPattern" value=$urnPublicationFormatSuffixPattern maxlength="40" inline=true size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}

		{fbvFormSection list="true"}
			{if !in_array($urnSuffix, array("pattern", "publisherId", "customId"))}
				{assign var="checked" value=true}
			{else}
				{assign var="checked" value=false}
			{/if}
			{fbvElement type="radio" id="urnSuffixDefault" name="urnSuffix" required="true" value="default" checked=$checked label="plugins.pubIds.urn.manager.settings.urnSuffixDefault"}
			<span class="instruct">{translate key="plugins.pubIds.urn.manager.settings.urnSuffixDefault.description"}</span>
			{if $urnSuffix eq "customId"}
				{assign var="checked" value=true}
			{else}
				{assign var="checked" value=false}
			{/if}
			{fbvElement type="radio" id="urnSuffixCustomIdentifier" name="urnSuffix" required="true" value="customId" checked=$checked label="plugins.pubIds.urn.manager.settings.urnSuffixCustomIdentifier"}
		{/fbvFormSection}
	{/fbvFormArea}
	{fbvFormArea id="urnSuffixReassignFormArea" class="border" title="plugins.pubIds.urn.manager.settings.urnReassign"}
		{fbvFormSection}
			<span class="instruct">{translate key="plugins.pubIds.urn.manager.settings.urnReassign.description"}</span><br/>
			{include file="linkAction/linkAction.tpl" action=$clearPubIdsLinkAction contextId="urnSettingsForm"}
		{/fbvFormSection}
	{/fbvFormArea}
	{fbvFormButtons submitText="common.save"}
</form>
<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
