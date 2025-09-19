<?php

class Category extends Database{

	public function getCategories() {
		$sql = "SELECT * FROM categories ORDER BY category_name ASC";
		return $this->executeQuery($sql);
	}

	public function getSubcategories($category_id) {
		$sql = "SELECT * FROM subcategories WHERE category_id = ? ORDER BY subcategory_name ASC";
		return $this->executeQuery($sql, [$category_id]);
	}

}

?>