/**
 *
 * @param personnes
 */
function mettreAJourLeGraphe(personnes=[]){
    let listePersonnesCorrespondant = []

    const texteDeRecherche = document.getElementById('champFiltreNom').value
    const MDMDeRecherche =  document.getElementById("selectMDM").value;
    const serviceDeLaRecherche =  document.getElementById("selectService").value;
    const posteDeLaRecherche =  document.getElementById("selectPoste").value;

    for (let i = 0; i < personnes.length; i++) {
        if (
            personneCorrespondAuTexte(personnes[i], texteDeRecherche) &&
            personneCorrespondADM(personnes[i], MDMDeRecherche) &&
            personneCorrespondAuService(personnes[i], serviceDeLaRecherche) &&
            personneCorrespondAuPoste(personnes[i], posteDeLaRecherche)
        ){
            listePersonnesCorrespondant.push(personnes[i])
        }
    }
    batirGraphe(listePersonnesCorrespondant)
}

/**
 *
 * @param personne
 * @param poste
 * @returns {boolean}
 */
function personneCorrespondAuPoste(personne={}, poste=''){
    return poste === '' || poste === personne.poste;
}

/**
 *
 * @param personne
 * @param service
 * @returns {boolean}
 */
function personneCorrespondAuService(personne={}, service=''){
    return service === '' || service === personne.service;
}


/**
 * La personne est-elle de la MDM voulue ?
 * @param personne
 * @param MDM
 * @returns {boolean}
 */
function personneCorrespondADM(personne={}, MDM=''){
    return MDM === '' || MDM === personne.mdm;
}


/**
 * Le texte recherché se trouve-t-il dans le nom ou le prénom de la personne ?
 * @param personne
 * @param texteVoulu
 * @returns {boolean}
 */
function personneCorrespondAuTexte(personne={}, texteVoulu=''){
    const nom = personne.nom.toLowerCase()
    const prenom = personne.prenom.toLowerCase()
    texteVoulu = texteVoulu.toLowerCase()
    return nom.includes(texteVoulu) || prenom.includes(texteVoulu);

}

/**
 * Construit le graphe en fonction d'un tableau d'objets représentant des personnes et
 * leurs MDM respectives.
 * @param personnes
 */
function batirGraphe(personnes=[]){
    /* CRÉATION DES NŒUDS */
    let listeNoeuds= []
    // Nœud central
    const noeudMetropole = {"id":1,"shape": "circularImage", "image": 'data/logo.png', color: "#E5E5E5", value:40}
    listeNoeuds.push(noeudMetropole)
    // Nœuds MDM
    const noeudsMDM = genererNoeudsMDM(personnes)
    listeNoeuds = listeNoeuds.concat(noeudsMDM)
    // Nœuds personnes
    const [noeudsPersonnes, liensPersonnes] = genererNoeudsPersonnes(personnes, listeNoeuds)
    listeNoeuds = listeNoeuds.concat(noeudsPersonnes)
    // Injection des nœuds dans le graphe.
    const nodes = new vis.DataSet(listeNoeuds);

    // LISTE DES LIENS
    let listeLiens = []
    /* CRÉATION DES LIENS */
    listeLiens = listeLiens.concat(genererLiensMetropole_MDM(noeudMetropole, noeudsMDM))
    listeLiens = listeLiens.concat(liensPersonnes)

    // create an array with edges
    const edges = new vis.DataSet(listeLiens);

    // create a network
    const container = document.getElementById("graphe");
    const data = {
        nodes: nodes,
        edges: edges,
    };
    const options = {
        nodes: { borderWidth: 5 },
    };
    const network = new vis.Network(container, data, options);

    network.on("click", function(e) {
        mettreAJourLaDescription(personnes, e.nodes[0], noeudsMDM)
    });
}


/**
 * On modifie le contenu HTML du div de description avec les informations de la personne dont on a reçu l'id.
 * @param listePersonnes
 * @param idPersonne
 * @param noeudsMDM
 */
