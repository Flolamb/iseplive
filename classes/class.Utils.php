<?php

/**
 * Miscellaneous Helpers
 */

class Utils {

	/**
	 * Sort an multidimensional array by comparison between a field and another array
	 *
	 * @param array $array			Array to be sorted
	 * @param int|string $id_field	Field's name in sub-array for sorting
	 * @param array $ids			Ids used for sorting
	 */
	public static function arraySort(&$array, $id_field, $ids){
		self::$array_sort_ids = array_flip($ids);
		self::$array_sort_id_field = $id_field;
		usort($array, array('Utils', 'arraySortCmp'));
	}
	private static $array_sort_ids;
	private static $array_sort_id_field;
	private static function arraySortCmp($a, $b){
		$id1 = $a[self::$array_sort_id_field];
		$id2 = $b[self::$array_sort_id_field];
		if(!isset(self::$array_sort_ids[$id1]) || !isset(self::$array_sort_ids[$id2]))
			return 0;
		return self::$array_sort_ids[$id1] < self::$array_sort_ids[$id2] ? -1 : 1;
	}
	
}
