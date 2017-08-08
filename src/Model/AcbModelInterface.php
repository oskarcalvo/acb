<?php

namespace Drupal\acb\Model;

interface AcbModelInterface {
	public function load_by_id($id);
	public function load_by_url($url);
	public function save($url, array $data);
	public function update($url, array $data, $id);
	public function delete($id);
}
