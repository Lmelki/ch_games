<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModeleProduit;
use App\Models\ModeleClient;
use App\Models\ModeleCategorie;
use App\Models\ModeleMarque;
use App\Models\ModeleAdministrateur;

//$pager = \Config\Services::pager();
helper(['url', 'assets']);
class Visiteur extends BaseController
{

    public function accueil()
    {
        $modelProd = new ModeleProduit();
        $data['vitrines'] = $modelProd->retourner_vitrine();
        $modelCat = new ModeleCategorie();
        $data['categories'] = $modelCat->retourner_categories();
        $modelMarq = new ModeleMarque();
        $data['marques'] = $modelMarq->retourner_marques();

        return view('templates/header', $data)
        .view('visiteur/accueil')
        .view('templates/footer');
    }


    public function lister_les_produits()
    {
        $session = session();
        $pager = \Config\Services::pager();
        $match = esc($this->request->getPost('search')); // fonction recherche integrée
        $modelProd = new ModeleProduit();
        if (empty($match)) {
            $data['lesProduits'] = $modelProd->paginate(12);
        } else {
            $data['lesProduits'] = $modelProd->produits_search($match)->paginate(12);
        }
        $data['pager'] = $modelProd->pager;
        $data['TitreDeLaPage'] = 'Nos produits';
        $modelCat = new ModeleCategorie();
        $data['categories'] = $modelCat->retourner_categories();
        $modelMarq = new ModeleMarque();
        $data['marques'] = $modelMarq->retourner_marques();

        return view('templates/header', $data)
        .view("visiteur/lister_les_produits")
        .view('templates/footer');
    }

    public function lister_les_produits_parmarque($nomarque = false)
    {
        if ($nomarque == false) {
            return redirect()->to('Visiteur/lister_les_produits');
        } else {
            $session = session();
            $pager = \Config\Services::pager();
            $modelMarq = new ModeleMarque();
            $marque = $modelMarq->retourner_marques($nomarque);
            
            $data['lamarque'] = $marque["NOM"];
            $data['TitreDeLaPage'] = $marque["NOM"];
            $modelCat = new ModeleCategorie();
            $data['categories'] = $modelCat->retourner_categories();
            $modelProd = new ModeleProduit();
            $data["lesProduits"] = $modelProd->retouner_produits_marque($nomarque)->paginate(12);
            $data['pager'] = $modelProd->pager;
            
            return view('templates/header', $data)
            .view("visiteur/lister_les_produits")
            .view('templates/footer');
        }
    }

    public function lister_les_produits_par_categorie($nocategorie=false) 
    {
       if($nocategorie==false){
        return redirect()->to('Visiteur/lister_les_produits');
        }else{
            $session = session();
            $pager = \Config\Services::pager();

            $modelCat = new ModeleCategorie();
            $categorie = $modelCat->retourner_categories($nocategorie);
            $data['categories'] = $modelCat->retourner_categories();
    
            $data['TitreDeLaPage'] = $categorie["LIBELLE"];
            $modelProd = new ModeleProduit();
            $data["lesProduits"] = $modelProd->retouner_produits_categorie($nocategorie)->paginate(12);
            $data['pager'] = $modelProd->pager;
     
      return view('templates/header', $data)
      .view("visiteur/lister_les_produits")
      .view('templates/footer');
      } 
   }

    public function voir_un_produit($noProduit = NULL)
    {
        $modelProd = new ModeleProduit();
        $data["unProduit"] = $modelProd->retourner_produits($noProduit);
        if (empty($data['unProduit'])) {
            //throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Page inconue');
        }

        $data['TitreDeLaPage'] = $data['unProduit']["LIBELLE"];
        $categorie = $data['unProduit']["NOCATEGORIE"];
        $marque = $data['unProduit']["NOMARQUE"];

        $modelCat = new ModeleCategorie();
        $data['categorie'] = $modelCat->retourner_categories($categorie);
        $data['categories'] = $modelCat->retourner_categories();

        $modelMarq = new ModeleMarque();
        $data['marque'] = $modelMarq->retourner_marques($marque);

        return view('templates/header', $data)
        .view('visiteur/voir_un_produit')
        .view('templates/footer');
    }

