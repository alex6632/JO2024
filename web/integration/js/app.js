(function ($) {
  var app = {
    openModal: function (el) {
      $('.' + el).on('click', function() {
        $('#' + el).addClass('show');
        $('.modal-bg').fadeIn();
      });
    },
    closeModal: function (el) {
      $('.modal ' + el).on('click', function() {
        $(this).parents('.modal').removeClass('show');
        $('.modal-bg').fadeOut();
      });
    },
    openModalConfirmDelete: function () {
      $('#listVille').on('click', '.jsModalConfirmDelete', function() {
        $(this).next().addClass('show');
        $('.modal-bg').fadeIn();
      });
    },
    closeModalConfirmDelete: function (el) {
      $('#listVille').on('click', '.modal ' + el, function() {
        $(this).parents('.modal').removeClass('show');
        $('.modal-bg').fadeOut();
      });
    },
    closeFlashMessage: function () {
      $('.bloc-alert').on('click', '.close', function() {
        $('.alert').fadeOut();
      });
    },
    ajaxCreate: function (el) {
      $('#jsModalCreate' + el + ' form').submit(function(e) {
        e.preventDefault();
        // Disabled le bouton submit pour éviter que l'utilisateur ne re clic dessus pendant le traitement
        $('#jsModalCreate' + el + ' form button').prop( "disabled", true );
        var urlList = $(el + ' .jsUrlList').val();

        $.ajax({
          url: urlList,
          type: 'POST',
          data: $(this).serialize(),
          success: function(response) {
            if(response.type == 'success') {
              $('#jsModalCreate' + el + ' form button').prop('disabled', false);
              $('.modal').removeClass('show');
              $('.modal-bg').fadeOut();
              $('#list' + el + ' tbody').append('<tr id="lineVille' + response.id + '"><td>' + response.nom + '</td><td><a class="jsModalConfirmDelete">' + response.deleteLink + '</a><div class="modal"><div class="close jsCloseModalConfirmDelete">X ' + response.closeText + '</div><h2>' + response.titleModal + '</h2><div class="choice"><input type="hidden" value="villes/delete/' + response.id + '" class="jsUrlModal' + response.id + '"><a class="jsActionDelete" id="' + response.id + '">' + response.yes + '</a><a class="jsCloseModalConfirmDelete">' + response.no + '</a></div></div><a href="villes/edit/' + response.id + '">' + response.editLink + '</a></td></tr>');
              $('.block-alert').append('<div class="alert alert--success" role="alert">' + response.msg + '</div>');
            }
          },
          error: function(response) {
            console.log(response);
            $('.block-alert').append('<div class="alert alert--error" role="alert">' + response.msg + '</div>');
          }
        })
      });
    },
    ajaxDelete: function (el) {
      $('#listVille').on('click', '.jsActionDelete', function(e) {
        e.preventDefault();
        var id      = $(this).attr('id'),
            urlList = $(this).parent().find('.jsUrlModal' + id).val();
        $('#lineVille' + id + ' .modal .choice a').prop( "disabled", true );

        $.ajax({
          url: urlList,
          type: 'GET',
          success: function(response) {
            if(response.type == 'success') {
              $('#lineVille' + id + ' .modal .choice a').prop( "disabled", false );
              $('.modal').removeClass('show');
              $('.modal-bg').fadeOut();
              $('#lineVille' + id).remove();
              $('.block-alert').append('<div class="alert alert--success" role="alert">' + response.msg + '</div>');
            }
          }
        })
      });
    },
    ajaxShow: function () {
      $('.jsModalEdit').on('click', function(e) {
        e.preventDefault();
        var id      = $(this).attr('id'),
            urlList = $(this).parent().find('.jsUrlModal_' + id).val(),
            urlEdit = $(this).parent().find('.jsUrlModal_' + id).attr('data-urlEdit'),
            idModal = $('#jsModalEditVille');

        $.ajax({
          url: urlList,
          type: 'GET',
          dataType : 'html',
          success: function(response) {
            idModal.addClass('show');
            $('.modal-bg').fadeIn();
            idModal.append(response + '<input type="hidden" value="' + urlEdit + '" data-id="' + id + '" class="jsUrlEdit">').addClass('show');
          }
        })
      });
    },
    ajaxUpdate: function (el) {
      $(document).on('submit','#jsModalEdit' + el + ' form',function(e) {
        e.preventDefault();
        var urlEdit = $(this).next().val(),
            idModal = $('#jsModalEditVille');

        $('#jsModalEdit' + el + ' form button').prop( "disabled", true );

        $.ajax({
          url: urlEdit,
          type: 'POST',
          data: $(this).serialize(),
          success: function(response) {
            if(response.type == 'success') {
              $('#jsModalEdit' + el + ' form button').prop( "disabled", false );
              idModal.removeClass('show');
              $('.modal-bg').fadeOut();
              $('#lineVille' + response.idVille + ' td').first().html(response.nom);
              $('.block-alert').append('<div class="alert alert--success" role="alert">' + response.msg + '</div>');
            }
          },
          error: function(response) {
            $('.block-alert').append('<div class="alert alert--error" role="alert">' + response.msg + '</div>');
          }
        })
      });
    }
  };

  $(document).ready(function () {

    // Open create modal events
    app.openModal('jsModalCreatePays');
    app.openModal('jsModalCreateDiscipline');
    app.openModal('jsModalCreateAthlete');
    app.openModal('jsModalCreateVille');
    app.openModalConfirmDelete();

    // Close modals events
    app.closeModal('.close');
    app.closeModalConfirmDelete('.jsCloseModalConfirmDelete');

    // Hide flash message
    app.closeFlashMessage();

    // Init create city ajax
    app.ajaxCreate('Ville');
    app.ajaxCreate('Discipline');

    // Init delete city ajax
    app.ajaxDelete('Ville');

    // Init update city ajax
    app.ajaxShow();
    app.ajaxUpdate('Ville');
  });
})(jQuery);