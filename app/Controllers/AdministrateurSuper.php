<?php
namespace App\Controllers;
use App\Models\ModeleProduit;
use App\Models\ModeleCategorie;
use App\Models\ModeleIdentifiant;
use App\Models\ModeleMarque;

helper(['url', 'assets', 'form']);

class AdministrateurSuper extends BaseController
{

    public function ajouter_un_produit($prod = false)
    {
        $validation =  \Config\Services::validation();
        $modelCat = new ModeleCategorie();
        $data['categories'] = $modelCat->retourner_categories();
        $modelMarq = new ModeleMarque();
        $data['marques'] = $modelMarq->retourner_marques();
        $data['TitreDeLaPage'] = 'Ajouter un produit';

        $rules = [ //régles de validation creation
            'Categorie' => 'required',
            'Marque' => 'required',
            'txtLibelle' => 'required',
            'txtDetail'    => 'required',
            'txtPrixHT' => 'required',
            'txtQuantite' => 'required',
            'txtNomimage' => 'required',
            'image' => [
                'uploaded[image]',
                'mime_in[image,image/jpg,image/jpeg]',
                'max_size[image,1024]',
            ]
        ];
        if (!$this->validate($rules)) {
            if ($_POST) $data['TitreDeLaPage'] = 'Corriger votre formulaire'; //correction
            else {
                if($prod==false) {
                    $data['TitreDeLaPage'] = 'Ajouter un produit';
                }
                // else { //abandonné !
                //     $data['TitreDeLaPage'] = 'Modifier un produit';
                //     $modelProd = new ModeleProduit();
                //     $produit =  $modelProd->retourner_produits($prod);
                //     $data['Categorie'] = $produit['NOCATEGORIE'];
                //     $data['Marque'] = $produit['NOMARQUE'];
                //     $data['txtLibelle'] = $produit['LIBELLE'];
                //     $data['txtDetail'] = $produit['DETAIL'];
                //     $data['txtPrixHT'] = $produit['PRIXHT'];
                //     $data['txtNomimage'] = $produit['NOMIMAGE'];
                //     $data['txtQuantite'] = $produit['QUANTITEENSTOCK'];
                // }
                
            }
            return view('templates/header', $data)
            .view('AdministrateurSuper/ajouter_un_produit')
            .view('templates/footer');
        } else // si formulaire valide
        {


            $donneesAInserer = array(
                'NOCATEGORIE' => $this->request->getPost('Categorie'),
                'NOMARQUE' => $this->request->getPost('Marque'),
                'LIBELLE' => $this->request->getPost('txtLibelle'),
                'DETAIL' => $this->request->getPost('txtDetail'),
                'PRIXHT' => $this->request->getPost('txtPrixHT'),
                'TAUXTVA' => (($this->request->getPost('txtPrixHT') * 20) / 100),
                'NOMIMAGE' => pathinfo($this->request->getPost('txtNomimage'), PATHINFO_FILENAME), // on n'insère que le nom du fichier dans la BDD
                'QUANTITEENSTOCK' => $this->request->getPost('txtQuantite'),
                'DATEAJOUT' => date("Y-m-d"),
                'DISPONIBLE' => 0,
            );

            if ($this->request->getPost('txtQuantite') > 0) $donneesAInserer['DISPONIBLE'] = 1;

            if ($img = $this->request->getFile('image')) {
                if ($img->isValid() && !$img->hasMoved()) {
                    $newName = $this->request->getPost('txtNomimage') . '.jpg';
                    $img->move('assets/images/', $newName);
                    print_r($donneesAInserer);
                    $modelProd = new ModeleProduit();
                    $modelProd->save($donneesAInserer);
                    
                    return redirect()->to('visiteur/lister_les_produits');
                }
            }
            //else redirecte ??
        }
    }

    public function rendre_indisponible($noProduit = null)
    {
        if ($noProduit == null) {
            return redirect()->to('visiteur/lister_les_produits');
        }

        $donneesAInserer = array(
            'DISPONIBLE' => 0
        );
        $modelProd = new ModeleProduit();
        $modelProd->update($noProduit, $donneesAInserer);
        return redirect()->to($_SERVER['HTTP_REFERER']);
    }

