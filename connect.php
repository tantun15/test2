<?php
    $db_servername = "localhost";
    $db_user = "root";
    $db_pass = "1234";
    $db_name = "rfs2";
    try {
            $conn = new PDO("mysql:host=$db_servername;dbname=$db_name", $db_user, $db_pass);
            $conn->exec("set names utf8");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        } catch(PDOException $e) {
            echo "Connection failed:" . $e->getMessage();
        }

	?>