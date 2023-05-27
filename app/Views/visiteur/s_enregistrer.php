
    <?php $session = session();?>
    <div>
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-6">
                    <div class="col-md-12 container">
                        <?php echo form_open('Visiteur/s_enregistrer') ?>
                            <br>
                            <h3 class="text-center text-primary"><?php echo $TitreDeLaPage ?></h3>
                            <?PHP if($TitreDeLaPage=='Corriger votre formulaire') echo service('validation')->listErrors();
                             if(!isset($txtNom)) $txtNom=''; if(!isset($txtPrenom)) $txtPrenom=''; if(!isset($txtAdresse)) $txtAdresse=''; if(!isset($txtVille)) $txtVille=''; if(!isset($txtCP)) $txtCP=''; if(!isset($txtEmail)) $txtEmail=''; 
                             
                                echo form_input('txtNom', set_value('txtNom', $txtNom),['placeholder' => 'Nom', 'class'=>'form-control']);
                                echo '<br>';
                                echo form_input('txtPrenom', set_value('txtPrenom', $txtPrenom),['placeholder' => 'Prénom', 'class'=>'form-control']);
                                echo '<br>';
                                echo form_input('txtAdresse', set_value('txtAdresse', $txtAdresse),['placeholder' => 'Adresse', 'class'=>'form-control']);
                                echo '<br>';
                                echo form_input('txtVille', set_value('txtVille', $txtVille),['placeholder' => 'Ville', 'class'=>'form-control']);
                                echo '<br>';
                                echo form_input('txtCP', set_value('txtCP', $txtCP),['placeholder' => 'Code postal', 'class'=>'form-control']);
                                echo '<br>';
                                echo form_input('txtEmail', set_value('txtEmail', $txtEmail),['placeholder' => 'Email', 'class'=>'form-control'],'email');
                                echo '<br>';
                                echo form_input('txtMdp', set_value('txtMdp'),['placeholder' => 'Mot de passe', 'class'=>'form-control','id'=>'mdp'],'password');
                                echo '<br>';
                                echo form_input('txtConfirmMdp', set_value('confirmMdp'),['placeholder' => 'Confirmez mot de passe', 'class'=>'form-control'],'password');
                                echo '<br>';
                                echo csrf_field();
                                echo form_submit('submit', 'Valider', ['class' => 'btn btn-primary']);
                                ?>
                        </form>
                        <div class="text-primary text-end m-2">
                            <?php if($TitreDeLaPage=="Modifier mon profil") { ?>
                            <a type="button" class="btn btn-outline-primary mr-5" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo" 
                            href="<?php echo site_url('Visiteur/droit_a_l_oubli') ?>">
                            Droit à l'oubli</a>
                            <?php } ?>
                            <a class="btn btn-outline-primary" href="<?php echo site_url('Visiteur/se_connecter') ?>">Se connecter</a>
                        </div>
                        <br>
                        <!-- LE MODALE DE DROIT A L4OUBLI COMMENCE ICI -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-center text-primary" id="exampleModalLabel"><?php echo $TitreDeLaPage ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            <h6 class="text-center text-danger">Attention cette action est irréversible ! </h6>
                            <?php  echo form_open('Visiteur/droit_a_l_oubli') ?>
                            <br>                            
                            <?php
                                echo form_input('txtEmail', set_value('txtEmail'),['placeholder' => 'Email', 'class'=>'form-control'],'email');
                                echo '<br>';
                                echo form_input('txtMdp', set_value('txtMdp'),['placeholder' => 'Mot de passe', 'class'=>'form-control','id'=>'mdp'],'password');
                                echo '<br>';
                                echo csrf_field();
                            ?>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                               <?php echo form_submit('submit', 'Confirmer la suppression', ['class' => 'btn btn-primary btn-md']); ?>
                            </div>
                            </form>
                        </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script language=javascript>
     function Affichermasquermdp() {
  var x = document.getElementById("mdp");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
} 
      </script> 