    public function rendre_disponible($noProduit = null)
    {
        if ($noProduit == null) {
            return redirect()->to('visiteur/lister_les_produits');
        }
        $donneesAInserer = array(
            'DISPONIBLE' => 1
        );
        $modelProd = new ModeleProduit();
        $modelProd->update($noProduit, $donneesAInserer);
        return redirect()->to($_SERVER['HTTP_REFERER']);
    }

    public function modifier_produit($noProduit = null)
    {
        if ($noProduit == null) {
            return redirect()->to('visiteur/lister_les_produits');
        }
        $validation =  \Config\Services::validation();
        $modelCat = new ModeleCategorie();
        $data['categories'] = $modelCat->retourner_categories();
        $modelMarq = new ModeleMarque();
        $data['marques'] = $modelMarq->retourner_marques();
        $modelProd = new ModeleProduit();
        $data['TitreDeLaPage'] = 'Modifier un produit';

        $rules = [ //régles de validation creation
            'Categorie' => 'required',
            'Marque' => 'required',
            'txtLibelle' => 'required',
            'txtDetail'    => 'required',
            'txtPrixHT' => 'required',
            'txtQuantite' => 'required',
            'txtNomimage' => 'required',
            'vitrine' => '',
        ];

        if (!$this->validate($rules)) {
            if($_POST)$data['TitreDeLaPage'] = 'Corriger votre formulaire';
            $produit =  $modelProd->retourner_produits($noProduit);
            $data['noProduit'] = $produit['NOPRODUIT'];
            $data['Categorie'] = $produit['NOCATEGORIE'];
            $data['Marque'] = $produit['NOMARQUE'];
            $data['txtLibelle'] = $produit['LIBELLE'];
            $data['txtDetail'] = $produit['DETAIL'];
            $data['txtPrixHT'] = $produit['PRIXHT'];
            $data['txtNomimage'] = $produit['NOMIMAGE'];
            $data['txtQuantite'] = $produit['QUANTITEENSTOCK'];
            $data['vitrine'] = $produit['VITRINE'];
            
            return view('templates/header', $data)
            .view('AdministrateurSuper/modifier_produit')
            .view('templates/footer');
        } else {

            $donneesAInserer = array(
                'NOCATEGORIE' => $this->request->getPost('Categorie'),
                'NOMARQUE ' => $this->request->getPost('Marque'),
                'LIBELLE' => $this->request->getPost('txtLibelle'),
                'DETAIL' => $this->request->getPost('txtDetail'),
                'PRIXHT' => $this->request->getPost('txtPrixHT'),
                'TAUXTVA' => (($this->request->getPost('txtPrixHT') * 20) / 100),
                'DATEAJOUT' => date("Y-m-d"),
                'NOMIMAGE' => $this->request->getPost('txtNomimage'),
                'QUANTITEENSTOCK' => $this->request->getPost('txtQuantite'),
                'VITRINE' => 0
            );

            if ($this->request->getPost('checkbox') == 1) {$donneesAInserer['VITRINE']=1;} 
            
            $modelProd->update($noProduit, $donneesAInserer);

            return redirect()->to('visiteur/lister_les_produits');
        }
    }

    function modifier_identifiants_bancaires_site()
    {
        $modelIdent = new ModeleIdentifiant();
        $data['identifiant'] = $modelIdent->retourner_identifiant();

        $rules = [ //régles de validation creation
            'txtSite' => 'required',
            'txtRang' => 'required',
            'txtIdentifiant' => 'required',
            'txtHmac'    => 'required',
        ];


        if (!$this->validate($rules)) {
            $modelCat = new ModeleCategorie();
            $data['categories'] = $modelCat->retourner_categories();
            return view('templates/header', $data)
            .view('AdministrateurSuper/modifier_identifiants_bancaires_site')
            .view('templates/footer');
        } else {

            $donneesAInserer = array(
                'SITE' => $this->request->getPost('txtSite'),
                'RANG' => $this->request->getPost('txtRang'),
                'IDENTIFIANT' => $this->request->getPost('txtIdentifiant'),
                'CLEHMAC' => $this->request->getPost('txtHmac'),
                'SITEENPRODUCTION' => 0
            );

            if ($this->request->getPost('checkbox') == 1) {
                $donneesAInserer['SITEENPRODUCTION'] = 1;
            }

            $modelIdent->update(1, $donneesAInserer);
            return redirect()->to('visiteur/lister_les_produits');
        }
    }
}
