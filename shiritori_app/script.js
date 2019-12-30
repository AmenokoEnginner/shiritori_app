$(function() {
  $('#submit').on('click', function() {
    var prev = $('#prev-2').text();

    $.ajax({
      type: 'POST',
      url: '_answer.php',
      data: {
        current: $('#input').val(),
        player: $('#player').text(),
        token: $('#token').val()
      },
    }).done(function(res) {
      if (res.next == 'ん') {
        window.location.href = 'result.php';
      }

      if (res.error) {
        $('#error').css('display', 'block');
        return false;
      }

      $('#input').val('');
      $('#error').css('display', 'none');
      $('#prev-1').text(prev);
      $('#prev-2').text(res.current);
      $('#current').text(res.next);

      if ($('#player').text() == 'player2') {
        $('#player').text('player1');
      } else {
        $('#player').text('player2');
      }
    }).fail(function() {
      console.log('Ajax Error');
    });
  });
});
