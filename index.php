<?php

/**
 * @defgroup plugins_pubIds_urndnb URN PID plugin
 */

/**
 * @file plugins/pubIds/urn_dnb/index.php
 *
 * Copyright (c) 2014-2015 Simon Fraser University Library
 * Copyright (c) 2000-2015 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_pubIds_urndnb
 * @brief Wrapper for DNB URN plugin.
 *
 */
require_once('URNDNBPubIdPlugin.inc.php');

return new URNDNBPubIdPlugin();

?>
