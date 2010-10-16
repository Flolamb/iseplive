<?php

/**
 * Mimetypes helpers
 */

class Mimetype {
	
	public static $mimetypes = array(
		// Images
		'jpg' => array('jpg.png', 'image/jpeg', 'Image JPEG'),
		'jpe' => array('jpg.png', 'image/jpeg', 'Image JPEG'),
		'jpeg' => array('jpg.png', 'image/jpeg', 'Image JPEG'),
		'png' => array('jpg.png', 'image/png', 'Image PNG'),
		'gif' => array('gif.png', 'image/gif', 'Image GIF'),
		'tif' => array('gif.png', 'image/tiff', 'Image TIFF'),
		'tiff' => array('gif.png', 'image/tiff', 'Image TIFF'),
		'bmp' => array('bmp.png', 'image/x-ms-bmp', 'Image Bitmap'),
		'ico' => array('gif.png', 'image/x-ico', 'Icône'),
		// Web pages
		'html' => array('html.png', 'text/html', 'Page HTML', true),
		'htm' => array('html.png', 'text/html', 'Page HTML', true),
		'xhtml' => array('html.png', 'application/xhtml+xml', 'Page XHTML', true),
		'xht' => array('html.png', 'application/xhtml+xml', 'Page XHTML', true),
		'xml' => array('html.png', 'text/xml', 'Document XML', true),
		'xsl' => array('html.png', 'application/xml', 'Feuille XSL', true),
		'php' => array('php.png', 'text/html', 'Page PHP', true),
		'php3' => array('php.png', 'text/html', 'Page PHP', true),
		'php4' => array('php.png', 'text/html', 'Page PHP', true),
		'php5' => array('php.png', 'text/html', 'Page PHP', true),
		'phtml' => array('php.png', 'text/html', 'Page PHP', true),
		'inc' => array('php.png', 'text/html', 'Page PHP', true),
		'wml' => array('html.png', 'text/vnd.wap.wml', 'Page WAP', true),
		'js' => array('js.png', 'application/x-javascript', 'Fichier Javascript', true),
		'wmls' => array('js.png', 'text/vnd.wap.wmlscript', 'Fichier WMLScript', true),
		'css' => array('css.png', 'text/css', 'Feuille CSS', true),
		// Media
		'mp3' => array('mp3.png', 'audio/mpeg', 'Son MP3'),
		'mp2' => array('mp3.png', 'audio/mpeg', 'Son MP2'),
		'mpga' => array('mp3.png', 'audio/mpeg', 'Son Mpga'),
		'm3u' => array('mp3.png', 'audio/x-mpegurl', 'Playlist M3u'),
		'wav' => array('mp3.png', 'audio/x-wav', 'Son WAV'),
		'wma' => array('mp3.png', 'audio/wma', 'Son WMA'),
		'mid' => array('mid.png', 'audio/midi', 'Son Midi'),
		'midi' => array('mid.png', 'audio/midi', 'Son Midi'),
		'kar' => array('mid.png', 'audio/midi', 'Son Midi'),
		'avi' => array('avi.png', 'video/x-msvideo', 'Vidéo AVI'),
		'divx' => array('avi.png', 'video/x-msvideo', 'Vidéo DivX'),
		'video' => array('avi.png', 'video/x-sgi-movie', 'Vidéo'),
		'mpg' => array('avi.png', 'video/mpeg', 'Vidéo MPEG'),
		'mpe' => array('avi.png', 'video/mpeg', 'Vidéo MPEG'),
		'mpa' => array('avi.png', 'video/mpeg', 'Vidéo MPEG'),
		'mpeg' => array('avi.png', 'video/mpeg', 'Vidéo MPEG'),
		'qt' => array('mov.png', 'video/quicktime', 'Vidéo Quicktime'),
		'mov' => array('mov.png', 'video/quicktime', 'Vidéo Quicktime'),
		'swf' => array('swf.png', 'application/x-shockwave-flash', 'Animation Flash'),
		// Compression and encryption
		'zip' => array('zip.png', 'application/zip', 'Archive ZIP'),
		'tar' => array('zip.png', 'application/x-tar', 'Archive TAR'),
		'gz' => array('zip.png', 'application/x-gzip', 'Archive GZIP'),
		'tgz' => array('zip.png', 'application/x-gzip', 'Archive GZIP'),
		'rar' => array('zip.png', 'application/x-rar', 'Archive WinRAR'),
		'pgp' => array('pgp.png', 'application/pgp-encrypted', 'Archive PGP'),
		'asc' => array('pgp.png', 'application/pgp-keys', 'Clé PGP', true),
		'sig' => array('pgp.png', 'application/pgp-signature', 'Signature PGP', true),
		// Documents
		'tex' => array('tex.png', 'application/x-tex', 'Document Tex'),
		'txt' => array('txt.png', 'text/plain', 'Fichier Texte', true),
		'htaccess' => array('txt.png', 'text/plain', 'Config Apache', true),
		'htpasswd' => array('txt.png', 'text/plain', 'Identifiants', true),
		'rtf' => array('txt.png', 'application/rtf', 'Document Texte'),
		'doc' => array('doc.png', 'application/msword', 'Document Texte'),
		'odt' => array('txt.png', 'application/msword', 'Document Texte'),
		'sxw' => array('txt.png', 'application/msword', 'Document Texte'),
		'xls' => array('txt.png', 'application/vnd.ms-excel', 'Tableau Excel'),
		'ods' => array('txt.png', 'application/vnd.ms-excel', 'Tableau ODS'),
		'sxc' => array('txt.png', 'application/vnd.ms-excel', 'Tableau SXC'),
		'pdf' => array('pdf.png', 'application/pdf', 'Document PDF'),
		'ppt' => array('ppt.png', 'application/vnd.ms-powerpoint', 'Présentation PowerPoint'),
		'man' => array('man.png', 'application/x-troff-man', 'Fichier Man'),
		'log' => array('log.png', 'text/plain', 'Fichier Log', true),
		'fon' => array('fon.png', 'application/octet-stream', 'Police'),
		'ttf' => array('ttf.png', 'application/octet-stream', 'Police'),
		'vcard' => array('vcard.png', 'application/vnd.groove-vcard', 'Fiche VCard'),
		// Executables & compiled
		'exe' => array('exe.png', 'application/octet-stream', 'Exécutable Windows'),
		'bat' => array('exe.png', 'text/plain', 'Exécutable MS-DOS', true),
		'com' => array('exe.png', 'application/octet-stream', 'Exécutable MS-DOS'),
		'bin' => array('bin.png', 'application/octet-stream', 'Binaire'),
		'so' => array('bin.png', 'application/octet-stream', 'Bibliothèque'),
		'sh' => array('sh.png', 'application/x-sh', 'Script Shell', true),
		'make' => array('make.png', 'application/octet-stream', ''),
		'rpm' => array('rpm.png', 'application/octet-stream', 'Package RPM'),
		'deb' => array('deb.png', 'application/octet-stream', 'Package Debian'),
		'dll' => array('bin.png', 'application/octet-stream', 'Librairie DLL'),
		'class' => array('java.png', 'application/octet-stream', 'Classe'),
		// Source files & DB
		'rb' => array('rb.png', 'text/plain', 'Source Ruby', true),
		'java' => array('java.png', 'text/plain', 'Source Java', true),
		'jsp' => array('java.png', 'text/plain', 'Source Java', true),
		'c' => array('c.png', 'text/plain', 'Source C', true),
		'cpp' => array('cpp.png', 'text/plain', 'Source C++', true),
		'cs' => array('cs.png', 'text/plain', 'Source C#', true),
		'pl' => array('pl.png', 'text/plain', 'Fichier Perl', true),
		'py' => array('py.png', 'text/plain', 'Source Python', true),
		's' => array('s.png', 'text/plain', '', true),
		'src' => array('src.png', 'application/x-wais-source', 'Fichier Source'),
		'y' => array('y.png', 'text/plain', '', true),
		'o' => array('o.png', 'application/x-object', 'Code objet', true),
		'l' => array('l.png', 'text/plain', '', true),
		'h' => array('h.png', 'text/x-chdr', 'En-tête C', true),
		'f' => array('f.png', 'text/x-fortran', 'Source Fortran', true),
		'db' => array('db.png', 'application/octet-stream', 'Base de données'),
		'sql' => array('db.png', 'text/x-sql', 'Code SQL', true),
		'fla' => array('fla.png', 'application/octet-stream', 'Source Flash'),
		'as' => array('as.png', 'text/plain', 'Source Actionscript', true)
	);
	
	
	/**
	 * Returns the URL of the icon corresponding to a file extension
	 *
	 * @param string $ext	File extension
	 * @return string		Icon URL
	 */
	public static function getIcon($ext){
		if(self::$mimetypes[$ext])
			$icon = self::$mimetypes[$ext][0];
		else
			$icon = 'unknown.png';
		return Config::URL_STATIC.'images/filetypes/'.$icon;
	}
	
}