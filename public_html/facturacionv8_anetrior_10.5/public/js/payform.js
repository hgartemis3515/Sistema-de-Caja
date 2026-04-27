'use strict';
var stripe = Stripe('pk_live_Kh3ZX4WmHsaKRvXI35LOO6RR');
var elements = stripe.elements();

$(function() {
    inicializar_controles();

    var elements = stripe.elements({
        fonts: [{
            cssSrc: 'https://fonts.googleapis.com/css?family=Roboto',
        }, ],
        // Stripe's payforms are localized to specific languages, but if
        // you wish to have Elements automatically detect your user's locale,
        // use `locale: 'auto'` instead.
        locale: window.__payform_nayariLocale
    });

    var card = elements.create('card', {
        iconStyle: 'solid',
        style: {
            base: {
                iconColor: '#7fbf5c',
                color: '#616972',
                fontWeight: 500,
                fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
                fontSize: '15px',
                fontSmoothing: 'antialiased',

                ':-webkit-autofill': {
                    color: '#fce883',
                },
                '::placeholder': {
                    color: '#7fbf5c',
                },
            },
            invalid: {
                iconColor: '#910d0d',
                color: '#910d0d',
            },
        },
    });
    card.mount('#payformnayari-card');

    registerElements([card], 'payformnayari');

});

function inicializar_controles() {
    $('.select').select2({
        minimumResultsForSearch: -1
    });

    $('input[type=radio][name=opcion_metodo_pago]').change(function() {
        if(this.value == 'paypal') {
            $(".contenedor_paypal").show();
            $(".contenedor_tarjeta").hide();
        }

        if(this.value == 'tarjeta') {
            $(".contenedor_tarjeta").show();
            $(".contenedor_paypal").hide();
        }
    });
}