function mettreAJourLaDescription(listePersonnes=[], idPersonne=0, noeudsMDM=[]){
    const veritableID = idPersonne-noeudsMDM.length-3 // On retire le nombre de nœuds MDM + le nœud Métropole
    const blocDescription = document.getElementById('description')
    blocDescription.style.border = 'solid 1px black';
    if(listePersonnes[veritableID]===undefined){

        // IMAGE
        const imgPersonne = document.createElement('img')
        imgPersonne.id = 'imgPersonne'
        imgPersonne.src = "data/portraits_mdm/femme/"+ listePersonnes[veritableID].photo
        if (listePersonnes[veritableID].genre==='homme'){
            imgPersonne.src = "data/portraits_mdm/homme/"+ listePersonnes[veritableID].photo
        }
        imgPersonne.height=50
        imgPersonne.width=50
        // PRÉNOM
        const paragraphePrenomPersonne = document.createElement('p')
        paragraphePrenomPersonne.id = 'paragraphePrenomPersonne'
        const textePrenomPersonne = document.createTextNode(listePersonnes[veritableID].prenom)
        paragraphePrenomPersonne.appendChild(textePrenomPersonne)
        // NOM
        const paragrapheNomPersonne = document.createElement('p')
        paragrapheNomPersonne.id = 'paragrapheNomPersonne'
        const texteNomPersonne = document.createTextNode(listePersonnes[veritableID].nom)
        paragrapheNomPersonne.appendChild(texteNomPersonne)
        // SERVICE
        const paragrapheService = document.createElement('p')
        paragrapheService.id = 'paragrapheService'
        const texteServicePersonne = document.createTextNode(listePersonnes[veritableID].service)
        paragrapheService.appendChild(texteServicePersonne)
        // POSTE
        const paragraphePoste = document.createElement('p')
        paragraphePoste.id = 'paragraphePoste'
        const textePostePersonne = document.createTextNode(listePersonnes[veritableID].poste)
        paragraphePoste.appendChild(textePostePersonne)
        // ANCIENNETÉ
        const paragrapheAnciennete = document.createElement('p')
        paragrapheAnciennete.id = 'paragraphePoste'
        const texteAnciennetePersonne = document.createTextNode(listePersonnes[veritableID].anciennete)
        paragrapheAnciennete.appendChild(texteAnciennetePersonne)
        // E-MAIL
        const paragrapheEMail = document.createElement('p')
        paragrapheEMail.id = 'paragraphePoste'
        const texteEMailPersonne = document.createTextNode(listePersonnes[veritableID].email)
        paragrapheEMail.appendChild(texteEMailPersonne)
        // TÉLÉPHONE
        const paragrapheTelephone = document.createElement('p')
        paragrapheTelephone.id = 'paragraphePoste'
        const texteTelephonePersonne = document.createTextNode(listePersonnes[veritableID].telephone)
        paragrapheTelephone.appendChild(texteTelephonePersonne)

        blocDescription.replaceChildren(
            imgPersonne,
            paragraphePrenomPersonne,
            paragrapheNomPersonne,
            paragrapheService,
            paragraphePoste,
            paragrapheAnciennete,
            paragrapheEMail,
            paragrapheTelephone
        )
    }
}



/**
 * Génère un tuple (array en fait) contenant un array des nœuds des personnes et un second array des
 * nœeuds liant ces personnes à leurs MDM.
 * @param personnes
 * @param autresNoeuds
 * @returns {*[][]}
 */
