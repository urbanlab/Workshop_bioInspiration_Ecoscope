<?php

/*
 * PREMIÈRE ÉTAPE : RÉCUPÉRATION DES DONNÉES
 */

// Les informations sont pour le moment stockées dans un fichier rawData.csv
$handle = fopen('data/rawData.csv','r');
/*
 * todo
Le contour du bloc de description doit s'adapter à la couleur du service de la personne sélectionnée.
Si personne n'est sélectionné, le liseré du bloc n'apparaît pas.

bonus :
faire la liste de chaque personnel (la page !)
*/
$count=0;
$personnes=array();
while ( ($data = fgetcsv($handle) ) !== FALSE ) {
    if ($count>0 && $count%2===0) { // La première ligne est l'en-tête et les lignes impaires sont vides.
        $personne = array();
        $personne['service']=$data[0];
        $personne['poste']=$data[1];
        $personne['anciennete']=$data[3];
        $personne['mdm']=$data[4];
        $personne['telephone']=genererNumeroTelephoneAleatoire();
        $personne['genre'] = genererGenre();
        $personne['prenom'] = genererPrenom($personne['genre']);
        $personne['nom'] = genererNom();
        $personne['email'] = genererEmail($personne['prenom'], $personne['nom']);
        $personne['photo'] = genererPhoto($personne['genre']);
        array_push($personnes, $personne);
    }
    $count++;
}
// On a aussi les informations de la dernière personne qui s'est présentée au prototype.
$rows = file('data/prototypeData.csv');
$last_row = array_pop($rows);
$data = str_getcsv($last_row);
$personne = array();
$personne['service']=$data[5];
$personne['poste']=$data[6];
$personne['anciennete']=$data[2];
$personne['mdm']=$data[4];
$personne['telephone']=$data[9];
$personne['genre'] = '';
$personne['prenom'] = $data[1];
$personne['nom'] = $data[0];
$personne['email'] = $data[8];
$personne['photo'] = genererPhoto($personne['genre']);
array_push($personnes, $personne);


/**
 * @return string
 */
function genererNumeroTelephoneAleatoire(){
    return '04 ' . strval(rand(10, 99)) . ' ' . strval(rand(10, 99)) . ' ' . strval(rand(10, 99)) . ' ' . strval(rand(10, 99));
}


function genererPhoto($genre='femme'){
    // On a une liste de photos divisée en deux dossiers 'homme' et 'femme'. On en pioche une au hasard.
    if($genre==='homme'){
        $photos = array_slice(scandir('data/portraits_mdm/homme/'), 2); // On retire les . et ..
    } else{
        $photos = array_slice(scandir('data/portraits_mdm/femme/'), 2); // On retire les . et ..
    }
    return $photos[array_rand($photos, 1)];
}



function genererGenre(){
    $genres = array('femme', 'homme');
    return $genres[rand(0,1)];
}

function genererNom(){
    $noms = array();
    $lines = file('data/noms_raw.txt');
    foreach ($lines as $line) {
        if (trim($line)!==''){
            $noms[] = trim($line);
        }
    }
    return $noms[array_rand($noms, 1)];

}

function genererPrenom($genre='femme'){
    if ($genre==='femme'){
        return genererPrenomFeminin();
    }
    return genererPrenomMasculin();
}

function genererPrenomFeminin(){
    $prenoms = array();
    $lines = file('data/prenoms_feminins_raw.txt');
    foreach ($lines as $line) {
        if (trim($line)!==''){
            $prenoms[] = trim($line);
        }
    }
    return $prenoms[array_rand($prenoms, 1)];
}

function genererPrenomMasculin(){
    $prenoms = array();
    $lines = file('data/prenoms_masculins_raw.txt');
    foreach ($lines as $line) {
        if (trim($line)!==''){
            $prenoms[] = trim($line);
        }
    }
    return $prenoms[array_rand($prenoms, 1)];
}

function genererEmail($prenom='', $nom='') {
    // La première lettre du prénom + le nom + @grandlyon.com
    return strtolower($prenom[0] . $nom) . '@grandlyon.com';
}