    public function ajouter_au_panier($noProduit)
    {
        $modelProd = new ModeleProduit();
        $produit = $modelProd->retourner_produits($noProduit);
        $item = array(
            'id'    => $produit["NOPRODUIT"],
            'qty'    => 1,
            'price'    => ($produit["PRIXHT"]) + ($produit["TAUXTVA"]),
            'ht' => $produit["PRIXHT"],
            'tva' => $produit["TAUXTVA"],
            'name'    => $produit["LIBELLE"],
            'image' => $produit["NOMIMAGE"],
            'maxi' => $produit["QUANTITEENSTOCK"]
        );
        $session = session();
        if ($session->has('cart')) {
            $cart =  array_values(session('cart'));
            $index = $this->exists($item['id']);
            if ($index == -1) {
                array_push($cart, $item);
            } else {
                $cart[$index]['qty']++;
            }
            $session->set('cart', $cart);
        } else {
            $cart = array($item);
            $session->set('cart', $cart);
        }
        return redirect()->to('Visiteur/afficher_panier');
    }

    private function exists($id)
    {
        $items = array_values(session('cart'));
        for ($i = 0; $i < count($items); $i++) {
            if ($items[$i]['id'] == $id) return $i;
        }
        return -1;
    }

    function afficher_panier()
    {
        $session = session();
        helper(['form']);
        $modelCat = new ModeleCategorie();
        $data['categories'] = $modelCat->retourner_categories();
        if ($session->has('cart'))
            $data['items'] = array_values(session('cart'));
        else $data['items'] = array();
        return view('templates/header', $data)
        .view('visiteur/afficher_panier')
        .view('templates/footer');
    }

    function suppression_item_panier($id = '')
    {
        $session = session();
        if ($session->has('cart')) {
            $items =  array_values(session('cart'));
            for ($i = 0; $i < count($items); $i++) {
                if ($items[$i]['id'] == $id) unset($items[$i]);
            }
            $session->set('cart', $items);
        }
        return redirect()->to('Visiteur/afficher_panier');
    }

    public function mise_a_jour_panier()
    {
        $session = session();
        if ($session->has('cart')) {
            $items =  array_values(session('cart'));
            $update = $this->request->getPost('update');
            for ($i = 0; $i < count($items); $i++) {
                $items[$i]['qty'] = $update[$items[$i]['id']];
            }
            $session->set('cart', $items);
        }
        return redirect()->to('Visiteur/afficher_panier');
    }

    public function s_enregistrer()
    {

        helper(['form']);
        $validation =  \Config\Services::validation();
        $data['TitreDeLaPage'] = "S'enregister";
        $session = session();

        $rules = [ //régles de validation creation
            'txtNom' => 'required',
            'txtPrenom' => 'required',
            'txtAdresse' => 'required',
            'txtVille'    => 'required',
            'txtCP' => 'required',
            'txtEmail' => 'required|valid_email|is_unique[client.EMAIL,id,{id}]',
            'txtMdp'    => 'required',
            'txtConfirmMdp'    => 'required|matches[txtMdp]'
        ];

        if (!empty($session->get('statut'))) //régles de validation pour modification
            $rules['txtEmail'] = 'required|valid_email';
            $rules['txtConfirmMdp'] = 'required|matches[txtMdp]';

        $messages = [ //message à renvoyer en cas de non respect des règles de validation
            'txtNom' => [
                'required' => 'Un nom  est requis',
            ],
            'txtPrenom' => [
                'required' => 'Un prénom est requis',
            ],
            'txtAdresse' => [
                'required' => 'Une adresse est requise',
            ],
            'txtVille'    => [
                'required' => 'Une ville est requise',
            ],
            'txtCP' => [
                'required' => 'Un code postal est requis',
            ],
            'txtEmail' => [
                'required' => 'Un Email est requis',
                'valid_email' => 'Un Email valide est requis',
                'is_unique' => 'Cet Email est déjà utilisé',
            ],
            'txtMdp'    => [
                'required' => 'Un mot de passe est requis',
            ],
            'txtConfirmMdp' => [
                'required' => 'Le mot de passe de confirmation est obligatoire',
                'matches' => 'Le Mot de Passe doit correspondre à celui entrée ci-dessus', 
            ]
        ];
        $modelCat = new ModeleCategorie();
        $data['categories'] = $modelCat->retourner_categories();
        
        $modelCli = new ModeleClient();

        if (!$this->validate($rules, $messages)) {

            if ($_POST) //if ($this->request->getMethod()=='post') // si c'est une tentative d'enregistrement // erreur IDE !!
                $data['TitreDeLaPage'] = "Corriger votre formulaire";
            else {
                if (empty($session->get('statut'))) $data['TitreDeLaPage'] = "S'enregister"; // premier affichage création compte sans session
                else { // premier affichage modification compte car session
                    $data['TitreDeLaPage'] = "Modifier mon profil";
                    $compte = $modelCli->retourner_client_par_no($session->get('id'));
                    $data['txtNom'] = $compte['NOM'];
                    $data['txtPrenom'] = $compte['PRENOM'];
                    $data['txtAdresse'] = $compte['ADRESSE'];
                    $data['txtVille'] = $compte['VILLE'];
                    $data['txtCP'] = $compte['CODEPOSTAL'];
                    $data['txtEmail'] = $compte['EMAIL'];
                }
            }
        } else {  // envoi d'une modification de compte (email et mdp aussi ? A VOIR...) ou enregistrement

            $compte = [
                'NOM' => $this->request->getPost('txtNom'),
                'PRENOM' => $this->request->getPost('txtPrenom'),
                'ADRESSE' => $this->request->getPost('txtAdresse'),
                'VILLE' => $this->request->getPost('txtVille'),
                'CODEPOSTAL' => $this->request->getPost('txtCP'),
                'EMAIL' => $this->request->getPost('txtEmail'),
                'MOTDEPASSE' => $this->request->getPost('txtMdp')
            ];

            if (empty($session->get('statut'))) { // enregistrement
                $modelCli->save($compte);
                $data['TitreDeLaPage'] = "Bravo ! vous êtes enregister sur ChopesGames";
            } else { // envoi d'une modification de compte
                $id = $session->get('id');
                if ($modelCli->update($id, $compte))
                    $data['TitreDeLaPage'] = "Bravo ! Mise à jour effectuée";
                else $data['TitreDeLaPage'] = "Sorry";
            }
        }
        return view('templates/header', $data)
        .view('visiteur/s_enregistrer')
        .view('templates/footer');
    }

