<!DOCTYPE html>
<html>

<head>
    <title>Panier</title>
    <link rel="stylesheet" type="text/css" href="../static/css/style.css">
</head>

<body>
    <?php include("../view/navigation.php"); ?>
    <main class="mainPizza">
        <h1> Votre panier </h1>
        <?php
        include("../controller/controllerPizza.php");
        $panier = $_SESSION['panier'];
        $tabQuantite = $_SESSION['tabQuantite'];

        foreach ($_SESSION['panier'] as $idPizza) {

            $pizzaPanier = controllerPizza::getPizzaPanier($idPizza);
            if ($pizzaPanier->rowCount() > 0) {
                // Parcourir les résultats et afficher les pizzas 
                while ($row = $pizzaPanier->fetch(PDO::FETCH_ASSOC)) {
                    $id = htmlspecialchars($row['idPizza']);
                    $lienImage = htmlspecialchars($row['lienImage']);
                    $nom = htmlspecialchars($row['nomPizza']);
                    $recette = htmlspecialchars($row['recette']);
                    $idAllergene = controllerPizza::getAllergenePizza($id);
                    $allergenes = "";
                    $quantite = $tabQuantite[$id];
                    $prix = controllerPizza::getPrixPizza($id);
                    echo "<div class='pizza-container'>";
                    echo "<img src='../static/$lienImage' alt='Pizza' class='pizza-image'>";
                    echo "<p id='pizza-title'>$nom</p>";
                    echo "<p id='pizza-recette'>$recette</p>";
                    // Affichage des allergènes
                    while ($row = $idAllergene->fetch(PDO::FETCH_ASSOC)) {
                        $allergenes .= htmlspecialchars($row['nomAllergene']) . ", ";
                    }
                    // Enlever la dernière virgule
                    $allergenes = substr($allergenes, 0, -2);
                    echo "<p class='pizza-allergenes'>Allergènes : $allergenes</p>";
                    echo "<p class='pizza-prix'>Quantite : $quantite </p>";
                    echo "<p class='pizza-prix'>Prix : $prix €</p>";
                    echo "<form action='../actions/suppressionPizzaPanier.php' method='post'>";
                    echo "<input type='hidden' name='idPizza' value='$id'>";
                    echo "<button type='submit' class='delete-pizza-button'>Supprimer</button>";
                    echo "</form>";
                    echo "</div>";
                }


            }

        }

        foreach ($_SESSION['panierPizzaPersonaliser'] as $idPizza => $listRecettePizza) {
            $occurrences = array_count_values($listRecettePizza);
            foreach ($occurrences as $recette => $count) {
                $pizzaPersonnalierPanier = controllerPizza::getPizzaPanier($idPizza);
                if ($pizzaPersonnalierPanier->rowCount() > 0) {
                    // Parcourir les résultats et afficher les pizzas 
                    while ($row = $pizzaPersonnalierPanier->fetch(PDO::FETCH_ASSOC)) {
                        $lienImage = htmlspecialchars($row['lienImage']);
                        $nom = htmlspecialchars($row['nomPizza']) . ' Personnaliser';
                        $idAllergene = controllerPizza::getAllergenePizza($idPizza);
                        $allergenes = "";
                        $quantite = $count;
                        $prixRecette = $_SESSION['prixParRecette'][$idPizza][$recette];
                        echo "<div class='pizza-container'>";
                        echo "<img src='../static/$lienImage' alt='Pizza' class='pizza-image'>";
                        echo "<p id='pizza-title'>$nom</p>";
                        echo "<p id='pizza-recette'>$recette</p>";
                        // Affichage des allergènes
                        while ($row = $idAllergene->fetch(PDO::FETCH_ASSOC)) {
                            $allergenes .= htmlspecialchars($row['nomAllergene']) . ", ";
                        }
                        // Enlever la dernière virgule
                        $allergenes = substr($allergenes, 0, -2);
                        echo "<p class='pizza-allergenes'>Allergènes : $allergenes</p>";
                        echo "<p class='pizza-prix'>Quantite : $quantite </p>";
                        echo "<p class='pizza-prix'>Prix : $prixRecette €</p>";
                        echo "<form action='../actions/suppressionPizzaPersonnaliserPanier.php' method='post'>";
                        echo "<input type='hidden' name='idPizza' value='$idPizza'>";
                        echo "<input type='hidden' name='recette' value='$recette'>";
                        echo "<input type='hidden' name='prixRecette' value='$prixRecette'>";
                        echo "<button type='submit' class='delete-pizza-button'>Supprimer</button>";
                        echo "</form>";
                        echo "</div>";
                    }
                }


            }

        }

        include("../controller/controllerProduit.php");
        $panierProduit = $_SESSION['panierProduit'];
        $tabQuantiteProduit = $_SESSION['tabQuantiteProduit'];
        foreach ($_SESSION['panierProduit'] as $idProduit) {

            $produitPanier = controllerProduit::getProduitPanier($idProduit);
            if ($produitPanier->rowCount() > 0) {
                // Parcourir les résultats et afficher les pizzas 
                while ($row = $produitPanier->fetch(PDO::FETCH_ASSOC)) {
                    $idProduit = htmlspecialchars($row['idProduit']);
                    $nomProduit = htmlspecialchars($row['nomProduit']);
                    $quantiteProduit = $tabQuantiteProduit[$idProduit];
                    $prixProduit = controllerProduit::getPrixProduit($idProduit);
                    echo "<div class='produit-container'>";
                    echo "<p id='produit-title'>$nomProduit</p>";
                    echo "<p class='produit-prix'>Quantite : $quantiteProduit </p>";
                    echo "<p class='produit-prix'>Prix : $prixProduit €</p>";
                    echo "<form action='../actions/suppressionProduitPanier.php' method='post'>";
                    echo "<input type='hidden' name='idProduit' value='$idProduit'>";
                    echo "<button type='submit' class='delete-produit-button'>Supprimer</button>";
                    echo "</form>";
                }


            }

        }

        // Afficher le prix total du panier
        $prixTotal = $_SESSION['prixTotal'] * 1.2;
        echo "<br>";
        echo "<p id='prix'> Prix total de votre commande : $prixTotal € </p>";

        if (!empty($_SESSION['panier']) || !empty($_SESSION['panierProduit']) || !empty($_SESSION["panierPizzaPersonaliser"]) && $_SESSION['prixTotal'] > 0) {
            echo "<form action='../view/vuePaiement.php' method='get'>";
            echo "<button type='submit'>Commander</button>";
            echo "</form>";
        }

        ?>
    </main>
</body>
<br>
<?php include("../view/footer.html"); ?>

</html>