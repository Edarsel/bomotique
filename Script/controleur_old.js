/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Effet de fondu lors du changement de page
window.onbeforeunload = function (event) {
    $('#contenu').hide("fade", 500);
}

//Quand la page est générée
$(document).ready(function () {
    //Affiche une localité aléatoire sur la carte
    afficherLocalRandom();
});

//Affiche une localité aléatoire sur la carte
function afficherLocalRandom() {

    $('#frameCarte').hide("fade", 500);

    var iframe = $('#frameCarte')
    var strSrcCarte
    var strCleAPI = "AIzaSyAZSoG_9eDofU0bkA82o_9VT6e1jF-3vrs"

    //iframe.hide()

    strSrcCarte = "https://www.google.com/maps/embed/v1/place?key=" + strCleAPI + "&q=" + objLocaliteRandom.npaLocalite + "+" + objLocaliteRandom.nomLocalite + "&maptype=satellite"
    iframe.attr('src', strSrcCarte)
    iframe.show()

    $('#frameCarte').show("fade", 500);
}

//Actualisation de la carte quand un élève est sélectionné dans la liste
$('#listeEleveClasse').change(function () {
    $('#frameCarte').hide("fade", 500);


    var iframe = $('#frameCarte')
    var strSrcCarte
    var strCleAPI = "AIzaSyAZSoG_9eDofU0bkA82o_9VT6e1jF-3vrs"

    var iEleveID = $("#listeEleveClasse option:selected").val()

    //iframe.hide()

    $.each(lstEleves, function (key, objEleve) {

        if (objEleve.numero == iEleveID) {

            var adresse = (objEleve.adresseRue).replace(" ", "+") + "+" + objEleve.adresseNumero
            strSrcCarte = "https://www.google.com/maps/embed/v1/place?key=" + strCleAPI + "&q=" + adresse + "," + objEleve.npaLocalite + "+" + objEleve.nomLocalite + "&maptype=satellite"
            iframe.attr('src', strSrcCarte)
            iframe.show()
        }
    })

    $('#frameCarte').show("fade", 500);
})

//Actualisation du formulaire d'édition quand un élève est sélectionné dans la liste
$('#listeEleveEdition').change(function () {
    var iEleveID = $("#listeEleveEdition option:selected").val()

    $.each(lstElevesEdition, function (key, objEleve) {
        if (iEleveID == objEleve.numero_eleve) {
            $('#nomEleve').val(objEleve.nomEleve)
            $('#prenomEleve').val(objEleve.prenomEleve)
            $('#rueEleve').val(objEleve.adresseRue)
            $('#rueNumEleve').val(objEleve.adresseNumero)

            $('#userEleve').val(objEleve.nomUtilisateur)

            $('#classeEleve').val(objEleve.num_tbl_classes);
            $('#localiteEleve').val(objEleve.num_tbl_localites);
            if (objEleve.estAdministrateur == 1)
            {
                $('#adminEleve').prop('checked', true);
            }
            else
            {
                $('#adminEleve').prop('checked', false);
            }

            $('#mdpEleve').val("")
            $('#vmdpEleve').val("")
        }
    })
})

//Evénement Click du bouton permettant d'ajouter un élève dans la BD
$('#ajoutEleve').click(function () {
    //event.preventDefault();

    if ($.trim($('#nomEleve').val()) != "") {
        if ($.trim($('#prenomEleve').val()) != "") {
            if ($('#classeEleve').val() != null) {
                if ($.trim($('#rueEleve').val()) != "") {
                    if ($.trim($('#rueNumEleve').val()) != "") {
                        if ($('#localiteEleve').val() != null) {
                            if ($.trim($('#userEleve').val()) != "") {
                                if (verifierNomUserUnique($('#userEleve').val(), null))
                                {
                                    if ($.trim($('#mdpEleve').val()) != "") {
                                        if ($('#mdpEleve').val() == $('#vmdpEleve').val()) {

                                            $("#formEleve #action").val('ajouterEleve');
                                            ajaxAjoutEleve();
                                        } else {
                                            alert("Les 2 mot de passes ne correspondent pas !")
                                            $('#vmdpEleve').focus();
                                        }
                                    } else {
                                        alert("Le mot de passe ne peut pas être vide !")
                                        $('#mdpEleve').focus();
                                    }
                                }
                                else
                                {
                                    alert("Ce nom d'utilisateur est déjà utilisé !")
                                    $('#userEleve').focus();
                                }
                            }
                            else
                            {
                                $("#formEleve #action").val('ajouterEleve');
                                ajaxAjoutEleve();
                            }
                        } else {
                            alert("Sélectionnez une localité !")
                            $('#localiteEleve').focus();
                        }
                    } else {
                        alert("L'adresse de l'élève est incomplète !")
                        $('#rueNumEleve').focus();
                    }
                } else {
                    alert("L'adresse de l'élève est incomplète !")
                    $('#rueEleve').focus();
                }
            } else {
                alert("Sélectionnez une classe !")
                $('#classeEleve').focus();
            }
        } else {
            alert("Le prénom de l'élève ne peut pas être vide !")
            $('#prenomEleve').focus();
        }
    } else {
        alert("Le nom de l'élève ne peut pas être vide !")
        $('#nomEleve').focus();
    }

    function ajaxAjoutEleve() {
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: $('#formEleve').serialize(),
            async: false,
            success: function (response) {
                $("#formEleve #action").val('');
                alert(response)
                actualiserPageEdition()
            }
        });
    }
});


