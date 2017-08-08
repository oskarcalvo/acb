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
	public function load_by_id($id){
		return $this->load_data_filtered_by_field('acbid',$id,'=');
	}
	
	/**
	 * @param $url
	 *
	 * @return mixed
	 */
	public function load_by_url($url){
		$result =  $this->load_data_filtered_by_field('url',$url,'LIKE');
		if(count($result) === 1){
			return $result[0];
		}
		return FALSE;
	}
	
	/**
	 * @param $field
	 * @param $value
	 * @param $operator
	 *
	 * @return mixed
	 */
	private function load_data_filtered_by_field($field, $value, $operator) {
		$query = db_select(self::DDBBTABLE, 'acb')
			->condition($field,$value, $operator)
			->fields('acb', array('acbid','url','data'));
		$query->addTag('acb_load');
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
	public function save($url, array $data){
		
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
	public function update($url, array $data, $id){
		
		$record = new stdClass();
		$record->url = $url;
		$record->data = serialize($data);
		$record->acbid = $id;
		return drupal_write_record(self::DDBBTABLE, $record, ['acbid']);
	}
	
	/**
	 * @param $id
	 */
	public function delete($id){
	
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