function genererNoeudsPersonnes(personnes=[], autresNoeuds=[]){
    let listePersonnes = []
    let listeLiensPersonnes = []

    for (let i = 0; i < personnes.length; i++) {
        let uriImage = "data/portraits_mdm/femme/"+ personnes[i].photo
        if (personnes[i].genre==='homme'){
            uriImage = "data/portraits_mdm/homme/"+ personnes[i].photo
        }

        if(i>=personnes.length-1){
            listePersonnes.push(
                {
                    "id":i+2+autresNoeuds.length,
                    label: personnes[i].prenom + '\n' + personnes[i].nom,
                    shape: "circle",
                    color: "#FB7E81",
                    value: 20
                }
            )
        } else {
            listePersonnes.push(
                {
                    "id":i+2+autresNoeuds.length,
                    //"label":personnes[i].prenom + ' ' + personnes[i].nom,
                    "shape": "circularImage",
                    "image": uriImage,
                    "color": getColorFromService(personnes[i].service),
                    value:10
                }
            )
        }

        listeLiensPersonnes.push(
            {
                "from":recupererIdNoeudFromLabel(autresNoeuds, personnes[i].mdm), // En théorie, chaque noeud aura créé un lien.
                "to":i+2+autresNoeuds.length,
                color: { color: getColorFromService(personnes[i].service)  }
            }
        )
    }
    return [listePersonnes, listeLiensPersonnes]
}


function getColorFromService(service=''){

    let couleur = '#FFE4C6';
    switch (service) {
        case 'Social':
            couleur = '#00D685'
            break;
        case 'Ressources et moyens':
            couleur = '#FF7D61'
            break;
        case 'Enfance':
            couleur = '#CC8EED'
            break;
        case 'Santé':
            couleur = '#547FF1'
            break;
        case 'Aide à la personne':
            couleur = '#EDB421'
            break;
        case 'CCAS':
            couleur = '#FFE4C6'
            break;
        default:
            break
    }
    return couleur
}

/**
 * Si on cherche un nom de noeud et qu'on souhaite récupérer son id.
 * -> On cherche dans un tableau d'objets de type {"id":1,"label":"nomNoeud"}
 * @param noeuds
 * @param labelVoulu
 * @returns {number|*}
 */
function recupererIdNoeudFromLabel(noeuds=[], labelVoulu=''){
    for (let i = 0; i < noeuds.length; i++) {
        if (noeuds[i].label === labelVoulu){
            return noeuds[i].id
        }
    }
    return 1
}

/**
 * À partir des données complètes des personnes, on extraie une liste de MDM. Cette liste de MDM sera ensuite
 * utilisée pour bâtir un tableau d'objets-nœuds représentant ces MDM.
 * @param personnes La liste des données des personnes.
 * @returns {*[]} Une liste de nœuds représentant chaque MDM de manière unique (pas de doublons).
 */
function genererNoeudsMDM(personnes=[]){
    let listeNomsMDM = []
    let listeNoeudsMDM = []
    //console.log(personnes)
    for (let i = 0; i < personnes.length; i++) {
        if (!listeNomsMDM.includes(personnes[i].mdm)){
            listeNomsMDM.push(personnes[i].mdm)
        }
    }
    //console.log(listeNomsMDM)
    for (let i = 0; i < listeNomsMDM.length; i++) {
        listeNoeudsMDM.push(
            {
                "id":i+2,
                "label":listeNomsMDM[i],
                "shape": "circle",
                margin: 20,
                color: { background: "#E5E5E5", border: "black" },
            }
        )
    }
    return listeNoeudsMDM
}

/**
 * À partir d'un objet-nœud représentant le nœud principal et d'un tableau d'objets-nœud représentant les nœuds
 * des MDM, on créé une liste de liens menant du nœud principal à chacun de ces nœuds de MDM.
 * @param noeudMetropole L'objet-nœud représentant le nœud principal.
 * @param noeudsMDM La liste des objets représentant les nœuds des MDM.
 * @returns {*[]} Une liste d'objets-liens menant du nœud principal aux différents nœuds-MDM.
 */
function genererLiensMetropole_MDM(noeudMetropole={}, noeudsMDM=[]){
    let liensMetropole_MDM = []
    for (let i = 0; i < noeudsMDM.length; i++) {
        const nouveauLien = {
            "from": noeudMetropole.id,
            "to":noeudsMDM[i].id,
        }
        liensMetropole_MDM.push(nouveauLien)
    }
    return liensMetropole_MDM
}