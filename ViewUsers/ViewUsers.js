//Directs to Add User page

document.addEventListener('DOMContentLoaded', function() {
    var addUserButton = document.querySelector(".add-user-btn");
    if(addUserButton) {
        addUserButton.addEventListener("click", function() {
            window.location.href = "AddUser.html";
        });
    }
});
