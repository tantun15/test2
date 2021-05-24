<?php
    require_once "connect.php";

    $input = json_decode($_POST["json"], true);
    $query = $input["query"];
    $page = $input["page"];

    $data = [];
    ## Total number of records without filtering
    $count = $conn->prepare("SELECT COUNT(*) AS allcount FROM invoice");
    $count->execute();
    $records = $count->fetch(PDO::FETCH_ASSOC);
    $totalRecords = $records['allcount'];

    $limit = 10;
    $startFrom = ( $page - 1 ) * $limit; 
    $ttp = ceil($totalRecords/$limit);

    $sql = "SELECT * FROM invoice LIMIT $startFrom, $limit";  
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // $data["result"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $data["page"] = $ttp;
    $data["curr"] = $page;

    exit(json_encode($data));
   

?>  