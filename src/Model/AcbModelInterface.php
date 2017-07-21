<?php

namespace Drupal\acb\Model;

interface AcbModelInterface {
	public function load_by_id($id);
	public function load_by_url($url);
	public function save($url, array $data);
	public function update($id, $url, array $data);
	public function delete($id);
}
