<?php

/**
 * @file plugins/pubIds/urn_dnb/classes/form/URNDNBSettingsForm.inc.php
 *
 * Copyright (c) 2014-2015 Simon Fraser University Library
 * Copyright (c) 2000-2015 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class URNDNBSettingsForm
 * @ingroup plugins_pubIds_urndnb
 *
 * @brief Form for press managers to setup URNDNB plugin
 */


import('lib.pkp.classes.form.Form');

class URNDNBSettingsForm extends Form {

	/** @var integer */
	var $_pressId;

	/**
	 * Get the press ID.
	 * @return integer
	 */
	function _getPressId() {
		return $this->_pressId;
	}

	/** @var URNDNBPubIdPlugin */
	var $_plugin;

	/**
	 * Get the plugin.
	 * @return URNDNBPubIdPlugin
	 */
	function &_getPlugin() {
		return $this->_plugin;
	}


	/**
	 * Constructor
	 * @param $plugin URNDNBPubIdPlugin
	 * @param $pressId integer
	 */
	function URNDNBSettingsForm(&$plugin, $pressId) {
		$this->_pressId = $pressId;
		$this->_plugin =& $plugin;

		parent::Form($plugin->getTemplatePath() . 'settingsForm.tpl');
		
		$this->addCheck(new FormValidatorRegExp($this, 'urnDNBPrefix', 'required', 'plugins.pubIds.urnDNB.manager.settings.urnDNBPrefixPattern', '/^urn:nbn(:[a-z0-9.-]+)+$/'));
		$this->addCheck(new FormValidatorCustom($this, 'urnDNBSuffixPattern', 'required', 'plugins.pubIds.urnDNB.manager.settings.urnDNBSuffixPatternRequired', create_function('$urnDNBSuffixPattern,$form', 'if ($form->getData(\'urnDNBSuffix\') == \'pattern\') return $urnDNBSuffixPattern != \'\';return true;'), array(&$this)));
		$this->addCheck(new FormValidatorRegExp($this, 'urnDNBSuffixPattern', 'optional', 'plugins.pubIds.urnDNB.manager.settings.urnDNBSuffixPatternFormat', '/^-[a-z0-9\.\-]*%[mp][a-z0-9\.\-]*%[mp][a-z0-9\.\-]*$/'));
		$this->addCheck(new FormValidatorRegExp($this, 'urnDNBSuffixPattern', 'optional', 'plugins.pubIds.urnDNB.manager.settings.urnDNBSuffixPatternFormat', '/%m/'));
		$this->addCheck(new FormValidator($this, 'urnDNBSuffix' ,'required', 'plugins.pubIds.urnDNB.manager.settings.urnDNBSuffixRequired'));
		$this->addCheck(new FormValidatorPost($this));

		
 		// for URNDNB reset requests
		import('lib.pkp.classes.linkAction.request.RemoteActionConfirmationModal');
		$application = PKPApplication::getApplication();
		$request = $application->getRequest();
		$clearPubIdsLinkAction =
		new LinkAction(
				'reassignURNDNBs',
				new RemoteActionConfirmationModal(
						__('plugins.pubIds.urnDNB.manager.settings.urnDNBReassign.confirm'),
						__('common.delete'),
						$request->url(null, null, 'manage', null, array('verb' => 'settings', 'clearPubIds' => true, 'plugin' => $plugin->getName(), 'category' => 'pubIds')),
						'modal_delete'
				),
				__('plugins.pubIds.urnDNB.manager.settings.urnDNBReassign'),
				'delete'
		);
		$this->setData('clearPubIdsLinkAction', $clearPubIdsLinkAction);		
		$this->setData('pluginName', $plugin->getName());
	}


	//
	// Implement template methods from Form
	//
	/**
	 * @see Form::initData()
	 */
	function initData() {
		$pressId = $this->_getPressId();
		$plugin =& $this->_getPlugin();
		foreach($this->_getFormFields() as $fieldName => $fieldType) {
			$this->setData($fieldName, $plugin->getSetting($pressId, $fieldName));
		}
	}

	/**
	 * @see Form::readInputData()
	 */
	function readInputData() {
		$this->readUserVars(array_keys($this->_getFormFields()));
	}

	/**
	 * @see Form::execute()
	 */
	function execute() {
		$plugin =& $this->_getPlugin();
		$pressId = $this->_getPressId();
		foreach($this->_getFormFields() as $fieldName => $fieldType) {
			$plugin->updateSetting($pressId, $fieldName, $this->getData($fieldName), $fieldType);
		}
	}


	//
	// Private helper methods
	//
	function _getFormFields() {
		return array(
			'urnDNBPrefix' => 'string',
			'urnDNBSuffix' => 'string',
			'urnDNBSuffixPattern' => 'string',
		);
	}
}

?>
