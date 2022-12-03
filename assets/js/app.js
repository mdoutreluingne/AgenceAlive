/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';
const Translators = require('./translator');



window.addEventListener("load", (event) => {
    console.log(Translator.trans('placeholder', { "username" : "will" }));
    console.log(Translator.trans('home.buy'));
    console.log(Translator.locale);
    alert('test traduction : ' + Translator.trans('home.buy'))
});


$("#contactButton").click(e => {
    e.preventDefault();
    $('#contactForm').slideDown();
    $("#contactButton").slideUp();
})
// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

