<?php

class Category extends Database{
	public function addCategory($category_name) {
		$sql = "INSERT INTO categories (category_name) VALUES (?)";
		return $this->executeNonQuery($sql, [$category_name]);
	}

	public function addSubcategory($category_id, $subcategory_name) {
		$sql = "INSERT INTO subcategories (category_id, subcategory_name) VALUES (?, ?)";
		return $this->executeNonQuery($sql, [$category_id, $subcategory_name]);
	}

	public function getCategories() {
		$sql = "SELECT * FROM categories ORDER BY category_name ASC";
		return $this->executeQuery($sql);
	}

	public function getSubcategories($category_id) {
		$sql = "SELECT * FROM subcategories WHERE category_id = ? ORDER BY subcategory_name ASC";
		return $this->executeQuery($sql, [$category_id]);
	}

	public function updateCategory($category_id, $category_name) {
    $sql = "UPDATE categories SET category_name = ? WHERE category_id = ?";
    return $this->executeNonQuery($sql, [$category_name, $category_id]);
	}

public function deleteCategory($category_id) {
    // Optionally, delete subcategories first or use ON DELETE CASCADE in your schema
    $sql = "DELETE FROM categories WHERE category_id = ?";
    return $this->executeNonQuery($sql, [$category_id]);
	}

public function updateSubcategory($subcategory_id, $subcategory_name) {
    $sql = "UPDATE subcategories SET subcategory_name = ? WHERE subcategory_id = ?";
    return $this->executeNonQuery($sql, [$subcategory_name, $subcategory_id]);
	}

public function deleteSubcategory($subcategory_id) {
    $sql = "DELETE FROM subcategories WHERE subcategory_id = ?";
    return $this->executeNonQuery($sql, [$subcategory_id]);
	}
}

?>