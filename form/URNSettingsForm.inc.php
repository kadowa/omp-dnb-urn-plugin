<?php

/**
 * @file plugins/pubIds/urn_dnb/classes/form/URNSettingsForm.inc.php
 *
 * Copyright (c) 2015 Heidelberg University
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class URNSettingsForm
 * @ingroup plugins_pubIds_urn
 *
 * @brief Form for press managers to setup URN plugin
 */


import('lib.pkp.classes.form.Form');

class URNSettingsForm extends Form {

	//
	// Private properties
	//
	/** @var integer */
	var $_pressId;

	/**
	 * Get the press ID.
	 * @return integer
	 */
	function _getPressId() {
		return $this->_pressId;
	}

	/** @var URNPubIdPlugin */
	var $_plugin;

	/**
	 * Get the plugin.
	 * @return URNPubIdPlugin
	 */
	function &_getPlugin() {
		return $this->_plugin;
	}


	//
	// Constructor
	//
	/**
	 * Constructor
	 * @param $plugin URNPubIdPlugin
	 * @param $pressId integer
	 */
	function URNSettingsForm(&$plugin, $pressId) {
		$this->_pressId = $pressId;
		$this->_plugin =& $plugin;

		parent::Form($plugin->getTemplatePath() . 'settingsForm.tpl');
		
		$this->addCheck(new FormValidatorRegExp($this, 'urnPrefix', 'required', 'plugins.pubIds.urn.manager.settings.urnPrefixPattern', '/^urn:nbn:de(:[a-z0-9.-]+)+$/'));
		$this->addCheck(new FormValidatorCustom($this, 'urnPublicationFormatSuffixPattern', 'required', 'plugins.pubIds.urn.manager.settings.urnPublicationFormatSuffixPatternRequired', create_function('$urnPublicationFormatSuffixPattern,$form', 'if ($form->getData(\'urnSuffix\') == \'pattern\') return $urnPublicationFormatSuffixPattern != \'\';return true;'), array(&$this)));
		$this->addCheck(new FormValidator($this, 'urnSuffix' ,'required', 'plugins.pubIds.urn.manager.settings.urnSuffixRequired'));
		$this->addCheck(new FormValidatorPost($this));

		
 		// for URN reset requests
		import('lib.pkp.classes.linkAction.request.RemoteActionConfirmationModal');
		$application = PKPApplication::getApplication();
		$request = $application->getRequest();
		$clearPubIdsLinkAction =
		new LinkAction(
				'reassignURNs',
				new RemoteActionConfirmationModal(
						__('plugins.pubIds.urn.manager.settings.urnReassign.confirm'),
						__('common.delete'),
						$request->url(null, null, 'plugin', null, array('verb' => 'settings', 'clearPubIds' => true, 'plugin' => $plugin->getName(), 'category' => 'pubIds')),
						'modal_delete'
				),
				__('plugins.pubIds.urn.manager.settings.urnReassign'),
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
			'urnPrefix' => 'string',
			'urnSuffix' => 'string',
			'urnPublicationFormatSuffixPattern' => 'string',
		);
	}
}

?>
