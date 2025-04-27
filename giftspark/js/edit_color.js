/*
Name: Pournima Mhaskar
Date: 04-26-2025
Class: COMPSCI 1XD3 
About: GiftSpark javascript code for the color drop down element on the edit event page
*/
window.addEventListener("load", function(event) {
    //Color drop down select box code:
    let selectbox = document.getElementById("select_color");
    function changeColor(select) {
        selectbox.style['background-color'] = select.value;
    }
    selectbox.addEventListener("change", function(event){
        changeColor(selectbox);
    })
})