    public function se_connecter()
    {
        helper(['form']);
        $session = session();
        $data['TitreDeLaPage'] = 'Se connecter';
        $rules = [ //régles de validation
            'txtEmail' => 'required|valid_email|is_not_unique[client.EMAIL,id,{id}]',
            'txtMdp'   => 'required|is_not_unique[client.MOTDEPASSE,id,{id}]'
        ];

        $modelCat = new ModeleCategorie();
        $data['categories'] = $modelCat->retourner_categories();
        if (!$this->validate($rules)) {
            if ($this->request->getMethod()=='post') // si c'est une tentative d'enregistrement // erreur IDE !!
                $data['TitreDeLaPage'] = "Corriger votre formulaire";
            else   $data['TitreDeLaPage'] = "Se connecter";
        } else {
            $modelCli = new ModeleClient();
            $Identifiant = esc($this->request->getPost('txtEmail'));
            $MdP = esc($this->request->getPost('txtMdp'));

            $UtilisateurRetourne = $modelCli->retourner_clientParMail($Identifiant);

            if (!$UtilisateurRetourne == null) {
                if ($MdP == $UtilisateurRetourne["MOTDEPASSE"]) {
                    if (!empty($session->get('statut'))) {
                        unset($_SESSION['cart']);
                    }
                    $session->set('id', $UtilisateurRetourne["NOCLIENT"]);
                    $session->set('statut', 1);
                    return redirect()->to('Visiteur/accueil');
                } 
            } 
        }
        return view('templates/header', $data)
        .view('visiteur/se_connecter')
        .view('templates/footer');
    }