function registerElements(elements, payform_nayariName) {
    var formClass = '.' + payform_nayariName;
    var payform_nayari = document.querySelector(formClass);

    var form = payform_nayari.querySelector('form');
    var resetButton = payform_nayari.querySelector('a.reset');
    var error = form.querySelector('.error');
    var errorMessage = error.querySelector('.message');

    function enableInputs() {
        Array.prototype.forEach.call(
            form.querySelectorAll(
                "input[type='text'], input[type='email'], input[type='tel']"
            ),
            function(input) {
                input.removeAttribute('disabled');
            }
        );
    }

    function disableInputs() {
        Array.prototype.forEach.call(
            form.querySelectorAll(
                "input[type='text'], input[type='email'], input[type='tel']"
            ),
            function(input) {
                input.setAttribute('disabled', 'true');
            }
        );
    }

    // Listen for errors from each Element, and show error messages in the UI.
    var savedErrors = {};
    elements.forEach(function(element, idx) {
        element.on('change', function(event) {
            if (event.error) {
                error.classList.add('visible');
                savedErrors[idx] = event.error.message;
                errorMessage.innerText = event.error.message;
            } else {
                savedErrors[idx] = null;

                // Loop over the saved errors and find the first one, if any.
                var nextError = Object.keys(savedErrors)
                    .sort()
                    .reduce(function(maybeFoundError, key) {
                        return maybeFoundError || savedErrors[key];
                    }, null);

                if (nextError) {
                    // Now that they've fixed the current error, show another one.
                    errorMessage.innerText = nextError;
                } else {
                    // The user fixed the last error; no more errors.
                    error.classList.remove('visible');
                }
            }
        });
    });

    // Listen on the form's 'submit' handler...
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        var cuenta_nombre = $("#txt_cuenta_nombre");
        var cuenta_apellido = $("#txt_cuenta_apellido");
        var cuenta_telefono = $("#txt_cuenta_telefono");
        var cuenta_email = $("#txt_cuenta_email");
        var cuenta_password = $("#txt_cuenta_password");
        var cuenta_pais = $("#txt_cuenta_pais");

        if(cuenta_nombre.val() == '') {
            var position = cuenta_nombre.offset();
            swal({
                title: 'Error',
                text: 'Debes Escribir Tu Nombre para Poder Crear tu Cuenta',
                type: "error",
                confirmButtonText: "Ok"
            }, function(){
                $('#payform_popup').animate({ scrollTop: position.top }, 'slow');
            });
        }

        if(cuenta_apellido.val() == '') {
            var position = cuenta_apellido.offset();
            swal({
                title: 'Error',
                text: 'Debes Escribir Tu Apellido Completo para Poder Crear tu Cuenta',
                type: "error",
                confirmButtonText: "Ok"
            }, function(){
                $('#payform_popup').animate({ scrollTop: position.top }, 'slow');
            });
        }

        if(cuenta_telefono.val() == '') {
            var position = cuenta_telefono.offset();
            swal({
                title: 'Error',
                text: 'Debes Escribir Tu Número de Teléfono para Poder Crear tu Cuenta',
                type: "error",
                confirmButtonText: "Ok"
            }, function(){
                $('#payform_popup').animate({ scrollTop: position.top }, 'slow');
            });
        }

        if(cuenta_email.val() == '') {
            var position = cuenta_email.offset();
            swal({
                title: 'Error',
                text: 'Debes Escribir Tu Correo Electrónico para Poder Crear tu Cuenta',
                type: "error",
                confirmButtonText: "Ok"
            }, function(){
                $('#payform_popup').animate({ scrollTop: position.top }, 'slow');
            });
        }

        if(cuenta_password.val() == '') {
            var position = cuenta_password.offset();
            swal({
                title: 'Error',
                text: 'Debes Escribir Tu Password para Poder Crear tu Cuenta',
                type: "error",
                confirmButtonText: "Ok"
            }, function(){
                $('#payform_popup').animate({ scrollTop: position.top }, 'slow');
            });
        }

        if(cuenta_pais.val() == '') {
            var position = cuenta_pais.offset();
            swal({
                title: 'Error',
                text: 'Debes Escribir Tu Nombre para Poder Crear tu Cuenta',
                type: "error",
                confirmButtonText: "Ok"
            }, function(){
                $('#payform_popup').animate({ scrollTop: position.top }, 'slow');
            });
        }

        // Show a loading screen...
        payform_nayari.classList.add('submitting');

        // Disable all inputs.
        disableInputs();

        // Gather additional customer data we may have collected in our form.
        var name = form.querySelector('#' + payform_nayariName + '-name');
        var address1 = form.querySelector('#' + payform_nayariName + '-address');
        var city = form.querySelector('#' + payform_nayariName + '-city');
        var state = form.querySelector('#' + payform_nayariName + '-state');
        var zip = form.querySelector('#' + payform_nayariName + '-zip');
        var additionalData = {
            name: name ? name.value : undefined,
            address_line1: address1 ? address1.value : undefined,
            address_city: city ? city.value : undefined,
            address_state: state ? state.value : undefined,
            address_zip: zip ? zip.value : undefined,
        };

        // Use Stripe.js to create a token. We only need to pass in one Element
        // from the Element group in order to create a token. We can also pass
        // in the additional customer data we collected in our form.
        
        stripe.createToken(elements[0], additionalData).then(function(result) {
            if (result.token) {
                // If we received a token, show the token ID.
                //payform_nayari.querySelector('.token').innerText = result.token.id;
                //payform_nayari.classList.add('submitted');
                
                var dataform = $('#form_cuentapersonal').serializeArray();
                dataform.push({name: 'token', value: result.token.id});

                $.ajax({
                    type: "POST",
                    data: dataform,
                    url: "/facturacionv8/checkout/procesar_chechout",
                    success: function(data) {

                        if (data.respuesta == 'ok') {
                            swal({
                                title: data.titulo,
                                text: data.mensaje,
                                type: "success",
                                confirmButtonText: "Ok"
                            }, function(){
                                // Stop loading!
                                payform_nayari.classList.remove('submitting');
                                console.log('si llega aquí!');
                                payform_nayari.classList.add('submitted');
                            });

                        } else {
                            swal({
                                title: data.titulo,
                                text: data.mensaje,
                                type: "error",
                                confirmButtonText: "Ok"
                            }, function(){
                                // Stop loading!
                                payform_nayari.classList.remove('submitting');
                                
                                // Otherwise, un-disable inputs.
                                enableInputs();
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Stop loading!
                        payform_nayari.classList.remove('submitting');
                        
                        // Otherwise, un-disable inputs.
                        enableInputs();

                        swal({
                            title: "Problems!",
                            text: 'We have problems with the server, please try again ...',
                            type: "error",
                            confirmButtonText: "Ok"
                        });
                    },
                    dataType: "json"
                });

            } else {
                // Stop loading!
                payform_nayari.classList.remove('submitting');

                // Otherwise, un-disable inputs.
                enableInputs();
            }
        });
    });

    resetButton.addEventListener('click', function(e) {
        e.preventDefault();
        // Resetting the form (instead of setting the value to `''` for each input)
        // helps us clear webkit autofill styles.
        form.reset();

        // Clear each Element.
        elements.forEach(function(element) {
            element.clear();
        });

        // Reset error state as well.
        error.classList.remove('visible');

        // Resetting the form does not un-disable inputs, so we need to do it separately:
        enableInputs();
        payform_nayari.classList.remove('submitted');
    });
}