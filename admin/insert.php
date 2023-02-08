<?php
     //recuperer la BDD
    require 'database.php';
    //variable pour les champ d'erreurs et valeurs
    $nameError = $descriptionError = $priceError = $categoryError = $imageError = $name = $description = $price = $category = $image = "";
    //si manquant a la soumission (booléen) avec la methode "post"
    if(!empty($_POST)) {
        $name               = checkInput($_POST['name']);
        $description        = checkInput($_POST['description']);
        $price              = checkInput($_POST['price']);
        $category           = checkInput($_POST['category']); 
        $image              = checkInput($_FILES["image"]["name"]);
        $imagePath          = '../images/'. basename($image);//path chemin de l'image
        $imageExtension     = pathinfo($imagePath,PATHINFO_EXTENSION);//extension de l'image (png,gif,jpeg...)
        $isSuccess          = true;
        $isUploadSuccess    = false;//
        //si non est vide
        if(empty($name)) {
            $nameError = 'doit comporter un nom';
            $isSuccess = false;
        }//si la descritpion est vide
        if(empty($description)) {
            $descriptionError = 'doit comporter une description';
            $isSuccess = false;
        } //si prix vide
        if(empty($price)) {
            $priceError = 'doit comporter des chiffre';
            $isSuccess = false;
        } //categorie non selectioné
        if(empty($category)) {
            $categoryError = 'une categorie doit etre selectionné';
            $isSuccess = false;
        }//si image vde
        if(empty($image)) {
            $imageError = 'une image doit etre uploader';
            $isSuccess = false;
        }
        else {
            //si l'extenion n'est pas bonne 
            $isUploadSuccess = true;
            if($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif" ) {
                $imageError = "Les fichiers autorises sont: .jpg, .jpeg, .png, .gif";
                $isUploadSuccess = false;
            }
            //pour que l'image ne porte pas 2x le meme nom 
            if(file_exists($imagePath)) {
                $imageError = "Le fichier existe deja";
                $isUploadSuccess = false;
            }
            //pour limité la taille de l'image (en oct)
            if($_FILES["image"]["size"] > 500000) {
                $imageError = "Le fichier ne doit pas depasser les 500KB";
                $isUploadSuccess = false;
            }
            //prendre le fichier pour la mettre dans le chemin
            if($isUploadSuccess) {
                //si false condition erreur
                if(!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
                    $imageError = "Il y a eu une erreur lors de l'upload";
                    $isUploadSuccess = false;
                } 
            } 
        }
        //ajout a la base de donnée (si tout c'est bien passer plus haut)
        if($isSuccess && $isUploadSuccess) {
            $db = Database::connect();
            $statement = $db->prepare("INSERT INTO items (name,description,price,category,image) values(?, ?, ?, ?, ?)");
            $statement->execute(array($name,$description,$price,$category,$image));
            Database::disconnect();
            header("Location: index.php");
        }
    }
    //securité pour mettre en securité les données 
    function checkInput($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
?>
<!-- partie html  -->
<!DOCTYPE html>
<html>
    <head>
        <title>Burger Code</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Holtwood+One+SC' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../css/styles.css">
    </head>
    
    <body>
        <h1 class="text-logo"><span class="bi-shop"></span> Admin Burger Code <span class="bi-shop"></span></h1>
        <div class="container admin">
            <div class="row">
                <h1><strong>Ajouter un item</strong></h1>
                <br>
                <form class="form" action="insert.php" role="form" method="post" enctype="multipart/form-data">
                    <br>
                    <div>
                        <label class="form-label" for="name">Nom:</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nom" value="<?php echo $name;?>">
                        <span class="help-inline"><?php echo $nameError;?></span>
                    </div>
                    <br>
                    <div>
                        <label class="form-label" for="description">Description:</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Description" value="<?php echo $description;?>">
                        <span class="help-inline"><?php echo $descriptionError;?></span>
                    </div>
                    <br>
                    <div>
                        <label class="form-label" for="price">Prix: (en €)</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Prix" value="<?php echo $price;?>">
                        <span class="help-inline"><?php echo $priceError;?></span>
                    </div>
                    <br>
                    <div>
                        <label class="form-label" for="category">Catégorie:</label>
                        <select class="form-control" id="category" name="category">
                        <?php
                        //connecté pour utliser la bdd +requete 
                           $db = Database::connect();
                           foreach ($db->query('SELECT * FROM categories') as $row) {
                                echo '<option value="'. $row['id'] .'">'. $row['name'] . '</option>';;
                           }
                        //deconnection de la bdd
                           Database::disconnect();
                        ?>
                        </select>
                        <span class="help-inline"><?php echo $categoryError;?></span>
                    </div>
                    <br>
                    <div>
                        <label class="form-label" for="image">Sélectionner une image:</label>
                        <input type="file" id="image" name="image"> 
                        <span class="help-inline"><?php echo $imageError;?></span>
                    </div>
                    <br>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"><span class="bi-pencil"></span> Ajouter</button>
                        <a class="btn btn-primary" href="index.php"><span class="bi-arrow-left"></span> Retour</a>
                   </div>
                </form>
            </div>
        </div>   
    </body>
</html>