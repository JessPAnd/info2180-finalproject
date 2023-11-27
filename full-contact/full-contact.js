window.onload = () => {

    contactId = 1;
    result = document.querySelector('.content');

    fetch(`http://localhost/info2180-project2/full-contact/full-contact.php?contact_id=${contactId}`)
    .then(response => {
        if(response.ok) {
            return response.text();
        }
    })
    .then(data => {
        result.innerHTML = data;
    })
    .catch(error => {
        console.error("Error fetching data: " + error);
    });

    function updateContactType(action, contactId) {
        fetch(`http://localhost/info2180-project2/full-contact/update-contact-type.php?contact_id=${contactId}&action=${action}`)
            .then(response => response.text())
            .then(data => {
                console.log(data);
                // You can handle the response as needed
            })
            .catch(error => {
                console.error("Error updating contact type: " + error);
            });
    }

    document.getElementById("assignToMeBtn").addEventListener("click", function () {
        updateContactType('assignToMe', contactId);
    });
    
    document.getElementById("switchToSalesLeadBtn").addEventListener("click", function () {
        updateContactType('switchToSalesLead', contactId);
    });


}