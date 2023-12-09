document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('form').addEventListener('submit', function(event) {
        event.preventDefault();

        var fname = document.getElementById('fname').value;
        var lname = document.getElementById('lname').value;
        var email = document.getElementById('email').value;
        var password = document.getElementById('password').value;
        
        if(fname && lname && email && password)
        {
            var url = "AddUser.php";
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.open("POST",url);
            xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xmlhttp.onreadystatechange = function() 
            {
                if(this.readyState == 4 && this.status == 200) 
                {
                    console.log("User added");
                }
            };
            let data = new URLSearchParams();
            data.append('fname', fname);
            data.append('lname', lname);
            data.append('password', password);
            data.append('email', email);

            xmlhttp.send(data);
        } 
        else 
        {
            console.log("Failed to add user");
        }
    });
});
