<?php

    session_start();
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="jquery-3.7.0.js"></script>
    <link rel="stylesheet" href="styleStagiaire.css">
</head>
<body>

    <?php

        if(isset($_SESSION["loggedin"]) == false){

            header("location:login.php?va=1");
            exit();
        }
    
    ?>

    <?php include "chemin.php";

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['modifier'])){

        if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['telephone']) && isset($_POST['login']) && isset($_POST['pass']) ){

            $requetm = "update stagiaires set nom =:nom , prenom =:prenom , telephone = :tele,login = :login,motdepasse=:mot where numero = ".$_SESSION['numero'] ;
        
            $reqm = $link->prepare($requetm);

            $reqm->bindParam(":nom",$_POST["nom"]);
            $reqm->bindParam(":prenom",$_POST["prenom"]);
            $reqm->bindParam(":tele",$_POST["telephone"]);
            $reqm->bindParam(":login",$_POST["login"]);
            $reqm->bindParam(":mot",$_POST["pass"]);

            if($reqm->execute()){

                if($reqm->rowCount() > 0){

                    echo "<div style='color: green;padding: 10px ;text-align: center;' >
                            L'opération de la modification a été effectuée avec succès.
                        </div>";
                }
                else{

                    echo "<div style='color: red;padding: 10px ;text-align: center;' >
                            ATTENTION  :
                                Aucune modification effectuée.
                        </div>";
                }
            }
            else {
                    echo "<div style='color: red;' >
                            ATTENTION  :
                                Erreur lors de la modification de l'employé.
                        </div>";
            }

        }
}
            
            ?>

        <?php include "chemin.php";
            
            $requet = "select * from stagiaires where numero = :numero";

            $req = $link->prepare($requet);

            $req->bindParam(':numero',$_SESSION["numero"]);

            if($req->execute()){

                $table = $req->fetchAll(PDO::FETCH_ASSOC);

                if(!empty($table)){

                    $nom = $table[0]["nom"];
                    $prenom =  $table[0]["prenom"];
                    $tele = $table[0]["telephone"];
                    $login =  $table[0]["login"];
                    $mot = $table[0]["motdepasse"];
                }
            }
            
        ?>
    <h1>Bienvenue  <?php echo $nom ." ". $prenom ?> </h1>

    <a href="logout.php">log out</a>
    
    <br><br><br>

            <!-- LA PARTIE DES INFORMATIONS -->

    <div id="container">

        <!-- <img src="add.png" width="15px" heigth="15px"> -->
        <input type="button" value="-" id="classinput1">
        <label id="div1" > Information Personnelles</label>

        <div id="contenu" class="contient">

                        <!--  RECUPERER TOUT LES DONNEES    -->
            
            

                <!-- AFFICHER LES DONNEES -->

            <form action="" method="post">
                
                <label for="nom">nom:</label>
                <input type="text" name="nom" id="nom" value="<?php echo $nom ?>"><br>

                <label for="prenom">prenom:</label>
                <input type="text" name="prenom" id="prenom" value="<?php echo $prenom ?>"><br>

                <label for="telephone">telephone:</label>
                <input type="text" name="telephone" id="telephone" value="<?php echo  $tele ?>"><br>

                <label for="log">login :</label>
                <input type="text" name="login" id="log" value="<?php echo $login ?>"><br>

                <label for="pass">mot de passe : </label>
                <input type="text" name="pass" id="pass" value="<?php echo $mot ?>"><br>

                <button type="submit" name="modifier">Modifier</button><br>
            </form>

            <!-- MODIFICATION DES DONNEES -->

            
        </div>
    </div>



                            <!-- la partie 2 -->


    <div id="container1">
        <!-- <img src="add.png" width="15px" heigth="15px"> -->
        <input type="button" value="-" id="classinput2">
        <label id="div2" > Examens</label>

        <div id="contenu1" class="contient">

        <!-- les examens  -->
            <form action="" id="formulaire" method="post">

                <select name="examen" id="examen">
                    <option value="">selectionner un examen </option>

                    <?php include "chemin.php";

                        $requets = " select id from examens " ;
                    
                        $reqs = $link->prepare($requets);

                        if($reqs->execute()){

                            $ids = $reqs->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($ids as $key) {

                                $ex = $key["id"];

                                $selected = ($_POST['examen'] == $ex) ? "selected" : "";
                                echo "<option value='$ex' $selected > $ex </option>";
                            }
                            
                        }
                    
                    ?>
                </select>
            </form>

            <!-- les informations d'examen selectionner -->
    <?php include "chemin.php";

        if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['examen'])){
            if($_POST['examen'] != ""){

                    $requetm = "select * from examens where id = :id" ;
                    
                    $reqm = $link->prepare($requetm);

                    $reqm->bindParam(":id",$_POST['examen']);

                    if($reqm->execute()){

                        $examen = $reqm->fetchAll(PDO::FETCH_ASSOC);

                        // if(!empty($examen)){

                            $salle = $examen[0]["salle"];
                            $date = $examen[0]["date"];
                            $type = $examen[0]["type"];
                        // }
                        // else{
                        //     $salle = "inconnu";
                        //     $date = "inconnu";
                        //     $type = "inconnu";
                        // }
                    
                    }
                }
            }
           
    ?>

        <label> salle :</label>
        <span><?php echo (isset($salle))? $salle:" " ;?></span> <br>

        <label> date :</label>
        <span><?php echo (isset($date))? $date:" " ;?></span> <br>

        <label> type :</label>
        <span><?php echo (isset($type))? $type:" " ;?></span> <br>

        <?php

        // la note personnelle
        
        if($_SERVER['REQUEST_METHOD'] && isset($_POST['examen'])){
            if($_POST['examen'] != ""){

                $requete = "select * from notes where examen=:id and stagiaire = :numero" ;
                
                $reqe = $link->prepare($requete);

                $reqe->bindParam(":id",$_POST['examen']);
                $reqe->bindParam(":numero",$_SESSION['numero']);

                if($reqe->execute()){

                    $notes = $reqe->fetchAll(PDO :: FETCH_ASSOC);

                    if(!empty($notes)){
                        
                        $note = $notes[0]["note"];
                        // var_dump($notes);
                    }
                    else{
                        $note = " ";
                    }
                }
            }
        }
            

                
        ?>

        <label> note :</label>
        <span><?php echo (isset($note))? $note:" " ;?></span><br>
       
    <?php

        //  la note minimale
        if($_SERVER['REQUEST_METHOD'] && isset($_POST['examen'])){
            if($_POST['examen'] != ""){

                $requete = "select max(note) as 'MAX_note', min(note) as 'MIN_note',avg(note) as 'moyenne' from notes where examen=:id" ;
                    
                $reqe = $link->prepare($requete);

                $reqe->bindParam(":id",$_POST['examen']);

                if($reqe->execute()){

                    $notes = $reqe->fetchAll(PDO :: FETCH_ASSOC);

                    $noteN = $notes[0]["MIN_note"];
                    $noteM = $notes[0]["MAX_note"];
                    $note_M = $notes[0]["moyenne"];

                }
            }
        }
            

                
        ?>

            <label> Meilleure note :</label>
            <span><?php echo (isset($noteM))? $noteM:" " ;?></span><br>

            <label> Derniere note  :</label>
            <span><?php echo (isset($noteN))? $noteN:" " ;?></span><br>

            <label> la moyenne :</label>
            <span><?php echo (isset($note_M))? $note_M:" " ;?></span><br>

        </div>

        
    <script>

        $("#examen").change(function (event) {
                $("form").submit();
        })

        $("#classinput1").click(function() {
            var button = $("#classinput1");
            var contient = $("#contenu");

            if (button.val() == "+") {
                button.val("-");
                contient.slideDown();
            } else if (button.val() == "-") {
                button.val("+");
                contient.slideUp();
            }
        });

        $("#classinput2").click(function() {
            var button = $("#classinput2");
            var contient = $("#contenu1");

            if (button.val() == "+") {
                button.val("-");
                contient.slideDown();
            } else if (button.val() == "-") {
                button.val("+");
                contient.slideUp();
            }
        });



    </script>
</body>
</html>