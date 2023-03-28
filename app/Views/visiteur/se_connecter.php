<div>
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-6">
                    <div class="col-md-12 container">
                        <?php  echo form_open('Visiteur/se_connecter') ?>
                        <br>
                            <h3 class="text-center text-primary"><?php echo $TitreDeLaPage ?></h3>
                            
                            <?php
                                echo form_input('txtEmail', set_value('txtEmail'),['placeholder' => 'Email', 'class'=>'form-control'],'email');
                                echo '<br>';
                                echo form_input('txtMdp', set_value('txtMdp'),['placeholder' => 'Mot de passe', 'class'=>'form-control','id'=>'mdp'],'password');
                                echo '<br>';
                                echo csrf_field();
                                echo form_submit('submit', 'Valider', ['class' => 'btn btn-primary btn-md']);
                            ?>
                            <div class="text-primary right">   
                            <a class="btn btn-primary" href="<?php echo site_url('Visiteur/s_enregistrer') ?>">Crée un compte</a>
 
                        </form>
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