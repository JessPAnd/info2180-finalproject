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

        document.querySelector('#notesbutton').addEventListener("click", function (event) {
            console.log("Event fired");
            event.preventDefault();
            postNote(contactId);
            
        });

         // document.getElementById("assignToMeBtn").addEventListener("click", function () {
        //     updateContactType('assignToMe', contactId);
        // });
    
        // document.getElementById("switchToSalesLeadBtn").addEventListener("click", function () {
        //     updateContactType('switchToSalesLead', contactId);
        // });

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

   
    function postNote(contactId) {

        noteText = document.getElementById('notepad').value;
        //for input checking purposes (values are present)
        // const data = {
        //     contact_id: contactId,
        //     note_text: noteText
        // };
        
        // console.log('postNote Data', data);

        fetch('http://localhost/info2180-project2/full-contact/full-contact.php', {
            method: 'POST',
            mode: "cors",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                contact_id: contactId,
                note_text: noteText
            })
        })
        .then(response => response.text())
        .then(responseData => {
            // noteText = data;

            // error messages are received through here so the function is sending and receiving.
            console.log('fetchResponse', responseData)
        })
        .catch(error => {
            console.error('Error posting note:', error);
        })
       
    }

};