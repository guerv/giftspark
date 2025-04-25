window.addEventListener("load", function (event) {

    check_email_exists();

    let submit_gift = document.getElementById("add_gift");
    let gift_list_node = document.getElementById("gift_list");
    let gift_text = document.getElementById("new_gifts");
    let gifts_to_pass = document.getElementById("gifts_pass");

    // checking email
    let email_input = document.getElementById("new_email");
    let email_feedback = document.getElementById("email_feedback");
    let submit_btn = document.getElementById("submit");


    let gifts = [];


    submit_gift.addEventListener("click", add_gift);
    email_input.addEventListener("input", check_email_exists);

    function render_gifts() {
        gift_list_node.innerHTML = "";

        // item, index -> dummy vars representing the current element in array, and index being that items index
        gifts.forEach((gift, index) => {
            let item = document.createElement("li");
            item.textContent = gift;

            let remove_button = document.createElement("button");
            remove_button.textContent = "Remove";
            remove_button.classList.add("remove_item");

            remove_button.addEventListener("click", function (event) {
                remove_gift(index);
            });

            item.appendChild(remove_button); // add button to item
            gift_list_node.appendChild(item); // add item to list 
        });

        console.log(gifts);

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

    function check_email_exists() {
        let email = email_input.value;

        let params = "email=" + encodeURIComponent(email);
        let config = {
            method: 'POST',
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: params
        };
        fetch("check_recip_email.php", config)
            .then(response => response.json()) // for associative array
            .then(data => {
                if (data.exists) {
                    email_feedback.innerHTML = "Email already registered.";
                    submit_btn.disabled = true;
                } else {
                    email_feedback.innerHTML = "";
                    submit_btn.disabled = false;
                }
            })
    }

});