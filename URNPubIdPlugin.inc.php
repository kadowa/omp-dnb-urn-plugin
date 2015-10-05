<?php

/**
 * @file plugins/pubIds/doi/URNPubIdPlugin.inc.php
 *
 * Copyright (c) 2015 Heidelberg University
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class URNPubIdPlugin
 * @ingroup plugins_pubIds_doi
 *
 * @brief URN plugin class
 */


import('classes.plugins.PubIdPlugin');

class URNPubIdPlugin extends PubIdPlugin {

	//
	// Implement template methods from PKPPlugin.
	//
	/**
	 * @see PubIdPlugin::register()
	 */
	function register($category, $path) {
		$success = parent::register($category, $path);
		$this->addLocaleData();
		return $success;
	}

	/**
	 * @see PKPPlugin::getName()
	 */
	function getName() {
		return 'URNPubIdPlugin';
	}

	/**
	 * @see PKPPlugin::getDisplayName()
	 */
	function getDisplayName() {
		return __('plugins.pubIds.urn.displayName');
	}

	/**
	 * @see PKPPlugin::getDescription()
	 */
	function getDescription() {
		return __('plugins.pubIds.urn.description');
	}

	/**
	 * @see Plugin::getTemplatePath($inCore)
	 */
	function getTemplatePath($inCore = false) {
		return parent::getTemplatePath($inCore) . 'templates/';
	}

	/**
	 * Define management link actions for the settings verb.
	 * @return LinkAction
	 */
	function getManagementVerbLinkAction($request, $verb) {
		$router = $request->getRouter();

		list($verbName, $verbLocalized) = $verb;

		if ($verbName === 'settings') {
			import('lib.pkp.classes.linkAction.request.AjaxLegacyPluginModal');
			$actionRequest = new AjaxLegacyPluginModal(
					$router->url($request, null, null, 'plugin', null, array('verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'pubIds')),
					$this->getDisplayName()
			);
			return new LinkAction($verbName, $actionRequest, $verbLocalized, null);
		}

		return null;
	}

	//
	// Implement template methods from PubIdPlugin.
	//
	/**
	 * @see PubIdPlugin::getPubId()
	 */
	function getPubId($pubObject, $preview = false) {
		$urn = "";

		return $urn;
	}

	/**
	 * @see PubIdPlugin::getPubIdType()
	 */
	function getPubIdType() {
		return 'urn';
	}

	/**
	 * @see PubIdPlugin::getPubIdDisplayType()
	 */
	function getPubIdDisplayType() {
		return 'URN';
	}

	/**
	 * @see PubIdPlugin::getPubIdFullName()
	 */
	function getPubIdFullName() {
		return 'Uniform Resource Name';
	}

	/**
	 * @see PubIdPlugin::getResolvingURL()
	 */
	function getResolvingURL($pressId, $pubId) {
		return 'https://nbn-resolving.org/'.urlencode($pubId);
	}

	/**
	 * @see PubIdPlugin::getFormFieldNames()
	 */
	function getFormFieldNames() {
		return array('urnPrefix');
	}

	/**
	 * @see PubIdPlugin::getDAOFieldNames()
	 */
	function getDAOFieldNames() {
		return array('pub-id::urn');
	}

	/**
	 * @see PubIdPlugin::getPubIdMetadataFile()
	 */
	function getPubIdMetadataFile() {
		return '';
	}
	
	/**
	 * @see PubIdPlugin::getSettingsFormName()
	 */
	function getSettingsFormName() {
		return 'classes.form.URNSettingsForm';
	}

	/**
	 * @see PubIdPlugin::verifyData()
	 */
	function verifyData($fieldName, $fieldValue, &$pubObject, $pressId, &$errorMsg) {
		return True;
	}

	/**
	 * @see PubIdPlugin::validatePubId()
	 */
	function validatePubId($pubId) {
		return True;
	}

	//
	// Private helper methods
	//
	/**
	 * Get the press object.
	 * @param $pressId integer
	 * @return Press
	 */
	function &_getPress($pressId) {
		assert(is_numeric($pressId));

		// Get the press object from the context (optimized).
		$request = $this->getRequest();
		$router = $request->getRouter();
		$press = $router->getContext($request); /* @var $press Press */

		// Check whether we still have to retrieve the press from the database.
		if (!$press || $press->getId() != $pressId) {
			unset($press);
			$pressDao = DAORegistry::getDAO('PressDAO');
			$press = $pressDao->getById($pressId);
		}

		return $press;
	}
}

?>
