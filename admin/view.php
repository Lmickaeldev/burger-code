<?php
    // connection base de donné
    require 'database.php';
    // recuperation de l'id
    if(!empty($_GET['id']))
    {   //securité
        $id = checkInput($_GET['id']);
    }
    //connection a la database
    $db = Database::connect();
    //recuperation des donné de la base
    $statement = $db->prepare('SELECT items.id, items.name, items.description, items.price, items.image, categories.name AS category 
                                FROM items LEFT JOIN categories ON items.category = categories.id 
                                WHERE items.id= ?');
    
    $statement->execute(array($id));
    //stockage dans une ligne 
    $item= $statement->fetch();
    Database::disconnect();

    //fonction securité
    function checkInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Admin Burger Code</title>
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
            <div class="col-md-6"><h1><strong>voir un items</strong></h1>
            <br>
            <form>
                <div class="form-group">
                    <label>Nom :</label><?php echo ' '.$item['name']; ?>
                </div>
                <div class="form-group">
                    <label>Description :</label><?php echo ' '.$item['description']; ?>
                </div>
                <div class="form-group">
                    <label>Prix :</label><?php echo ' '.number_format($item['price'], 2, '.', '') .'€'; ?>
                </div>
                <div class="form-group">
                    <label>categorie :</label><?php echo ' '.$item['category']; ?>
                </div>
                <div class="form-group">
                    <label>Image :</label><?php echo ' '.$item['image']; ?>
                </div>
            </form>
            </div>
                <div class="col-md-6 site">
                            <div class="img-thumbnail">
                                <img src="<?php echo '../images/' . $item['image'] ; ?>" class="img-fluid" alt="...">
                                <div class="price"> <?php echo number_format($item['price'], 2, '.', '') .'€'; ?>
                                </div>
                                <div class="caption">
                                    <h4><?php echo $item['name']; ?></h4>
                                    <p><?php echo $item['description']; ?></p>
                                    <a href="#" class="btn btn-order" role="button"><span class="bi-cart-fill"></span> Commander</a>
                                </div>
                            </div>
                </div> 
            
                <div class="form-action"><a class="btn btn-primary" href="index.php"><i class="bi bi-arrow-left"></i>RETOUR</a></div>
            
            </div>
        </div>
    </body>
</html>
