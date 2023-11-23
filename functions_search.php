<?php
function search_products($keyword) {
    global $db;
    $escaped_keyword = $db->escape($keyword);

    $sql = "SELECT p.id, p.name, p.quantity, p.buy_price, p.sale_price, p.media_id, p.date, c.name AS categorie, m.file_name AS image ";
    $sql .= "FROM products p ";
    $sql .= "LEFT JOIN categories c ON c.id = p.categorie_id ";
    $sql .= "LEFT JOIN media m ON m.id = p.media_id ";
    $sql .= "WHERE p.name LIKE '%$escaped_keyword%' ";
    $sql .= "ORDER BY p.id ASC";

    return find_by_sql($sql);
}
?>
