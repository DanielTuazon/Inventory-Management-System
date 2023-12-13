<?php

include('connection.php');

// Query suppliers
$stmt = $conn->prepare("SELECT id, supplier_name FROM suppliers");
$stmt->execute();
$rows = $stmt->fetchAll();

$categories = [];
$bar_chart_data = [];

$colors = ['#FF0000','#0000FF','#FF00FF','#00FF00','#FFFF00','#00FFFF','#FFC0CB','#FFA500','#7FFFD4'];
$counter = 0;

// Query supplier product count
foreach($rows as $key => $row){

    $id = $row['id'];
    $categories[] = $row['supplier_name'];

    // Query count
    $stmt = $conn->prepare("SELECT COUNT(*) as p_count FROM productsuppliers WHERE productsuppliers.supplier='" . $id . "'");
    $stmt->execute();
    $row = $stmt->fetch();

    $count = $row['p_count'];

    //What this means is if not set the counter is set to zero
    if(!isset($colors[$key])) $counter=0;

    $bar_chart_data[] = [
        'y' => (int) $count,
        'color' => $colors[$counter]
    ];

    $counter++;
}

?>