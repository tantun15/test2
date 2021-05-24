<?php
  require_once "connect.php" ;
  $res = [];
  $invoice_id = $_POST["id"];
  $sql = "SELECT * FROM invoice_item WHERE invoice_id = :invoice_id ";
  $res = $conn->prepare($sql);
  $res->execute([":invoice_id" => $invoice_id]);
  $data['res'] =  $res->fetchAll(PDO::FETCH_ASSOC);
  exit(json_encode($data));

?> 
