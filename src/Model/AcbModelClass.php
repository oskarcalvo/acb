<?php

use Drupal\acb\Model\AcbModelInterface;


class AcbModelClass implements AcbModelInterface {
	
	const DDBBTABLE = 'acb_layout';
	
	/**
	 * @param $id
	 */
	public function load_by_id($id){
	
	}
	
	/**
	 * @param $url
	 */
	public function load_by_url($url){
	
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
