<?php
//+ Jonas Raoni Soares Silva
//@ http://jsfromhell.com

class Language {
	private static $language = null;

	public static function get(){
		new Language;
		return self::$language;
	}
	public static function getBestMatch($langs = array()){
		foreach($langs as $n => $v)
			$langs[$n] = strtolower($v);
		$r = array();
		foreach(self::get() as $l => $v){
			($s = strtok($l, '-')) != $l && $r[$s] = 0;
			if(in_array($l, $langs))
				return $l;
		}
		foreach($r as $l => $v)
			if(in_array($l, $langs))
				return $l;
		return null;
	}
	private function __construct(){
		if(self::$language !== null)
			return;
		if(($list = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']))){
			if(preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)(?:;q=([0-9.]+))?/', $list, $list)){
				self::$language = array_combine($list[1], $list[2]);
				foreach(self::$language as $n => $v)
					self::$language[$n] = +$v ? +$v : 1;
				arsort(self::$language);
			}
		}
		else
			self::$language = array();
	}
}

Language::get();
$lang = Language::getBestMatch(array('fr', 'en', 'es'));

setcookie("lang", $lang, time() + 3600*24*365);
header("Location: $lang/");
?>