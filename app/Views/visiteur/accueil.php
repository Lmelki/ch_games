

<h3 class='text-primary text-center m-2'>Accueil</h3><hr/> 
<div class="container-fluid">
  <div class="row">
      <!-- AJOUT DU VOLET CATEGORIE A GAUCHE -->
    <div class="col-sm-2 marque">
      <h5>Catégories:</h5>
      <ul class="list-group">
        <?php foreach ($categories as $categorie){ echo '<li class="list-group-item">'.anchor('Visiteur/lister_les_produits_par_categorie/'
          .$categorie["NOCATEGORIE"],$categorie["LIBELLE"]). '</li>'; ?><?php } ?>
      </ul>
    </div>
    <div class="container col-sm-10 p-5 d-flex justify-content-center">
      <div id="carouselExampleIndicators" class="carousel slide w-25" data-bs-ride="true">
        <div class="carousel-indicators">
        <?php $countcarousel=0; foreach($vitrines as $vitrine): $countcarousel++;?>
          <button type="button" data-bs-target="#carouselExampleIndicators" <?php if($countcarousel===1): ?> data-bs-slide-to="0" <?php else: ?> data-slide-to="<?php echo $countcarousel-1 ?>"<?php endif ?> class="active" aria-current="true" aria-label="Slide 1"></button><?php endforeach;?>
        </div>
        <div class="carousel-inner">
        <?php $count = 0; 
              $indicators = ''; 
              foreach ($vitrines as $vitrine): 
              $count++; 
              if ($count === 1) 
              { 
                  $class = 'active'; 
              } 
              else 
              { 
                  $class = ''; 
              }?> 

<!-- à revoir !!! -->

          <div class="carousel-item <?php echo $class; ?>">
          <a href="<?= base_url().'/index.php/Visiteur/voir_un_produit/'.$vitrine["NOPRODUIT"];?>">
          <img src="<?php echo base_url().'/assets/images/'.$vitrine["NOMIMAGE"].'.jpg'?>" class="d-block w-100 img-fluid" alt="<?php echo $vitrine['LIBELLE']; ?>">
          </a>
          </div>
          <?php endforeach;?> 
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
  </div>
</div>



