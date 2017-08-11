<?php

namespace Drupal\acb\Model;

interface AcbModelInterface {
	static public function load_by_id($id);
	static public function load_by_url($url);
	static public function save($url, array $data);
	static public function update($url, array $data, $id);
	static public function delete($id);
}
