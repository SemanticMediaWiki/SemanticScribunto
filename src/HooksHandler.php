<?php

namespace SMW\Scribunto;

class HooksHandler
{

	/**
	 * Hook: ScribuntoExternalLibraries
	 *
	 * Allow extensions to add Scribunto libraries
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ScribuntoExternalLibraries
	 */
	public static function onScribuntoExternalLibraries( $engine, array &$extraLibraries ): bool
	{
		if ( $engine == 'lua' ) {
			$extraLibraries['mw.smw'] = ScribuntoLuaLibrary::class;
		}

		return true;
	}
}
