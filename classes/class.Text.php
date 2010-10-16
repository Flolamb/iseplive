<?php

/**
 * Helpers for texts
 */

class Text {
	
	/**
	 * Transforms a text in HTML, replacing links with <a> tags
	 *
	 * @param string $txt
	 * @param string
	 */
	public static function inHTML($txt){
		$txt = htmlspecialchars($txt);
		$txt = nl2br($txt);
		// Links
		$txt = preg_replace('#(?<=^|[^a-z"/])((?:http://|www\.)[^\s\(\),<>]*)(?=[\s\(\),<>]|$)#im', '<a href="\\1">\\1</a>', $txt);
		$txt = str_replace('<a href="www.', '<a href="http://www.', $txt);
		return $txt;
	}
	
}
