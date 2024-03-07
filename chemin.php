<?php

    $servername = "localhost";
    $BD = "ecole";
    $user = 'root';
    $pw = "";
    try{

        $link=new PDO("mysql:host=$servername;dbname=$BD",$user,$pw);

    }
    catch(PDOException $ex)
    {
        echo ($ex->getMessage());
        exit();
    }
    
?>