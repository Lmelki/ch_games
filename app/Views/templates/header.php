<!DOCTYPE html>
<html>
<?php $session = session();
if ($session->has('cart')) {
    $cart = session('cart');
    $nb = count($cart);
} else $nb = 0; ?>

<head>
    <title>ChopesGames</title>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets/images/favicon.ico') ?>">
    <link rel="alternate" type="application/rss+XML" title="ChopesGames" href="<?php echo site_url('AdministrateurSuper/flux_rss') ?>" />

    <!-- <link rel="stylesheet" href="<?php echo css_url('bootstrap.min') ?>"> -->
    <!-- <link rel="stylesheet" href="<?php echo css_url('style') ?>"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
<!-- NOUVEAU -->
    <nav class="navbar navbar-expand-lg bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo site_url('Visiteur/accueil') ?>">
        <img class="d-block rounded-circle" style="width:60px;height:48px;'" src="<?php echo base_url() . '/assets/images/logoGames.jpeg' ?>" alt="Logo">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?php echo site_url('Visiteur/accueil') ?>">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo site_url('Visiteur/lister_les_produits') ?>">Afficher tous les produits</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Mon compte
          </a>
          <ul class="dropdown-menu">
            <?php if (!is_null($session->get('statut'))) { ?>
            <?php if ($session->get('statut') == 1) { ?>
                <li><a class="dropdown-item" href="<?php echo site_url('Client/historique_des_commandes') ?>">Mes commandes</a></li>
                <li><a class="dropdown-item" href="<?php echo site_url('Visiteur/s_enregistrer') ?>">Modifier mon compte</a></li>
            <?php } elseif ($session->get('statut') == 3) { ?>
                <li><a class="dropdown-item" href="?>">(2Do) Modifier mon compte</a></li>
            <?php } ?>
                <li><a class="dropdown-item" href="<?php echo site_url('Client/se_de_connecter') ?>">Se déconnecter</a></li>
            <?php } else { ?>
                <li><a class="dropdown-item" href="<?php echo site_url('Visiteur/se_connecter') ?>">Se connecter</a></li>
                <li><a class="dropdown-item" href="<?php echo site_url('Visiteur/s_enregistrer') ?>">S'enregistrer</a></li>
            <?php } ?>
          </ul>
        </li>
        <?php if (empty($session->get('statut'))) : ?>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo site_url('Visiteur/connexion_administrateur') ?>">Connexion Administrateur</a>
        </li>
        <?php endif; ?>
        <li>
            <button class="btn btn-md" href="<?php echo site_url('Visiteur/afficher_panier') ?>">
                <span class="fas fa-shopping-cart"><?php if ($nb > 0) echo "($nb)" ?> panier</span>
            </button>
        </li>
        <?php if ($session->get('statut') == 2 or $session->get('statut') == 3) : ?>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Administration
          </a>
          <ul class="dropdown-menu">

                <li><a class="dropdown-item" href="<?php echo site_url('AdministrateurEmploye/afficher_les_clients') ?>">Clients - Commandes</a></li>
                <li><a class="dropdown-item" href="">(2Do) Commandes non traitées</a></li>
            <?php if ($session->get('statut') == 3) { ?>
                <li><a class="dropdown-item" href="<?php echo site_url('AdministrateurSuper/ajouter_un_produit') ?>">Ajouter un produit</a></li>
                <li><a class="dropdown-item" href="<?php echo site_url('AdministrateurSuper/modifier_identifiants_bancaires_site') ?>">Modifier les identifiants bancaires site</a></li>
            <?php } ?>
          </ul>
        </li>
        <?php endif; ?>
      </ul>
      <form class="d-flex" role="search" method="post" action="<?php echo site_url('Visiteur/lister_les_produits') ?>">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Recherche</button>
      </form>
    </div>
  </div>
</nav>
    <main>