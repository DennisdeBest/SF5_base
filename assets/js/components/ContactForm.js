'use strict';

import M from 'materialize-css/dist/js/materialize.js'

export default class ContactForm {

    constructor() {
        this.registerActions();
    }

    registerActions() {
        $('#message-form').on('submit', (e) => {
            this.sendMessage(e);
        })
    }

    sendMessage(e) {
        let $form = $('#message-form');
        let $submitButton = $form.find('button:submit');
        e.preventDefault();
        $submitButton.attr("disabled", "disabled");

        //Set all errors back to zero
        const inputFields = $("input[name^='message[']");
        $.each(inputFields, (index, field) => {
            $(field).removeClass('invalid');
            const errorTextField = $(field).parent().find('span.helper-text');
            errorTextField.data('error', "");
            errorTextField.html("");
        });


        $.ajax({
            url: Routing.generate('send_message'),
            type: 'POST',
            dataType: 'json',
            data: $form.serialize(),
            success: (response) => {
                M.toast({html: response.message});
            },
            error: (xhr, status, error) => {
                const errors = xhr.responseJSON.errors;
                $.each(errors, (fieldName, error) => {
                    const fieldSelector = "input[name='message["+fieldName+"]']";
                    const field = $(fieldSelector);
                    field.addClass('invalid');
                    const errorTextField = field.parent().find('span.helper-text');
                    errorTextField.data('error', error[0]);
                    let errorMessages = "<ul>";
                    $.each(error, (index, error) => {
                        errorMessages += "<li>" + error + "</li>";
                    });
                    errorMessages += "</ul>";
                    errorTextField.html(errorMessages);
                });
            },
            complete: () => {
                setTimeout(() => {
                    $submitButton.removeAttr("disabled");
                }, 2000)
            },
        });
    }
}
