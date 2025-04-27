/*
Name: Natalia Guevara - gifts, Pournima Mhaskar - color drop down
Date: 04-26-2025
Class: COMPSCI 1XD3 
About: GiftSpark javascript and AJAX code for gift list feature on the add event page + code for the color drop down element
*/
window.addEventListener("load", function (event) {


    let submit_gift = document.getElementById("add_gift");
    let gift_list_node = document.getElementById("gift_list");
    let gift_text = document.getElementById("new_gifts");
    let gifts_to_pass = document.getElementById("gifts_pass");

    let gifts = [];

    submit_gift.addEventListener("click", add_gift);

    function render_gifts() {
        gift_list_node.innerHTML = "";

        // item, index -> dummy vars representing the current element in array, and index being that items index
        gifts.forEach((gift, index) => {
            let item = document.createElement("li");
            item.textContent = gift;

            let remove_button = document.createElement("button");
            remove_button.textContent = "Remove";
            remove_button.classList.add("remove_item");
            remove_button.classList.add("gift_button");

            remove_button.addEventListener("click", function (event) {
                remove_gift(index);
            });

            item.appendChild(remove_button); // add button to item
            gift_list_node.appendChild(item); // add item to list 
        });

        //console.log(gifts);

        gifts_to_pass.value = JSON.stringify(gifts);
    }

    function add_gift() {
        let new_gift = gift_text.value;

        if (new_gift === "" || gifts.length >= 5) return;

        gifts.push(new_gift);
        gift_text.value = "";

        render_gifts();
    }

    function remove_gift(index) {
        gifts.splice(index, 1); //at index, remove 1 item
        render_gifts();
    }


    //Color drop down select box code:
    let selectbox = document.getElementById("select_color");
    function changeColor(select) {
        selectbox.style['background-color'] = select.value;
    }
    selectbox.addEventListener("change", function(event){
        changeColor(selectbox);
    })

});