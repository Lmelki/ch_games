
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
                                
                            <div class="text-primary right">
                            <a class="btn btn-primary" href="<?php echo site_url('Visiteur/se_connecter') ?>">Se connecter</a>
                            </div>
                        </form>
                        <br>
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