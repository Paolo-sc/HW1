var flag=0;

function navigation(event){
    const divs = document.querySelector("#tabs").querySelectorAll("div");
    if(event.currentTarget.id=="list"){
        for(element of divs){
            if(element.classList != '')
                element.classList= '';
            element.style.borderBottom = "2px solid rgb(244, 67, 54)";
        }
        event.currentTarget.classList.add("red");

        const items = document.querySelectorAll("#add_privato,#add_impresa,#remove");
        for(element of items)
            element.classList.add('hide');

        flag=1;

        if(document.querySelector("#selection").classList.contains('hide'))
            document.querySelector("#selection").classList.remove('hide');

        document.querySelector("#private").checked=false;
        document.querySelector("#not_private").checked=false;
    }
    if(event.currentTarget.id=="add"){
        for(element of divs){
            if(element.classList != '')
                element.classList= '';
            element.style.borderBottom = "2px solid rgb(4, 170, 109)";
        }
        event.currentTarget.classList.add("green");

        const tables = document.querySelectorAll("table");
        for(element of tables)
            element.classList.add('hide');
        
        document.querySelector("#remove").classList.add('hide');

        const items = document.querySelectorAll("#selection,#add_section");
        for(element of items)
            if(element.classList.contains('hide'))
                element.classList.remove('hide');

        flag=0;

        const forms = document.querySelectorAll("form");
        for(element of forms)
            element.reset();

        document.querySelector("#private").checked=false;
        document.querySelector("#not_private").checked=false;
    }
    if(event.currentTarget.id=="delete"){
        for(element of divs){
            if(element.classList != '')
                element.classList= '';
            element.style.borderBottom = "2px solid rgb(255, 87, 34)";
        }
        event.currentTarget.classList.add("orange");

        document.querySelector("table").classList.add('hide');

        const items = document.querySelectorAll("#selection,#add_privato,#add_impresa,table");
        for(element of items)
            element.classList.add('hide');

        const forms = document.querySelectorAll("form");
        for(element of forms)
            element.reset();

        if(document.querySelector("#remove").classList.contains('hide'))
            document.querySelector("#remove").classList.remove('hide');

        document.querySelector("#private").checked=false;
        document.querySelector("#not_private").checked=false;
    }
}

function setUpPage(){
    const divs = document.querySelector("#tabs").querySelectorAll("div");
    for(element of divs)
        element.addEventListener('click',navigation);
    
    document.querySelector("#private").onchange = clickPrivate;
    document.querySelector("#not_private").onchange = clickNotPrivate;
}


function clickPrivate(){
    if(document.querySelector("#not_private").checked)
        document.querySelector("#not_private").checked = false;

    if(!document.querySelector("#private").checked)
        document.querySelector("#private").checked=true;

    if(flag==0){
        document.querySelector("#add_impresa").classList.add('hide');
        document.querySelector("#add_privato").classList.remove('hide');
    }

    if(flag==1){
        fetch("fetch_private_table.php").then(fetchResponse).then(fetchPrivateTableJson);
        addPagerToTable(document.querySelector('#private_table'));
        if(document.querySelector("#private_table").classList.contains('hide'))
            document.querySelector("#private_table").classList.remove('hide');
        if(!document.querySelector("#private_table").classList.contains('hide'))
            document.querySelector("#not_private_table").classList.add('hide');
    }
}

function clickNotPrivate(){
    if(document.querySelector("#private").checked)
        document.querySelector("#private").checked = false;

    if(!document.querySelector("#not_private").checked)
        document.querySelector("#not_private").checked=true;

    if(flag==0){
        document.querySelector("#add_privato").classList.add('hide');
        document.querySelector("#add_impresa").classList.remove('hide');
    }

    if(flag==1){
        fetch("fetch_not_private_table.php").then(fetchResponse).then(fetchNotPrivateTableJson);
        addPagerToTable(document.querySelector('#not_private_table'));
        if(document.querySelector("#not_private_table").classList.contains('hide'))
            document.querySelector("#not_private_table").classList.remove('hide');
        if(!document.querySelector("#private_table").classList.contains('hide'))
            document.querySelector("#private_table").classList.add('hide');
    }
}

function addPagerToTable(table) {
    const rowsPerPage = 10;
    table.querySelector('tfoot').innerHTML='';

    let tBodyRows = table.querySelectorAll('tBody tr');
    let numPages = Math.ceil(tBodyRows.length/rowsPerPage);

    let colCount = 
    [].slice.call(
        table.querySelector('tr').cells
    )
    .reduce((a,b) => a + parseInt(b.colSpan), 0);

    table
    .querySelector('tfoot')
    .insertRow()
    .innerHTML = `<td colspan=${colCount}><div class="nav"></div></td>`;

    if(numPages == 1)
        return;

    for(i = 0;i < numPages;i++) {

        let pageNum = i + 1;

        table.querySelector('.nav')
        .insertAdjacentHTML(
            'beforeend',
            `<a href="#" rel="${i}">${pageNum}</a> `        
        );

    }

    changeToPage(table, 1, rowsPerPage);

    for (let navA of table.querySelectorAll('.nav a'))
        navA.addEventListener(
            'click', 
            e => changeToPage(
                table, 
                parseInt(e.target.innerHTML), 
                rowsPerPage
            )
        );
}

function changeToPage(table, page, rowsPerPage) {

    let startItem = (page - 1) * rowsPerPage;
    let endItem = startItem + rowsPerPage;
    let navAs = table.querySelectorAll('.nav a');
    let tBodyRows = table.querySelectorAll('tBody tr');

    for (let nix = 0; nix < navAs.length; nix++) {

        if (nix == page - 1)
            navAs[nix].classList.add('active');
        else 
            navAs[nix].classList.remove('active');

        for (let trix = 0; trix < tBodyRows.length; trix++)
            if(trix >= startItem && trix < endItem)
                tBodyRows[trix].classList.remove('hide');
            else
                tBodyRows[trix].classList.add('hide');

    }
}

function fetchResponse(response){
    console.log(response);
    console.log(response.json);
    return response.json();
}

function fetchPrivateTableJson(json){
    const tbody = document.querySelector('#private_table tbody');
    let tr;
    tbody.innerHTML='';
    for(element of json){
        tr = document.createElement('tr');
        tr.innerHTML = '<td>'+element.id+'</td>'+'<td>'+element.name+'</td>'+'<td>'+element.surname+'</td>'+'<td>'+element.cf+'</td>'+
        '<td>'+element.phone+'</td>'+'<td>'+element.address+'</td>'+'<td>'+element.username+'</td>'+'<td>'+element.email+'</td>';
        tbody.appendChild(tr);
    }
}

function fetchNotPrivateTableJson(json){
    const tbody = document.querySelector('#not_private_table tbody');
    let tr;
    tbody.innerHTML='';
    for(element of json){
        tr = document.createElement('tr');
        tr.innerHTML = '<td>'+element.id+'</td>'+'<td>'+element.rag_soc+'</td>'+'<td>'+element.phone+'</td>'+'<td>'+element.address+'</td>'+
        '<td>'+element.email+'</td>'+'<td>'+element.p_iva+'</td>';
        tbody.appendChild(tr);
    }
}

setUpPage();