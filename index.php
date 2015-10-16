<?php

/**
 * @defgroup plugins_pubIds_urn URN PID plugin
 */

/**
 * @file plugins/pubIds/urn_dnb/index.php
 *
 * Copyright (c) 2015 Heidelberg University
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_pubIds_doi
 * @brief Wrapper for DNB URN plugin.
 *
 */
require_once('URNDNBPubIdPlugin.inc.php');

return new URNDNBPubIdPlugin();

?>
