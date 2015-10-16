<?php

/**
 * @file plugins/pubIds/urn_dnb/URNDNBPubIdPlugin.inc.php
 *
 * Copyright (c) 2014-2015 Simon Fraser University Library
 * Copyright (c) 2000-2015 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class URNDNBPubIdPlugin
 * @ingroup plugins_pubIds_urndnb
 *
 * @brief URNDNB plugin class
 */


import('classes.plugins.PubIdPlugin');

class URNDNBPubIdPlugin extends PubIdPlugin {

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
		return 'URNDNBPubIdPlugin';
	}

	/**
	 * @see PKPPlugin::getDisplayName()
	 */
	function getDisplayName() {
		return __('plugins.pubIds.urnDNB.displayName');
	}

	/**
	 * @see PKPPlugin::getDescription()
	 */
	function getDescription() {
		return __('plugins.pubIds.urnDNB.description');
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
	
	/**
	 * @see PKPPlugin::manage()
	 */
	function manage($verb, $args, &$message, &$messageParams, &$pluginModalContent = null) {
		$request = $this->getRequest();
		$templateManager = TemplateManager::getManager($request);
		$templateManager->register_function('plugin_url', array(&$this, 'smartyPluginUrl'));
		if (!$this->getEnabled() && $verb != 'enable') return false;
		switch ($verb) {
			case 'enable':
				$this->setEnabled(true);
				$message = NOTIFICATION_TYPE_PLUGIN_ENABLED;
				$messageParams = array('pluginName' => $this->getDisplayName());
				return false;
	
			case 'disable':
				$this->setEnabled(false);
				$message = NOTIFICATION_TYPE_PLUGIN_DISABLED;
				$messageParams = array('pluginName' => $this->getDisplayName());
				return false;
	
			case 'settings':
				$press = $request->getPress();
	
				$settingsFormName = $this->getSettingsFormName();
				$settingsFormNameParts = explode('.', $settingsFormName);
				$settingsFormClassName = array_pop($settingsFormNameParts);
				$this->import($settingsFormName);
				$form = new $settingsFormClassName($this, $press->getId());
				if ($request->getUserVar('save')) {
					$form->readInputData();
					if ($form->validate()) {
						$form->execute();
						$message = NOTIFICATION_TYPE_SUCCESS;
						$messageParams = array('contents' => __('plugins.pubIds.urn.manager.settings.urnSettingsUpdated'));
						return false;
					} else {
						$pluginModalContent = $form->fetch($request);
					}
				} else {
					$form->initData();
					$pluginModalContent = $form->fetch($request);
				}
				return false;
			default:
				// Unknown management verb
				assert(false);
				return false;
		}
	}

	//
	// Implement template methods from PubIdPlugin.
	//
	/**
	 * @see PubIdPlugin::getPubId()
	 */
	function getPubId($monograph, $preview = false) {
		// Determine the type of the publishing object.
		$pubObjectType = $this->getPubObjectType($monograph);

		// Get the press id of the object.
		if (in_array($pubObjectType, array('Monograph', 'PublishedMonograph'))) {
			$pressId = $monograph->getContextId();
		} else {
			return null;
		}
		
		$press = $this->_getPress($pressId);
		if (!$press) return null;
		$pressId = $press->getId();

		// If we already have an assigned URN, use it.
		$storedURNDNB = $monograph->getStoredPubId('urn');
		if ($storedURNDNB) return $storedURNDNB;
		
		// Retrieve the URN prefix.
		$urnPrefix = $this->getSetting($pressId, 'urnDNBPrefix');
		
		if (empty($urnPrefix)) return null;

		// Generate the URN suffix.
		$urnSuffixGenerationStrategy = $this->getSetting($pressId, 'urnDNBSuffix');
		
		switch ($urnSuffixGenerationStrategy) {
/* 			case 'customId':
				$urnSuffix = $pubObject->getData('urnSuffix');
				break;
 */
			case 'pattern':
				$urnSuffix = $this->getSetting($pressId, "urnDNBSuffixPattern");
				
				// %p - press initials
				$urnSuffix = String::regexp_replace('/%p/', String::strtolower($press->getPath()), $urnSuffix);

				if ($monograph) {
					// %m - monograph id
					$urnSuffix = String::regexp_replace('/%m/', $monograph->getId(), $urnSuffix);
				}

				break;

			default:
				$urnSuffix = String::strtolower($press->getPath());

				if ($monograph) {
					$urnSuffix .= '.' . $monograph->getId();
				}
		}
		if (empty($urnSuffix)) return null;

		// Join prefix and suffix.
		$urn = $urnPrefix . $urnSuffix;

		if (!$preview) {
			// Save the generated URN.
			$this->setStoredPubId($monograph, $pubObjectType, $urn);
		}

		return $urn . $this->_calculateUrnCheckNo($urn);
	}

	/**
	 * @see PubIdPlugin::getPubIdType()
	 */
	function getPubIdType() {
		return 'other::urnDNB';
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
		return array();
	}

	/**
	 * @see PubIdPlugin::getDAOFieldNames()
	 */
	function getDAOFieldNames() {
		return array('pub-id::other::urnDNB');
	}

	/**
	 * @see PubIdPlugin::getPubIdMetadataFile()
	 */
	function getPubIdMetadataFile() {
		return $this->getTemplatePath().'publicationFormatEditDummy.tlp';
	}

	/**
	 * @see PubIdPlugin::getSettingsFormName()
	 */
	function getSettingsFormName() {
		return 'form.URNDNBSettingsForm';
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
		$urnParts = explode(':', $pubId, 2);
		return count($urnParts) == 2 and substr($pubId, 0, 3) == "urn:";
	}

	/**
	 * Return the name of the corresponding DAO.
	 * @param $pubObject object
	 * @return DAO
	 */
	function &getDAO($pubObjectType) {
		$daos =  array(
				'PublishedMonograph' => 'PublishedMonographDAO',
				'Monograph' => 'MonographDAO',
		);
		$daoName = $daos[$pubObjectType];
		assert(!empty($daoName));
		return DAORegistry::getDAO($daoName);
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
	

	/**
	 * Add URN check number (s. http://www.persistent-identifier.de/?link=316)
	 * Adapted from code by Božana Bokan, Center for Digital Systems (CeDiS), Freie Universität Berlin
	 */
	function _calculateUrnCheckNo($urn) {
		$urn = strtoupper($urn);
	
		// numeric values that contain a 0 remain unassigned (marked by ua), as well as a few others
		$allowedChars = array('0', '1', '2', '3', '4', '5', '6', '7', '8', 'ua10', 'U',
				'R', 'N', 'B', 'D', 'E', ':', 'A', 'C', 'ua20', 'F', 'G',
				'H', 'I', 'J', 'L', 'M', 'O', 'P', 'ua30', 'Q', 'S', 'T',
				'V', 'W', 'X', 'Y', 'Z', '-', 'ua40', '9', 'K', '_', 'ua44', '/', 
				'ua46', '.', 'ua48', '+');

		$charMap = array_combine($allowedChars, range(1, 49));;
		
		$numUrn = "";
		for ($i = 0; $i < strlen($urn); $i++) {
			$numUrn .= $charMap[$urn[$i]];
		}
				
		$sum = 0;
		for ($j = 0; $j < strlen($numUrn); $j++) {
			$sum += $numUrn[$j] * ($j+1);
		}

		$quot = $sum / substr($numUrn, -1);
		
		return substr((string)floor($quot), -1);
	}
}

?>
