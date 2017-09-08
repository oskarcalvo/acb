<?php

namespace Drupal\acb\Model;

use Drupal\acb\Model\AcbModelInterface;
use stdClass;

class AcbModelClass implements AcbModelInterface {
	
	const DDBBTABLE = 'acb_layout';
	
	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	static public function load_by_id($id){
		return self::load_data('acbid',$id,'=');
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
		$result = self::load_data(NULL,NULL,NULL,['acbid','url']);
		return $result;
	}
	/**
	 * @param $field
	 * @param $value
	 * @param $operator
	 *
	 * @return mixed
	 */
	static private function load_data($field = NULL,
																		$value = NULL,
																		$operator = NULL,
																		array $fields = NULL,
																		$pager = NULL,
																		$order = NULL)
  {
		$query = db_select(self::DDBBTABLE, 'acb');
		if (isset($pager)) {
			$query->extend('PagerDefault');
			
		}
		if(!is_null($field) & !is_null($value) & !is_null($operator) ) {
			$query->condition($field, $value, $operator);
		}
		if(is_null($fields)){
			$query->fields('acb');
		}
		else {
			$query->fields('acb', $fields);
		}
		
		$query->addTag('acb_load');
		
		if (isset($order)) {
			$query->orderby("acb.$order");
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
		$record->acbid = $id;
		return drupal_write_record(self::DDBBTABLE, $record, ['acbid']);
	}
	
	/**
	 * @param $id
   * @return boolean
	 */
	static public function delete($id){
	  $delete = db_delete(self::DDBBTABLE)
      ->condition('acbid', $id, '=')
      ->execute();
	  return $delete;
	}
	
	public static function url_exist($url) {
		$query = db_select(self::DDBBTABLE, 'acb')
			->condition('url', $url, '=')
			->fields('acb',['acbid']);
		$query->addTag('acb_url_exist');
		$result =  $query->execute();
		$records = $result->fetchAllAssoc('acbid');
		foreach ($records as $record){
			$output = $record->acbid;
		}
		return $output;
	}
	
}
