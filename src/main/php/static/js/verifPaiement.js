function confirmer(enregistrerClique) {
  if (enregistrerClique) {
    alert("Commande confirmée");
    window.location.href =
      "https://projets.iut-orsay.fr/pizzeria/actions/creerCommande.php?";
  } else {
    alert(
      "Veuillez enregistrer vos informations avant de confirmer la commande."
    );
  }
}
