<?php

namespace Drupal\acb\Model;

use Drupal\acb\Model\AcbModelInterface;


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
		return $this->load_data_filtered_by_field('url',$url,'LIKE');
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
		
		return drupal_write_record(DDBBTABLE, $record);
	}
	
	/**
	 * @param $id
	 * @param $url
	 * @param array $data
	 *
	 * @return bool|int
	 */
	public function update($id, $url, array $data){
		
		$record = new stdClass();
		$record->url = $url;
		$record->data = serialize($data);
		$record->id = $id;
		
		return drupal_write_record(DDBBTABLE, $record, [$id]);
	}
	
	/**
	 * @param $id
	 */
	public function delete($id){
	
	}
	
}
