window.$ = window.jQuery = require('jquery');
window.Materialize = require('materialize-css');
require('./components/Drivers');

$(document).ready(function() {
    //menu lateral
    $('.sidenav').sidenav();
    $('.collapsible').collapsible();
    //alerta
    $(".dato").click();
    //modales
    $('.modal').modal();
    $(".dropdown-trigger").dropdown();
});