    public function connexion_administrateur()
    {
        helper(['form']);
        // $validation =  \Config\Services::validation();
        $session = session();

        $rules = [ //régles de validation
            'txtIdentifiant' => 'required',
            'txtMotDePasse'   => 'required'
        ];
        // $messages = [ //message à renvoyer en cas de non respect des règles de validation
        //     'txtIdentifiant' => [
        //         'required' => 'Un identifiant est requis',
        //     ],
        //     'txtMotDePasse'    => [
        //         'required' => 'Un mot de passe est requis',
        //     ]
        // ];

        $modelCat = new ModeleCategorie();
        $data['categories'] = $modelCat->retourner_categories();
        $data['TitreDeLaPage'] = "Corriger votre formulaire";

        if (!$this->validate($rules)) {
            if ($this->request->getMethod()!='post') // si c'est une tentative d'enregistrement // erreur IDE !!
            $data['TitreDeLaPage'] = "Se connecter";
             // sinon premier affichage

        } else { //validation ok
            $modelAdm = new ModeleAdministrateur();
            $Identifiant = esc($this->request->getPost('txtIdentifiant'));
            $MdP = esc($this->request->getPost('txtMotDePasse'));
            $adminRetourne = $modelAdm->retourner_administrateur_par_id($Identifiant);

            if (!$adminRetourne == null) {
                //  if (password_verify($MdP,$adminRetourne->MOTDEPASSE))
                // PAS D'ENCODAGE DU MOT DE PASSE POUR FACILITATION OPERATIONS DE TESTS (ENCODAGE A FAIRE EN PRODUCTION!)
                if ($MdP == $adminRetourne["MOTDEPASSE"]) {
                    $session->set('identifiant', $adminRetourne["IDENTIFIANT"]);
                    $session->set('mail', $adminRetourne["EMAIL"]);
                    if (!empty($session->get('statut'))) {
                        unset($_SESSION['cart']);
                    }
                    if ($adminRetourne["PROFIL"] == 'Employé') {
                        $session->set('statut', 2);
                    } elseif ($adminRetourne["PROFIL"] == 'Super') {
                        $session->set('statut', 3);
                    }
                    return redirect()->to('Visiteur/accueil');
                } 
            } 
        }
        return view('templates/header', $data)
        .view('visiteur/connexion_administrateur')
        .view('templates/footer');
    }
    public function prodById(int $id){
        $modelProd = new ModeleProduit();
        $slug= $modelProd->retournerSlug($id);
    //redirection   
        if ($slug != null){ 
        return redirect()->to('jeux/'.$slug['NOMIMAGE']);
        }
    //else redirect 404 adaptée ?
      }
      
    public function prodBySlug($slug){
        $modelProd = new ModeleProduit();
        $id= $modelProd->retournerId($slug);
    //pas de redirection mais invocation de la méthode déjà programmée     
        if ($id != null){ 
        return $this->voir_un_produit($id);
        }
    //else redirect 404 adaptée ?
      }
      public function droit_a_l_oubli() {
        helper(['form']);
        $session = session();
        $data['TitreDeLaPage'] = 'Confirmer la suppression du compte';
        $rules = [ //régles de validation
            'txtEmail' => 'required|valid_email|is_not_unique[client.EMAIL,id,{id}]',
            'txtMdp'   => 'required|is_not_unique[client.MOTDEPASSE,id,{id}]'
        ];

        $modelCat = new ModeleCategorie();
        $data['categories'] = $modelCat->retourner_categories();
        if (!$this->validate($rules)) {
            if ($this->request->getMethod()=='post') // si c'est une tentative d'enregistrement // erreur IDE !!
                $data['TitreDeLaPage'] = "Corriger votre formulaire";
            else   $data['TitreDeLaPage'] = "Confirmer la suppression du compte";
        } else {
            $modelCli = new ModeleClient();
            $Identifiant = esc($this->request->getPost('txtEmail'));
            $MdP = esc($this->request->getPost('txtMdp'));

            $UtilisateurRetourne = $modelCli->retourner_clientParMail($Identifiant);

            if (!$UtilisateurRetourne == null) {
                if ($MdP == $UtilisateurRetourne["MOTDEPASSE"]) {
                    
                    $id_anonyme = $UtilisateurRetourne['NOCLIENT'];
                    // $id_anonyme = $session->get('id');
                    $client_anonyme = [
                        'MOTDEPASSE' => "oih,ygb=)_85681AHGBTjfh" ,  
                        'EMAIL' => "client@anonyme" ,
                        'CODEPOSTAL' => null ,
                        'VILLE' => null,
                        'ADRESSE' => null, 
                        'PRENOM' => "anonyme" ,
                        'NOM' => "client inconu",
                    ];
                    $modelCli->update($id_anonyme, $client_anonyme);
                    $session->destroy();
                    return redirect()->to('Visiteur/accueil');
                } 
            } 
        }
        $data['TitreDeLaPage'] = "Modifier mon profil";
        return view('templates/header', $data)
        .view('visiteur/s_enregistrer')
        .view('templates/footer');
    }
}