function onResponse(response){
    return response.json();
}

function onData(json){
    const modale = document.querySelector('#modale');
    const section = document.createElement('section');

    let new_div;
    let new_p;

    for(element of json){
        new_div = document.createElement('div');
        new_p = document.createElement('p');
        new_p.textContent = "Una richiesta relativa all'intervento: " + element.intervento;
        new_div.appendChild(new_p);
        section.appendChild(new_div);
    }
    modale.appendChild(section);
    modale.classList.remove('hide');
}

function onDataImport(json){
    const modale = document.querySelector('#modale');
    const section = document.createElement('section');

    let new_div;
    let new_p;

    for(element of json){
        new_div = document.createElement('div');
        new_p = document.createElement('p');
        new_p.textContent="-Id articolo: " + element.id_articolo + " -Fornitore: " + element.fornitore;
        new_div.appendChild(new_p);
        section.appendChild(new_div);
    }
    modale.appendChild(section);
    modale.classList.remove('hide');
}

function submit2(event){
    event.preventDefault();
    fetch("fetch_request.php?id="+document.querySelector('#input_2').value).then(onResponse).then(onData);
}

function sameImport(event){
    fetch("same_import.php").then(onResponse).then(onDataImport);
}

function setupPage(){
    window.addEventListener('keydown',chiudiModale);
    document.querySelector('#submit_2').addEventListener('click',submit2);
    document.querySelector('button').addEventListener('click',sameImport);
    //document.querySelector('#submit_2').addEventListener('click',submit2);
}

function chiudiModale(event){
    if(event.key === 'Escape'){
        const modale = document.querySelector('#modale');
        if(!modale.classList.contains('hide')){
            modale.classList.add('hide');
            document.body.classList.remove('no-scroll');
            modale.innerHTML = '';
        }
    }
}
setupPage();