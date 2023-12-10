 
 let contactId=0;
 let currentUrl = window.location.search;
//  console.log('Current URL:', currentUrl);
 let urlParams = new URLSearchParams(currentUrl);
 contactId = urlParams.get('contact_id');
//  console.log('contactId before', contactId);
 document.addEventListener("DOMContentLoaded", function() {
    
   

    loadPage().then(() => {
        pageEvents();
        // console.log('contactId after', contactId);
})



    result = document.querySelector('.content');
    let toggle = document.querySelector(".toggle");
    let navigation = document.querySelector(".navigation");
    let main = document.querySelector(".main");

    toggle.onclick = function () {
        navigation.classList.toggle("active");
        main.classList.toggle("active");
    };

    

});

function loadPage() {
    return fetch(`http://localhost/info2180-finalproject/full-contact/full-contact.php?contact_id=${contactId}`)
     .then(response => {
         if(response.ok) {
             return response.text();
         }
     })
     .then(data => {
        //  console.log("data being passed to innerHtml");
         result.innerHTML = data;
        //  console.log("function after html load");
 
     })
     .catch(error => {
         console.error("Error fetching data: " + error);
     });
 }

 

 function pageEvents() {
     document.querySelector('#notesbutton').addEventListener("click", function (event) {
        //  console.log("Note Event fired");
         event.preventDefault();
         postNote(contactId);
     });

      document.getElementById("assign").addEventListener("click", function () {
        //  console.log("Assign Event fired");
         currentUserID = this.getAttribute('current-contact-id')
         updateContactType('assignToMe', currentUserID);
     });
 
     document.getElementById("switch").addEventListener("click", function () {
         currentUserID = this.getAttribute('current-contact-id')
         updateContactType('switchType', currentUserID);
     });

    //  console.log('event listeners added');
 }

 function updateContactType(action, contactId) {

     fetch(`http://localhost/info2180-finalproject/full-contact/full-contact.php?contact_id=${contactId}&action=${action}`)
         .then(response => response.text())
         .then(data => {
             loadPage().then(() => {pageEvents();})
            //  console.log(data);
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

     fetch('http://localhost/info2180-finalproject/full-contact/full-contact.php', {
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
         loadPage().then(() => {pageEvents();})
        //  console.log('fetchResponse', responseData)
     })
     .catch(error => {
         console.error('Error posting note:', error);
     })
    
 }