//  import swal from 'sweetalert';

$(document).ready(function() {
    $('[data-confirm]').on('click', function(e) {
        e.preventDefault(); //Annuler l'action par défaut
        //Récupérer la valeur de l'attribut href
        var href = $(this).attr('href');
        //Récupérer la valeur de l'attribut data-confirm
        var message = $(this).data('confirm');

        //On aurait pu écrire aussi
        //var message = $(this).attr('data-confirm');

        //Afficher la popup SweetAlert
        swal({
                title: "Are you sure?",
                text: message,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    swal("Houf! Supression confirmée !", {
                        icon: "success",
                        buttons: false,
                    });
                    window.location.href = href;
                } else {
                    swal("Annulation confirmé!");
                }
            });
    });

    $('a.like').on('click', function(event) {
        event.preventDefault();

        var id = $(this).attr('id');
        var url = "ajax/micropost_like.php";
        var action = $(this).data('action');
        var micropost_id = id.split('like')[1];

        $.ajax({
            type: 'POST',
            url: url,
            data: {
                micropost_id: micropost_id,
                action: action
            },

            success: function(likers) {
                $("#likers_" + micropost_id).html(likers);
                if (action == 'like') {
                    $("#" + id).html("Je n'aime plus").data('action', 'unlike');
                } else {
                    $("#" + id).html("J'aime").data('action', 'like');
                }
            }
        });
    });

    $('#searchbox').keyup(function() {

        var query = $(this).val();
        var url = 'ajax/search.php';

        if (query.length > 0) {
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    query: query
                },
                beforSend: function() {
                    $("#spinner").show();
                },

                success: function(data) {
                    $("#spinner").hide();
                    $('#display-results').html(data).show();
                }
            });
        } else {
            $('#display-results').hide();
        }

    });

});