echo genererPage($personnes);


function genererPage($personnes=array()){
    return genererEnTete() . genererCorpsDePage($personnes) . genererPiedDePage();
}

function genererEnTete(){
    return '<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Trombi_MDM</title>
        
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
       
        <!-- Fake favicon, to avoid extra request to server -->
        <link rel="icon" href="data:;base64,iVBORw0KGgo=">
        
        <link rel="stylesheet" type="text/css" href="trombi.css">
        
        <script type="text/javascript" src="trombi.js"></script>
        <script type="text/javascript" src="node_modules/vis-network/standalone/umd/vis-network.js"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">  
    </head>';
}

function genererCorpsDePage($personnes=array()){
    return '
    <body>
        <script type="text/javascript">
            // Liste des personnes
            const personnes = ' . json_encode($personnes) . '
            const correspondancesNoeudsPersonnes={}
        </script>
        <div id="filtres">
            <h3>Votre annuaire MDM</h3>
            <p>Cet outil vous redirige vers la personne que vous cherchez en fonction de son secteur d\'activité ou de la situation à laquelle vous êtes confronté.</p>
            <div id="listeFiltres">
                <div id="filtreNom">
                    <h5>Son nom</h5>
                    <input type="text" id="champFiltreNom">
                </div>
                <div id="filtreMDM">
                    <h5>Sa MDM</h5>
                    <select name="selectMDM" ID="selectMDM">
                        <option value=""></option>
                        <option value="MDMS Latarjet">MDMS Latarjet</option>
                        <option value="MDMS Jean XXIII">MDMS Jean XXIII</option>
                        <option value="MDMS Félix Brun">MDMS Félix Brun</option>
                        <option value="MDMS Bachut">MDMS Bachut</option>
                        <option value="MDMS Madeleine">MDMS Madeleine</option>
                        <option value="MDMS Bancel">MDMS Bancel</option>
                    </select>
                </div>
                <div id="filtreService">
                    <h5>Son service</h5>
                    <select name="selectService" ID="selectService">
                        <option value=""></option>
                        <option value="Social">Social</option>
                        <option value="Aide à la personne">Aide à la personne</option>
                        <option value="Enfance">Enfance</option>
                        <option value="CCAS">CCAS</option>
                        <option value="Ressources et moyens">Ressources et moyens</option>
                        <option value="Direction">Direction</option>
                    </select>
                </div>
                <div id="filtrePoste">
                    <h5>Son poste</h5>
                    <select name="selectPoste" ID="selectPoste">
                        <option value=""></option>
                        <option value="Travailleur social">Travailleur social</option>
                        <option value="Autre poste santé">Autre poste santé</option>
                        <option value="Instructeur PA">Instructeur PA</option>
                        <option value="Assistant de gestion">Assistant de gestion</option>
                        <option value="Chef de service ou Adjoint">Chef de service ou Adjoint</option>
                        <option value="Instructeur Enfance">Instructeur Enfance</option>
                        <option value="Autre poste administratifs">Autre poste administratifs</option>
                        <option value="Instructeur PH">Instructeur PH</option>
                        <option value="Assistant Médico-Social">Assistant Médico-Social</option>
                        <option value="Médecin">Médecin</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>
                <button onclick="mettreAJourLeGraphe(personnes)">Rechercher</button>
            </div>
        </div>
        <div id="graphe"></div>
        <div id="description"></div>
        <div id="blocBas">
            <p style="color: #00D685">Social</p>
            <p style="color: #FF7D61">Ressources et moyens</p>
            <p class="legende" style="color: #CC8EED">Enfance</p>
            <p class="legende" style="color: #547FF1">Santé</p>
            <p class="legende" style="color: #EDB421">Aide à la personne</p>
            <p class="legende" style="color: #FFE4C6">CCAS</p>
        </div>

    <script type="text/javascript">
        batirGraphe(personnes)
        /*
        function reload() {
            document.location.reload();
        }
        setTimeout(reload, 10000);
         */
    </script>
  </body>';
}

function genererPiedDePage(){
    return '
</html>';
}