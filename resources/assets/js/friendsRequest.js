(function($){
    "use strict";

    var $button = $('#friends-request-tab');
    var $tab = $('#friends-request');

    $button.click(function($e){
        $e.preventDefault();
        $tab.empty();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            dataType: 'json',
            type: 'GET',
            url: '/friends/request',
            success: function($data){
                if (jQuery.isEmptyObject($data) ){
                    $tab.append(
                        '<div class="container-">\
                            <div class="row my-5">\
                                <div class="col-12 text-center">\
                                    <p>Nessun richiesta di amicizia presente</p>\
                                </div>\
                            </div>\
                        </div>'
                    )
                }
                else{
                    $tab.append('<div class="container">')
                    $.each($data, function($i, $user) {
                        $tab.append(
                            '<div class="row my-5">\
                                <div class="col-4 col-md-2 text-center">\
                                    <img src="/storage/'+$user.image_url+'" alt="user image" class="img-fluid rounded-circle img-thumbnail img-md">\
                                </div>\
                                <div class="col-8 col-md-5 d-flex">\
                                    <span class="align-self-center"><a href="/profile/'+$user.id+'">'+$user.name+' '+$user.surname+'</a></span>\
                                </div>\
                                <div class="col-12 col-md-5 d-flex flex-column justify-content-around mt-4 mt-md-0">\
                                    <button type="button" class="btn btn-success align-self-center friendship-accept-button" data-id="'+$user.id+'">Accetta richiesta di amicizia</button>\
                                    <button type="button" class="btn btn-danger align-self-center friendship-delete-button mt-3" data-id="'+$user.id+'">Rifiuta richiesta di amicizia</button>\
                                </div>\
                            </div>'
                        );
                    });
                    $tab.append('</div>');
                }

            },
            error: function($data){
                var $errors = $data.responseJSON;
                console.log($errors);
            }
        });
    });

    $button.on('hidden.bs.tab', function(){
        $tab.empty();
    });
})(jQuery);
