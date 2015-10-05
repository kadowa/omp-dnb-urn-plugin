<?php

/**
 * @defgroup plugins_pubIds_doi DOI PID plugin
 */

/**
 * @file plugins/pubIds/urn/index.php
 *
 * Copyright (c) 2015 Heidelberg University
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_pubIds_doi
 * @brief Wrapper for URN plugin.
 *
 */
require_once('URNPubIdPlugin.inc.php');

return new URNPubIdPlugin();

?>
