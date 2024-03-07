<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="monstyle.css">
</head>
<body>
    <form action="" method="post">

        <label for="log">log in :</label>
        <input type="text" name="login" id="log"><br>

        <label for="pass">mot de passe : </label>
        <input type="password" name="pass" id="pass"><br>

        <button type="submit">LOG IN</button><br>
    </form>


    <?php include "chemin.php";
    
        if($_SERVER['REQUEST_METHOD'] == "POST"){

            if(isset($_POST["login"]) && isset($_POST["pass"])){

                $requeta = "select * from stagiaires where login = :login and motdepasse = :passe";

                $reqi = $link->prepare($requeta);

                $reqi->bindParam(":login",$_POST['login']);
                $reqi->bindParam(":passe",$_POST['pass']);

                if($reqi->execute()){

                    if($reqi->rowCount() > 0){

                        $table = $reqi->fetchAll(PDO::FETCH_ASSOC);

                        $numero = $table[0]['numero'];
                        $nom = $table[0]['nom'];
                        $prenom = $table[0]['prenom'];

                        $_SESSION['loggedin'] = true ;
                        $_SESSION['numero'] = $numero ;
                        header('location:stagiaire.php');
                        exit();
                        
                    }
                    else{

                        echo "<div style='background-color: rgba(241, 174, 174, 0.941);color: red;border: 2px solid red;padding: 10px ;text-align: center;border-radius: 15px;margin: 0 ;position: absolute;top: 10%;left: 50%;transform: translate(-50%, -50%);' >
                                    ATTENTION  :
                                        Un des donnees incorrect .
                              </div>";
                    }
                }
                else{
                    echo "<div style='background-color: rgba(241, 174, 174, 0.941);color: red;border: 2px solid red;padding: 10px ;text-align: center;border-radius: 15px;margin: 0 ;position: absolute;top: 10%;left: 50%;transform: translate(-50%, -50%);' >
                        ATTENTION  :
                            Erreur lors de la recuperation du stagiaire.
                      </div>";
                }

            }

        }


        if(isset($_GET['va'])){

            echo "<div style='background-color: rgba(241, 174, 174, 0.941);color: red;border: 2px solid red;padding: 10px ;text-align: center;border-radius: 15px;margin: 0 ;position: absolute;top: 10%;left: 50%;transform: translate(-50%, -50%);' >
                        ATTENTION  :
                            vous devez vous connecter.
                    </div>";
        }
    
    ?>
</body>
</html>