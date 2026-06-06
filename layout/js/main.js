$(function () {
    'use strict';

    $('[placeholder]').focus(function () {

        $(this).attr('data-text', $(this).attr('placeholder'))

        $(this).attr('placeholder', '');
    }).blur(function () {
        $(this).attr('placeholder', $(this).attr('data-text'));
    })
})

let menuToggle = document.getElementById('menuToggle')
let links = document.getElementById('link-navber')

menuToggle.addEventListener('click', function(){
    links.classList.toggle('act')
})