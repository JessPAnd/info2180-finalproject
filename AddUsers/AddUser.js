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
        xmlhttp.onreadystatechange = function() 
        {
            if(this.readyState == 4 && this.status == 200) 
            {
                console.log("User added");
            }
        };
        xmlhttp.send('fname=' + encodeURIComponent(fname) + "&lname=" + encodeURIComponent(lname) + "&password=" + encodeURIComponent(password) + "&email=" + encodeURIComponent(email));
    } 
    else 
    {
        console.log("Failed to add user");
    }
});
