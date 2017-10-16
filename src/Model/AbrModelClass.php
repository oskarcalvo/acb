<?php

namespace Drupal\abr\Model;

use Drupal\abr\Model\AbrModelInterface;
use stdClass;

class AbrModelClass implements abrModelInterface {
	
	const DDBBTABLE = 'abr_layout';
	
	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	static public function load_by_id($id){
		return self::load_data('abrid',$id,'=');
	}
	
	/**
	 * @param $url
	 *
	 * @return mixed
	 */
	static function load_by_url($url){
		$result =  self::load_data('url',$url,'LIKE');
		if(count($result) === 1){
			return $result[0];
		}
		return FALSE;
	}
	
	/**
	 * @return array
	 */
	static function list_of_items() {
		$result = self::load_data(NULL,NULL,NULL,['abrid','url']);
		return $result;
	}
  
  /**
   * @param null $field
   * @param null $value
   * @param null $operator
   * @param array|NULL $fields
   * @param null $pager
   * @param null $order
   * @return mixed
   */
	static private function load_data($field = NULL,
																		$value = NULL,
																		$operator = NULL,
																		array $fields = NULL,
																		$pager = NULL,
																		$order = NULL)
  {
		$query = db_select(self::DDBBTABLE, 'abr');
		if (isset($pager)) {
			$query->extend('PagerDefault');
			
		}
		if(!is_null($field) & !is_null($value) & !is_null($operator) ) {
			$query->condition($field, $value, $operator);
		}
		if(is_null($fields)){
			$query->fields('abr');
		}
		else {
			$query->fields('abr', $fields);
		}
		
		$query->addTag('abr_load');
		
		if (isset($order)) {
			$query->orderby("abr.$order");
		}
		$result= $query->execute();
	  $records = $result->fetchAll();
		return $records;
	}
	
	/**
	 * @param $url
	 * @param array $data
	 *
	 * @return bool|int
	 */
	static public function save($url, array $data){
		
		$record = new stdClass();
		$record->url = $url;
		$record->data = serialize($data);
		return drupal_write_record(self::DDBBTABLE, $record);
	}
	
	/**
	 * @param $id
	 * @param $url
	 * @param array $data
	 *
	 * @return bool|int
	 */
	static public function update($url, array $data, $id){
		
		$record = new stdClass();
		$record->url = $url;
		$record->data = serialize($data);
		$record->abrid = $id;
		return drupal_write_record(self::DDBBTABLE, $record, ['abrid']);
	}
	
	/**
	 * @param $id
   * @return boolean
	 */
	static public function delete($id){
	  $delete = db_delete(self::DDBBTABLE)
      ->condition('abrid', $id, '=')
      ->execute();
	  return $delete;
	}
	
	public static function url_exist($url) {
		$query = db_select(self::DDBBTABLE, 'abr')
			->condition('url', $url, '=')
			->fields('abr',['abrid']);
		$query->addTag('abr_url_exist');
		$result =  $query->execute();
		$records = $result->fetchAllAssoc('abrid');
		foreach ($records as $record){
			$output = $record->abrid;
		}
		return $output;
	}
	
}
