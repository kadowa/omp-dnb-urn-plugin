# URN DNB Plugin

> The URN DNB plugin for [Open Monograph Press][omp] (OMP) has been developed at UB Heidelberg. It enables the assignments of URNs according to the [policies of the Deutsche Nationalbibliothek (DNB)][dnb-policy] (Link in German) to monographs in OMP.

## Installation

	git clone https://github.com/kadowa/omp-dnb-urn-plugin omp/plugins/pubIds/urn_dnb
	php omp/tools/upgrade.php upgrade

## Configuration

After installation, enable and configure the plugin in `Management > Settings > Website > Plugins > Public Identifier Plugins > DNB URN > Settings`: specifiy a DNB URN Prefix and a pattern for Namespace Specific Strings to generate unique URNs.

## Bugs / Issues

You can report issues here: <https://github.com/kadowa/omp-dnb-urn-plugin/issues>

## License

This software is released under the the [GNU General Public License][gpl-licence].

See the [COPYING][gpl-licence] included with OMP for the terms of this license.

[omp]: https://github.com/pkp/omp
[dnb-policy]: http://nbn-resolving.org/urn:nbn:de:101-2012121200
[gpl-licence]: https://github.com/pkp/omp/blob/master/docs/COPYING
