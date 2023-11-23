<?php
// -----------------------------------------------------------------------
// DEFINE SEPARATOR ALIASES
// -----------------------------------------------------------------------
define("URL_SEPARATOR", '/');

define("DS", DIRECTORY_SEPARATOR);

// -----------------------------------------------------------------------
// DEFINE ROOT PATHS
// -----------------------------------------------------------------------
defined('SITE_ROOT')? null: define('SITE_ROOT', realpath(dirname(__FILE__)));
define("LIB_PATH_INC", SITE_ROOT.DS);

require_once(LIB_PATH_INC.'config.php');
require_once(LIB_PATH_INC.'functions.php');
require_once(LIB_PATH_INC.'session.php');
require_once(LIB_PATH_INC.'upload.php');
require_once(LIB_PATH_INC.'database.php');
require_once(LIB_PATH_INC.'sql.php');

if (!function_exists('search_products')) {
    function search_products($category_id, $product_title) {
        global $db;

        // Initialize the WHERE clause
        $where_clause = " WHERE 1 = 1";

        // Apply category filter if selected
        if (!empty($category_id)) {
            $where_clause .= " AND p.categorie_id = {$category_id}";
        }

        // Apply product title filter if entered
        if (!empty($product_title)) {
            $where_clause .= " AND p.name LIKE '%{$product_title}%'";
        }

        $sql = "SELECT p.*, c.name as category_name FROM products p";
        $sql .= " LEFT JOIN categories c ON p.categorie_id = c.id";
        $sql .= $where_clause;
        $sql .= " ORDER BY p.id DESC";

        return find_by_sql($sql);
    }
}