<!DOCTYPE html>
<!--
    GIFTSPARK (mock) Bday Recip. Form
-->

<?php

$gifts = [];



?>
<html>

<head>
    <meta charset="utf-8" />
    <title>Add</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.png" />
    <meta name="viewport" content="width=device-width" />

    <!--<link rel="stylesheet" href="css/cal.css" />-->

    <script>
        window.addEventListener("load", function(event) {

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

                    remove_button.addEventListener("click", function(event) {
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
                    headers: { "Content-Type": "application/x-www-form-urlencoded"},
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
    </script>
</head>

<body>
    <form id="form" method="POST" action="success_add.php">
        <div>
            <label for="new_name">Name of Birthday Person</label>
        </div>
        <div>
            <input type="text" name="new_name" id="new_name" required />
        </div>

        <br />

        <div>
            <label for="new_bday">Birthday Date</label>
        </div>
        <div>
            <input type="date" name="new_bday" id="new_bday" required />
        </div>

        <br />

        <div>
            <label for="new_email">Their Email</label>
        </div>
        <div>
            <input type="text" name="new_email" id="new_email" required />
            <span id="email_feedback"></span>
        </div>

        <br />

        <div>
            <label for="new_gifts">Gift Ideas</label>
        </div>
        <div>
            <input type="text" id="new_gifts" />
            <input type="button" id="add_gift" value="Add Gift!" />
        </div>
        <div>
            <ul id="gift_list"></ul>
        </div>

        <input type="hidden" name="gifts" id="gifts_pass" />

        <br />

        <input type="submit" id="submit" />
    </form>
</body>

</html>