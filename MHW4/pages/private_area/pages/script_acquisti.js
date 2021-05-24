 function addArticolo(event){
    event.currentTarget.classList.add("green");
    document.querySelector('#add_section').classList.remove('hide');
 }
 
 function setupPage(){
     document.querySelector('#add').addEventListener('click',addArticolo);
 }

 setupPage();