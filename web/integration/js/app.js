(function ($) {
  var app = {
    openModal: function (el) {
      $('.'+el).on('click', function() {
        $('#'+el).addClass('show');
        $('.modal-bg').fadeIn();
      });
    }
  };

  $(document).ready(function () {

    app.openModal('jsModalCreatePays');
    app.openModal('jsModalCreateDiscipline');
    app.openModal('jsModalCreateAthlete');

    $('.modal .close').on('click', function() {
      $(this).parent().removeClass('show');
      $('.modal-bg').fadeOut();
    });

    $('.bloc-alert').on('click', '.close', function() {
      $('.alert').fadeOut();
    });

  });
})(jQuery);