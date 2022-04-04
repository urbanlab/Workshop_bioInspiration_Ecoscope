<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST) && $_POST!==array()){
    $nouvelAgent=array(
        'nom'=>$_POST['nom'],
        'prenom'=>$_POST['prenom'],
        'experience'=>$_POST['experience'],
        'arrondissement'=>$_POST['arrondissement'],
        'selectMDM'=>$_POST['selectMDM'],
        'selectService'=>$_POST['selectService'],
        'selectPoste'=>$_POST['selectPoste'],
        'descrPoste'=>$_POST['descrPoste'],
        'email'=>$_POST['email'],
        'telephone'=>$_POST['telephone'],
    );
    insererNouvelAgent($nouvelAgent);
}

function insererNouvelAgent($nouvelAgent=array()){
    try {
        $fp = fopen('data/prototypeData.csv', 'a+');
        fputcsv($fp, $nouvelAgent);
        fclose($fp);
    }catch (Exception $e){
        var_dump($e);
    }

}

echo '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Trombi_MDM</title>

    <!-- Fake favicon, to avoid extra request to server -->
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">

    <link rel="stylesheet" type="text/css" href="form.css">

    <script type="text/javascript" src="form.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<form class="contentarea" id="formulaire" method="post">
    <h1>
        Formulaire d\'inscription : je travaille à la MDM
    </h1>
    
    <div class="blocPrincipal" id="blocGauche">
        <div class="camera">
            <video id="video">Video stream not available.</video>
            <button id="startbutton">Prendre photo</button>
        </div>
        <canvas id="canvas">
        </canvas>
        <div class="output">
            <img id="photo" alt="The screen capture will appear in this box.">
        </div>
    </div>
    <div class="blocPrincipal" id="blocMilieu">
        <div id="blocMilieuHaut">
            <div id="blocMilieuHautGauche">
                <h3>Qui êtes-vous ?</h3>
                <p>Prénom</p>
                <input type="text" name="prenom">
                <p>Nom</p>
                <input type="text" name="nom">
                <p>Années d\'expérience</p>
                <input type="text" name="experience">
            </div>
            <div id="blocMilieuHautDroite">
            <h3>Où travaillez-vous ?</h3>
            <p>Arrondissement</p>
            <select name="arrondissement">
                <option value=""></option>
                <option value="7è arrondissement">7è arrondissement</option>
                <option value="8è arrondissement">8è arrondissement</option>
            </select>
            <p>Nom de votre MDM</p>
            <select name="selectMDM" id="selectMDM">
                <option value=""></option>
                <option value="MDMS Latarjet">MDMS Latarjet</option>
                <option value="MDMS Jean XXIII">MDMS Jean XXIII</option>
                <option value="MDMS Félix Brun">MDMS Félix Brun</option>
                <option value="MDMS Bachut">MDMS Bachut</option>
                <option value="MDMS Madeleine">MDMS Madeleine</option>
                <option value="MDMS Bancel">MDMS Bancel</option>
            </select>
            <p>Nom du service</p>
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
        </div>
        <div id="blocMilieuBas">
            <h3>Comment pouvons-nous vous contacter ?</h3>
            <div id="blocMilieuBasGauche">
                <p>Email</p>
                <input type="text" name="email">
            </div>
            <div id="blocMilieuBasDroite">
                <p>Numéro de téléphone</p>
                <input type="text" name="telephone">
                <p><input type="submit"></input></p>
            </div>
        </div>
    </div>
    <div class="blocPrincipal" id="blocDroite">
        <h3>En quoi consiste votre travail ?</h3>
        <p>Intitulé de votre poste</p>
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
        <p>Comment décririez-vous votre poste ?</p>
        <textarea rows="10" cols="20" name="descrPoste"></textarea>
    </div>
</form>

</body>
</html>';