window.onload = function () {
    var alpha = /^[A-Za-z]+$/;
    var numbers = /^[0-9]+$/;
    var emailcheck = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

    var save = document.querySelector("#button");

    if (save) {
        save.addEventListener("click", process);
    } else {
        console.error("Button not found");
    }

    function process(e) {
        var valid = 0;
        e.preventDefault();
        var title = document.querySelector("#title").value;
        var fname = document.querySelector("#fname").value.trim();
        var lname = document.querySelector("#lname").value.trim();
        var email = document.querySelector('#email').value.trim();
        var phone = document.querySelector('#phone').value.trim();
        var company = document.querySelector('#company').value.trim();
        var type = document.querySelector("#type").value;
        var assignedto = document.querySelector("#assignedto").value;

        console.log(title, fname, lname, email, phone, company, type, assignedto);

        if (!fname.match(alpha)) {
            document.querySelector("#fname").style.borderColor = "red";
            return;
        } else {
            console.log('First name worked');
            valid = valid + 1;
            document.querySelector("#fname").style.borderColor = "black";
        }

        if (!lname.match(alpha)) {
            document.querySelector("#lname").style.borderColor = "red";
            console.log('Last name validation failed');
            return;
        } else {
            console.log('Last name worked');
            valid = valid + 1;
            document.querySelector("#lname").style.borderColor = "black";
        }

        if (!email.match(emailcheck)) {
            document.querySelector("#email").style.borderColor = "red";
            console.log('Email validation failed');
            return;
        } else {
            console.log('Email worked');
            valid = valid + 1;
            document.querySelector("#email").style.borderColor = "black";
        }

        if (!phone.match(numbers)) {
            document.querySelector("#phonenum").style.borderColor = "red";
            console.log('Phone number validation failed');
            return;
        } else {
            console.log('Phone number worked');
            valid = valid + 1;
            document.querySelector("#phone").style.borderColor = "black";
        }

        if (!company.match(alpha)) {
            document.querySelector("#company").style.borderColor = "red";
            console.log('Company validation failed');
            return;
        } else {
            console.log('Company worked');
            valid = valid + 1;
            document.querySelector("#company").style.borderColor = "black";
        }

        // Constructing the JSON payload (POST method)
        let payload = {
            fname: fname,
            lname: lname,
            email: email,
            company: company,
            phone: phone,
            type: type,
            assignedto: assignedto,
            title: title
        };

        if (valid === 5) {
            const xhr = new XMLHttpRequest();
        
            xhr.onreadystatechange = function () {
                if (this.readyState == 4) {
                    if (this.status == 200) {
                        // Display the response from the server (success or error)
                        document.getElementById("show").innerHTML = this.responseText;
                        console.log('Request successful');
        
                        
                        document.querySelector("#title").value = "";
                        document.querySelector("#fname").value = "";
                        document.querySelector("#lname").value = "";
                        document.querySelector("#email").value = "";
                        document.querySelector("#phone").value = "";
                        document.querySelector("#company").value = "";
                        document.querySelector("#type").value = "Sales Lead";
                        document.querySelector("#assignedto").value = "None"; 
        
                    } else {
                        document.getElementById("show").innerHTML = "There was a problem with the request";
                        console.error('Request failed with status:', this.status);
        
                        // Optionally, log the response text for debugging
                        console.error('Response text:', this.responseText);
                    }
                }
            }
        
            let url = 'new_contact.php';
            let params = 'fname=' + encodeURIComponent(fname) +
                         '&lname=' + encodeURIComponent(lname) +
                         '&email=' + encodeURIComponent(email) +
                         '&company=' + encodeURIComponent(company) +
                         '&phone=' + encodeURIComponent(phone) +
                         '&type=' + encodeURIComponent(type) +
                         '&assignedto=' + encodeURIComponent(assignedto) +
                         '&title=' + encodeURIComponent(title);
        
            xhr.open('POST', url, true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send(params);
        }
    }
}
