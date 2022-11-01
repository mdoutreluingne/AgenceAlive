/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';
import Translator from 'bazinga-translator';
Translator.add("foo", "Baaar", "messages", "fr");
console.log(Translator.trans('foo'));

console.log(Translator.transChoice('apples', 0, {"count" : 0}));

$("#contactButton").click(e => {
    e.preventDefault();
    $('#contactForm').slideDown();
    $("#contactButton").slideUp();
})
// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

