'use strict';

import $ from 'jquery'
import ContactForm from './components/ContactForm';

import '../scss/main.scss'
import M from 'materialize-css/dist/js/materialize.js'
import './plugins/fontawesome'

$(document).ready(function () {
    new ContactForm();
    M.updateTextFields();
});
