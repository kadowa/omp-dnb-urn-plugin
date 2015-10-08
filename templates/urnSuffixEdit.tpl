{**
 * @file plugins/pubIds/urn/templates/urnSuffixEdit.tpl
 *
 * Copyright (c) 2014-2015 Simon Fraser University Library
 * Copyright (c) 2003-2015 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Edit URN meta-data.
 *}

{assign var=storedPubId value=$pubObject->getStoredPubId($pubIdPlugin->getPubIdType())}
{fbvFormArea id="pubIdURNFormArea" class="border" title="plugins.pubIds.urn.editor.urn"}
{if $pubIdPlugin->getSetting($currentPress->getId(), 'urnSuffix') == 'customId' || $storedPubId}
	{if empty($storedPubId)}
			{fbvFormSection}
				<p class="pkp_help">{translate key="plugins.pubIds.urn.manager.settings.urnSuffixDescription"}</p>
				{fbvElement type="text" label="plugins.pubIds.urn.manager.settings.urnPrefix" id="urnPrefix" disabled=true value=$pubIdPlugin->getSetting($currentPress->getId(), 'urnPrefix') size=$fbvStyles.size.SMALL}
				{fbvElement type="text" label="plugins.pubIds.urn.manager.settings.urnSuffix" id="urnSuffix" value=$urnSuffix size=$fbvStyles.size.MEDIUM}
			{/fbvFormSection}
	{else}
		{$storedPubId|escape}
	{/if}
{else}
	{$pubIdPlugin->getPubId($pubObject, true)|escape} <br />
	<br />
	{capture assign=translatedObjectType}{translate key="plugins.pubIds.urn.editor.urnObjectType"|cat:$pubObjectType}{/capture}
	{translate key="plugins.pubIds.urn.editor.urnNotYetGenerated" pubObjectType=$translatedObjectType}
{/if}
{/fbvFormArea}
