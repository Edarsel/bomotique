function OnOffLED() {
  console.debug("ON/OFF");
  $.ajax({
    url: 'index.php',
    type: 'POST',
    data: {action: "LedOnOff"},
    async: false,
    success: function (response) {
      //alert(response);
    }
  });
}

//APRES QUE LE DOM SOIT PRET
$( document ).ready(function() {
  console.log("controleur.js est chargé");

  var myVar = setInterval(updtEtatLed, 5000);

  dataTableLog();

  function updtEtatLed() {
    $.get("index.php?action=pagePrincipale").then(function(page) {
      $("#lblOnOff").html($(page).find("#lblOnOff").html());
    });
  }

  //Actualisation du formulaire d'édition quand un utilisateur est sélectionné dans la liste
  $(document).on('change', "#listeUtilEdition", function () {
    //$('#listeUtilEdition').change(function () {
    var iUtilID = $("#listeUtilEdition option:selected").val();

    $.each(lstUtilisateur, function (key, objUtil) {
      if (iUtilID == objUtil.numero) {
        $('#nomUtil').val(objUtil.nomUtilisateur);

        if (objUtil.estAdministrateur == 1)
        {
          $('#adminUtil').prop('checked', true);
        }
        else
        {
          $('#adminUtil').prop('checked', false);
        }

        $('#mdp').val("");
        $('#vmdp').val("");
      }
    })
  });

  //Ordonner le tableau avec le script DataTables.net
  function dataTableLog() {
    var table = $('#tableauLogsConnexion').DataTable();
    //Trier les données du plus récent au plus vieux
    table
    .column( '0' )
    .order( 'desc' )
    .draw();
  }

  $('#btnImpulsion').click(function () {

    $.ajax({
      url: 'index.php',
      type: 'POST',
      data: {action: "Impulsion"},
      async: false,
      success: function (response) {
        console.debug("Fin du chrono");
        //alert(response)
        //actualiserPageEdition()
      }
    });
  });


  $('#supprLog').click(function () {

    $.ajax({
      url: 'index.php',
      type: 'POST',
      data: $('#formSupprLog').serialize(),
      async: false,
      success: function (response) {
        alert(response);
        $.get("index.php?action=pageLogsConnexion").then(function(page) {
          $("#divTableauLogsConnexion").html($(page).find("#divTableauLogsConnexion").html());
          dataTableLog();
        })
      }
    });
  });

  $('#enrParamSecu').click(function () {

    $.ajax({
      url: 'index.php',
      type: 'POST',
      data: $('#formParamSecurite').serialize(),
      async: false,
      success: function (response) {
        alert(response);
      }
    });
  });

  $('#enrImpulsLED').click(function () {
    var iTemps = $.trim($('#tempsImpulsion').val());

    if(Math.floor(iTemps) == iTemps && $.isNumeric(iTemps))
    {
      console.debug(iTemps);

      $('#cbxOnOff').prop("checked", false);

      $.ajax({
        url: 'index.php',
        type: 'POST',
        data: $('#formParamLED').serialize(),
        async: false,
        success: function (response) {
          alert(response)
          //actualiserPageEdition()
          $('#cbxOnOff').prop("checked", false);
        }
      });
    }
    else {
      alert("Veuillez entrer un nombre !")
    }
  });

  $('#enrModeCo').click(function () {

    if ($('#modeCoMDP').is(':checked'))
    {
      if ($.trim($('#mdp').val()) != "") {
        if ($('#mdp').val() == $('#vmdp').val()) {

          ajaxEnrMode();
        } else {
          alert("Les 2 mot de passes ne correspondent pas !")
          $('#vmdp').focus();
        }
      } else {
        alert("Le mot de passe ne peut pas être vide !")
        $('#mdp').focus();
      }
    }
    else
    {
      ajaxEnrMode();
    }

    function ajaxEnrMode() {
      $.ajax({
        url: 'index.php',
        type: 'POST',
        data: $('#formParamConnexion').serialize(),
        async: false,
        success: function (response) {
          alert(response)
          //actualiserPageEdition()
        }
      });
    }
  });

  //Evénement Click du bouton permettant d'ajouter un élève dans la BD
  $('#ajoutUtil').click(function () {
    //event.preventDefault();

    if ($.trim($('#nomUtil').val()) != "") {
      if (verifierNomUserUnique($('#nomUtil').val(), null))
      {
        if ($.trim($('#mdp').val()) != "") {
          if ($('#mdp').val() == $('#vmdp').val()) {

            $("#formEdition #action").val('ajouterUtil');
            ajaxAjoutUtil();

            //$("#ListBoxUtil").load("index.php?action=pageEditionUtil #ListBoxUtil");

            //Nouvelle méthode qui remplace le ".load"
            //Pour recharger la listbox
            $.get("index.php?action=pageEditionUtil").then(function(page) {
              $("#ListBoxUtil").html($(page).find("#ListBoxUtil").html())
            })

          } else {
            alert("Les 2 mot de passes ne correspondent pas !")
            $('#vmdp').focus();
          }
        } else {
          alert("Le mot de passe ne peut pas être vide !")
          $('#mdp').focus();
        }
      }
      else
      {
        alert("Ce nom d'utilisateur est déjà utilisé !")
        $('#nomUtil').focus();
      }
    }


    function ajaxAjoutUtil() {
      $.ajax({
        url: 'index.php',
        type: 'POST',
        data: $('#formEdition').serialize(),
        async: false,
        success: function (response) {
          $("#formEdition #action").val('');
          alert(response)
          //actualiserPageEdition()
        }
      });
    }
  });

  //Evénement Click du bouton permettant de modifier un élève dans la BD
  $('#modifUtil').click(function () {

    if ($("#listeUtilEdition option:selected").val() != null)
    {
      if ($.trim($('#nomUtil').val()) != "") {
        if (verifierNomUserUnique($('#nomUtil').val(), $("#listeUtilEdition option:selected").val()))
        {
          if ($.trim($('#mdp').val()) != "") {
            if ($('#mdp').val() == $('#vmdp').val()) {

              $("#formEdition #idUtil").val($("#listeUtilEdition option:selected").val().toString());
              $("#formEdition #action").val('modifierUtil');
              ajaxModifUtil();

              //Pour recharger la listbox
              $.get("index.php?action=pageEditionUtil").then(function(page) {
                $("#ListBoxUtil").html($(page).find("#ListBoxUtil").html())
              })

            }
            else {
              alert("Les 2 mot de passes ne correspondent pas !")
              $('#vmdp').focus();
            }
          }
          else
          {
            $("#formEdition #idUtil").val($("#listeUtilEdition option:selected").val().toString());
            $("#formEdition #action").val('modifierUtil');
            ajaxModifUtil();

            //Pour recharger la listbox
            $.get("index.php?action=pageEditionUtil").then(function(page) {
              $("#ListBoxUtil").html($(page).find("#ListBoxUtil").html())
            })
          }
        }
        else
        {
          alert("Ce nom d'utilisateur est déjà utilisé !")
          $('#nomUtil').focus();
        }
      }
      else
      {
        alert("Le nom d'utilisateur ne doit pas être vide !")
        $('#nomUtil').focus();
      }
    }else
    {
      erreurSelectEleve();
    }


    function ajaxModifUtil() {
      if (verifierNomUserUnique($('#nomUtil').val(), $("#listeUtilEdition option:selected").val())) {



        $.ajax({
          url: 'index.php',
          type: 'POST',
          data: $('#formEdition').serialize(),
          async: false,
          success: function (response) {
            $("#formEdition #action").val('');
            //$("#formEleve #idEleve").val('');
            alert(response)
            //actualiserPageEdition()
          }
        });
      }
    }
  });

  $('#supprUtil').click(function () {

    var idEleve

    var r = confirm("Voulez-vous vraiment supprimer cet utilisateur ?");
    if (r == true) {
      if ($("#listeUtilEdition option:selected").val() != null)
      {
        //idEleve = $("#listeUtilEdition option:selected").val();
        $("#formEdition #action").val('supprimerUtil');
        $("#formEdition #idUtil").val($("#listeUtilEdition option:selected").val().toString());

        $.ajax({
          url: 'index.php',
          type: 'POST',
          data: $('#formEdition').serialize(),
          async: false,
          success: function (response) {
            $("#formEdition #action").val('');

            alert(response)
          }
        });

        //Rechargement de la page
        window.location.replace(location.href);
      }
      else
      {
        erreurSelectEleve();
      }   
    }
  });

})



//Vérification de la disponibilité du nom d'utilisateur
function verifierNomUserUnique(nomUtilisateur, indexUtilisateur) {
  var estUnique = true;

  $.each(lstUtilisateur, function (key, objUtil) {
    if (objUtil.numero != indexUtilisateur) {
      if ($.trim(objUtil.nomUtilisateur) == $.trim(nomUtilisateur)) {
        estUnique = false;
      }
    }
  })

  return estUnique;
}
