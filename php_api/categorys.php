<?php
include 'condb.php';
header('Content-Type: application/json; charset=utf-8');

$action = $_POST['action'] ?? '';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $catStmt = $conn->prepare("SELECT * FROM categorys ORDER BY category_id ASC");
        $catStmt->execute();
        $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            "success" => true,
            "data" => $categories,
            "categories" => $categories
        ]);
        exit;
    }

    // เพิ่มสินค้า
    if ($action === 'add') {
        $name = $_POST['category_name'];

        $stmt = $conn->prepare("INSERT INTO categorys (category_name)
                                VALUES (?)");
        $stmt->execute([$name]);
        echo json_encode(["message" => "เพิ่มประเภทเรียบร้อย"]);
        exit;
    }

    // แก้ไขสินค้า
    if ($action === 'update') {
        $id = $_POST['category_id'];
        $name = $_POST['category_name'];


        $sql = "UPDATE categorys SET category_name=?";
        $params = [$name];

        $sql .= " WHERE category_id=?";
        $params[] = $id;

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        echo json_encode(["message" => "แก้ไขประเภทสำเร็จ"]);
        exit;
        }

    // ลบสินค้า
    if ($action === 'delete') {
        $id = $_POST['category_id'];
        $stmt = $conn->prepare("DELETE FROM categorys WHERE category_id=?");
        $stmt->execute([$id]);
        echo json_encode(["message" => "ลบประเภทเรียบร้อย"]);
        exit;
    }

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}