//Evénement Click du bouton permettant de modifier un élève dans la BD
$('#modifEleve').click(function () {
    if ($("#listeEleveEdition option:selected").val() != null)
    {
        if ($.trim($('#nomEleve').val()) != "") {
            if ($.trim($('#prenomEleve').val()) != "") {
                if ($('#classeEleve').val() != null) {
                    if ($.trim($('#rueEleve').val()) != "") {
                        if ($.trim($('#rueNumEleve').val()) != "") {
                            if ($('#localiteEleve').val() != null) {
                                if ($.trim($('#userEleve').val()) != "") {
                                    if (verifierNomUserUnique($('#userEleve').val(), $("#listeEleveEdition option:selected").val()))
                                    {
                                        if ($.trim($('#mdpEleve').val()) != "") {
                                            if ($('#mdpEleve').val() == $('#vmdpEleve').val()) {

                                                $("#formEleve #idEleve").val($("#listeEleveEdition option:selected").val().toString());
                                                $("#formEleve #action").val('modifierEleve');
                                                ajaxModifEleve();
                                            }
                                            else {
                                                alert("Les 2 mot de passes ne correspondent pas !")
                                                $('#vmdpEleve').focus();
                                            }
                                        }
                                        else
                                        {
                                            $("#formEleve #idEleve").val($("#listeEleveEdition option:selected").val().toString());
                                            $("#formEleve #action").val('modifierEleve');
                                            ajaxModifEleve();
                                        }
                                    }
                                    else
                                    {
                                        alert("Ce nom d'utilisateur est déjà utilisé !")
                                        $('#userEleve').focus();
                                    }

                                }
                                else
                                {
                                    $("#formEleve #idEleve").val($("#listeEleveEdition option:selected").val().toString());
                                    $("#formEleve #action").val('modifierEleve');
                                    ajaxModifEleve();
                                }
                            } else {
                                alert("Sélectionnez une localité !")
                                $('#localiteEleve').focus();
                            }
                        } else {
                            alert("L'adresse de l'élève est incomplète !")
                            $('#rueEleve').focus();
                        }
                    } else {
                        alert("L'adresse de l'élève est incomplète !")
                        $('#rueEleve').focus();
                    }
                } else {
                    alert("Sélectionnez une classe !")
                    $('#classeEleve').focus();
                }
            } else {
                alert("Le prénom de l'élève ne peut pas être vide !")
                $('#prenomEleve').focus();
            }
        } else {
            alert("Le nom de l'élève ne peut pas être vide !")
            $('#nomEleve').focus();
        }
    } else
    {
        erreurSelectEleve();
    }

    function ajaxModifEleve() {
        if (verifierNomUserUnique($('#userEleve').val(), $("#listeEleveEdition option:selected").val())) {
            


            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: $('#formEleve').serialize(),
                async: false,
                success: function (response) {
                    $("#formEleve #action").val('');
                    //$("#formEleve #idEleve").val('');
                    alert(response)
                    actualiserPageEdition()
                }
            });
        }
    }
});


//Evénement Click du bouton permettant de supprimer un élève dans la BD
$('#supprEleve').click(function () {

    var idEleve

    var r = confirm("Voulez-vous vraiment supprimer cet élève ?");
    if (r == true) {
        if ($("#listeEleveEdition option:selected").val() != null)
        {
            //idEleve = $("#listeEleveEdition option:selected").val();
            $("#formEleve #action").val('supprimerEleve');
            $("#formEleve #idEleve").val($("#listeEleveEdition option:selected").val().toString());
            
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: $('#formEleve').serialize(),
                async: false,
                success: function (response) {
                    $("#formEleve #action").val('');

                    alert(response)
                    actualiserPageEdition()
                }
            });
        }
        else
        {
            erreurSelectEleve();
        }    
    }
});

//Actualise la page d'édition
function actualiserPageEdition() {
    $("#actualiserEdition").submit();

}

//Vérification de la disponibilité du nom d'utilisateur
$('#userEleve').on({
    "change keyup paste": function () {
        var estUnique = verifierNomUserUnique($('#userEleve').val(), $("#listeEleveEdition option:selected").val())

        if ($.trim($('#userEleve').val()) == "") {
            $('#userEleve').tooltip("disable");
        }
        else {
            if (estUnique) {
                $('#userEleve').tooltip({items: "#userEleve", content: "Ce nom d'utilisateur est disponible !"});
            }
            else
            {
                $('#userEleve').tooltip({items: "#userEleve", content: "Ce nom d'utilisateur est déjà utilisé !"});
            }
            $('#userEleve').tooltip("open");
        }
    },
    "focusout": function () {
        $('#userEleve').tooltip("disable");
    }
});

//Vérification de la disponibilité du nom d'utilisateur
function verifierNomUserUnique(nomUtilisateur, indexUtilisateur) {
    var estUnique = true;

    $.each(lstElevesEdition, function (key, objEleve) {
        if (objEleve.numero_eleve != indexUtilisateur) {
            if (objEleve.nomUtilisateur == nomUtilisateur) {
                estUnique = false;
            }
        }
    })

    return estUnique;
}

//Si aucun élève n'est sélectionné dans la page d'édition
function erreurSelectEleve() {
    alert("Sélectionnez un élève dans la liste pour effectuer cette action.");
}