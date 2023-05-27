<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeleClient extends Model
{

    protected $table = 'client';
    protected $allowedFields = ['NOCLIENT', 'NOM', 'PRENOM', 'ADRESSE', 'VILLE', 'CODEPOSTAL', 'EMAIL', 'MOTDEPASSE'];
    protected $primaryKey = 'NOCLIENT';

    public function retourner_clientParMail($mail = false)
    {
        return $this->where(['EMAIL' => $mail])
            ->first();
    }

    public function retourner_client_par_no($noclient)
     {
        return $this->where(['NOCLIENT' => $noclient])->first(); 
    } 

   public function retourner_clients()
   {
    return $this->findAll();
   }
   
   public function confirm_connexion($mail, $mdp)
   {
       return $this->where(['EMAIL' => $mail, 'MOTDEPASSE' => $mdp])
           ->first();
   }
//    public function anonymiser($noclient =false){

//     return $this->builder()->set('MOTDEPASSE' , '' , 'EMAIL' , "" , 'CODEPOSTAL' , "" , 'VILLE' , "", 'ADRESSE' , "" ,
//      'PRENOM' , "" , 'NOM' , "")
//                             ->where('NOCLIENT')
//                             ->update();
//    }

//     public function anonymiser_client($noclient = null){

//     $data = ['MOTDEPASSE' => '' ,
//     'EMAIL' => "" ,
//      'CODEPOSTAL' => "" ,
//       'VILLE' => "",
//        'ADRESSE' => "" ,
//         'PRENOM' => "" ,
//          'NOM' => ""];
//      return $this->update($noclient , $data);

//     }

}
