<?php
  require_once "connect.php" ;
    
    $input = json_decode($_POST["json"], true);
    $keyword = $input["keyword"];
    // $query = $input["query"];
    $page = $input["page"];

    $data = [];
    ## Total number of records with filtering
    $count = $conn->prepare("SELECT COUNT(*) AS allcount FROM invoice WHERE invoice_id LIKE '%$keyword%' OR invoice_number LIKE '%$keyword%' OR name LIKE '%$keyword%' 
    OR address LIKE '%$keyword%' OR telephone LIKE '%$keyword%' OR email LIKE '%$keyword%' ");
    $count->execute();
    $records = $count->fetch(PDO::FETCH_ASSOC);
    $totalRecords = $records['allcount'];

    $limit = 10;
    $startFrom = ( $page - 1 ) * $limit; 
    $ttp = ceil($totalRecords/$limit);
  //ถ้าค่า keyword ไม่ว่างจะแสดงข้อมูลตามที่กรอก ถ้าว่างจะโชว์ค่าทั้งหมด
if(!empty($keyword)){
    $sql = "SELECT * FROM invoice WHERE invoice_id LIKE '%$keyword%' OR invoice_number LIKE '%$keyword%' OR name LIKE '%$keyword%' 
    OR address LIKE '%$keyword%' OR telephone LIKE '%$keyword%' OR email LIKE '%$keyword%' LIMIT $startFrom, $limit";
    // $stmt = $conn->prepare($sql);
    // $stmt->execute();
    // $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // $data["page"] = $ttp;
    // $data["curr"] = $page;
    // exit(json_encode($data));

}else{
    $sql = "SELECT * FROM invoice LIMIT $startFrom, $limit ";
    // $stmt = $conn->prepare($sql);
    // $stmt->execute();
    // $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // $data["page"] = $ttp;
    // $data["curr"] = $page;
    // exit(json_encode($data));
}
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $data["page"] = $ttp;
    $data["curr"] = $page;
    exit(json_encode($data));


?> 
