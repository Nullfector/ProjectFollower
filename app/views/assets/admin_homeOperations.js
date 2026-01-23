const from = document.getElementById("f1");
const res = document.getElementById("response");
const div = document.getElementById("divall");
const b = document.getElementById("but1");
const b2 = document.getElementById("but2");

const b3 = document.getElementById("but3");
const b4 = document.getElementById("but4");

const sel1 = document.getElementById("sel1"); //zadanie
const sel2 = document.getElementById("sel2"); //projekt

const sel3 = document.getElementById("sel3"); //projekt
const sel4 = document.getElementById("sel4"); //zadanie

const sel5 = document.getElementById("sel5"); //projekt
const sel6 = document.getElementById("sel6"); //zespół
const sel7 = document.getElementById("sel7"); //next projekt

async function get_tasks_info(){
    const id = sel5.value;//id projektu
    try{
        const res1 = await fetch(`/../app/db_kontrolers/main_admin.php?id=${encodeURIComponent(id)}&action=tasks`,{method: 'GET'});
        const html = await res1.text();

        const res2 = await fetch(`/../app/db_kontrolers/main_admin.php?id=${encodeURIComponent(id)}&action=tasks_conn`,{method: 'GET'});
        const html2 = await res2.text();

        div.innerHTML = html +'\n'+html2; //to jest ???????
        
    } catch (e) {
            //tu trzeba się pobawić
            console.log(e);
            div.innerHTML = `Coś jest nie tak po stronie serwera`;
        }
}

async function get_required(){
    const id = sel3.value;//id projektu

    try{
        const res1 = await fetch(`/../app/db_kontrolers/main_admin.php?action=zadanie_1&id=${encodeURIComponent(id)}`,{method: 'GET'});
 
        const data1 = JSON.parse(await res1.text());
        if (!data1.ok) {
            sel1.innerHTML = `<option value="0">${data1.error}</option>`;
            return;
        } else {
            sel1.innerHTML = `<option value="0">Wybierz zadanie</option>`;
        }

        for (const v of data1.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_za;
            opt.textContent = v.nazwa_zadania;
            sel1.appendChild(opt);
            
        }
    } catch (e) {
            //tu trzeba się pobawić
            console.log(e);
            sel1.innerHTML = `<option value="0">Błąd połączenia</option>`;
        }
}

async function get_required2(){
    const id = sel3.value;//id projektu

    try{
        const res1 = await fetch(`/../app/db_kontrolers/main_admin.php?action=zadanie_2&id=${encodeURIComponent(id)}`,{method: 'GET'});
 
        const data1 = JSON.parse(await res1.text());
        if (!data1.ok) {
            sel4.innerHTML = `<option value="0">${data1.error}</option>`;
            return;
        } else {
            sel4.innerHTML = `<option value="0">Wybierz zadanie</option>`;
        }

        for (const v of data1.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_za;
            opt.textContent = v.nazwa_zadania;
            sel4.appendChild(opt);
            
        }
    } catch (e) {
            //tu trzeba się pobawić
            console.log(e);
            sel4.innerHTML = `<option value="0">Błąd połączenia</option>`;
        }
}

sel3.addEventListener('change', async (e) =>{
    if(e.target.value == "0"){
        sel4.innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
        sel1.innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
    } else {
        get_required();
        get_required2();
    }
});

sel5.addEventListener('change', async (e) =>{
    if(e.target.value == "0"){
        div.innerHTML='';
    } else {
        get_tasks_info();
        sel6.value="0";
        sel7.value="0";
    }
});
//----------------------------------------------------------------------------------------------------------
async function get_team_info(){
    const id = sel6.value;//id zespołu
    try{
        const res1 = await fetch(`/../app/db_kontrolers/main_admin.php?id=${encodeURIComponent(id)}&action=team`,{method: 'GET'});
        const html = await res1.text();

        const res2 = await fetch(`/../app/db_kontrolers/main_admin.php?id=${encodeURIComponent(id)}&action=team_conn`,{method: 'GET'});
        const html2 = await res2.text();

        const res3 = await fetch(`/../app/db_kontrolers/main_admin.php?id=${encodeURIComponent(id)}&action=team_conn2`,{method: 'GET'});
        const html3 = await res3.text();

        div.innerHTML = html +'\n'+html2+'\n'+html3;
        
    } catch (e) {
            //tu trzeba się pobawić
            console.log(e);
            div.innerHTML = `Coś jest nie tak po stronie serwera`;
        }
}

sel6.addEventListener('change', async (e) =>{
    if(e.target.value == "0"){
        div.innerHTML='';
    } else {
        get_team_info();
        sel5.value="0";
        sel7.value="0";
    }
});

sel7.addEventListener('change', async (e) =>{
    if(e.target.value == "0"){
        div.innerHTML='';
    } else {
        const id = sel7.value;//id projektu
        try{
        const res1 = await fetch(`/../app/db_kontrolers/main_admin.php?id=${encodeURIComponent(id)}&action=pr`,{method: 'GET'});
        const html = await res1.text();

        div.innerHTML = html;
        
    } catch (e) {
            //tu trzeba się pobawić
            console.log(e);
            div.innerHTML = `Coś jest nie tak po stronie serwera`;
        }
        sel6.value="0";
        sel5.value="0";
    }
});
//------------------------------------------------------------------------------------------------------------
b.addEventListener('click', async () =>{
    if(sel2.value == "0"){
        document.getElementById('response').textContent="No nie";
        return;
    }

    try{ // 0-aktywuj, 1-zamknij
        const resp = await fetch(`/../app/db_kontrolers/main_admin.php`,{method: 'PUT', body: JSON.stringify({'id': sel2.value, 'val': 0, 'action': 'edit_proj'})});
        const data = await resp.json();

        if(!data.ok){
            res.textContent = `Nie można zaaktywować projektu!`;
            res.style.color = 'red';
        } else {
            res.textContent = `Pomyślnie aktywowano projekt!`;
            res.style.color = 'green';
            setTimeout(() => {res.textContent = "";}, 3000);
        }
    
    }catch(err){
        console.log(err);
        res.textContent = 'Wystąpił błąd po stronie serwera!';
        res.style.color = 'red';
    }
    
});

b2.addEventListener('click', async () =>{
    if(sel1.value == "0"){
        document.getElementById('response').textContent="No błagam";
        return;
    }

    try{
        const resp = await fetch(`/../app/db_kontrolers/main_admin.php`,{method: 'PUT', body: JSON.stringify({'id': sel1.value, 'val': 0, 'action': 'edit_zad'})});
        const data = await resp.json();

        if(!data.ok){
            res.textContent = `Nie można zaaktywować zadania!`;
            res.style.color = 'red';
        } else {
            res.textContent = `Pomyślnie aktywowano zadanie!`;
            res.style.color = 'green';
            setTimeout(() => {res.textContent = "";}, 3000);
        }


    }catch(err){
        console.log(err);
        res.textContent = 'Wystąpił błąd po stronie serwera!';
        res.style.color = 'red';
    }
    
    
});

b3.addEventListener('click', async () =>{
    if(sel3.value == "0"){
        document.getElementById('response').textContent="No nie";
        return;
    }

    try{
        const resp = await fetch(`/../app/db_kontrolers/main_admin.php`,{method: 'PUT', body: JSON.stringify({'id': sel3.value, 'val': 1, 'action': 'projekt_edit'})});
        const data = await resp.json();

        if(!data.ok){
            res.textContent = `Nie można zakończyć projektu - istnieją jeszcze nie zakończone zadania!`;
            res.style.color = 'red';
        } else {
            res.textContent = `Pomyślnie zakończono projekt!`;
            res.style.color = 'green';
            setTimeout(() => {res.textContent = "";}, 3000);
        }
    
    }catch(err){
        console.log(err);
        res.textContent = 'Wystąpił błąd po stronie serwera!';
        res.style.color = 'red';
    }
    
    
});

b4.addEventListener('click', async () =>{
    if(sel4.value == "0"){
        document.getElementById('response').textContent="No błagam";
        return;
    }
    
    try{
        const resp = await fetch(`/../app/db_kontrolers/main_admin.php`,{method: 'PUT', body: JSON.stringify({'id': sel4.value, 'val': 1, 'action': 'edit_zad'})});
        const data = await resp.json();

        if(!data.ok){
            res.textContent = `Nie można zakończyć zadania!`;
            res.style.color = 'red';
        } else {
            res.textContent = `Pomyślnie zakończono zadanie!`;
            res.style.color = 'green';
            setTimeout(() => {res.textContent = "";}, 3000);
        }


    }catch(err){
        console.log(err);
        res.textContent = 'Wystąpił błąd po stronie serwera!';
        res.style.color = 'red';
    }
    
});

document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById("admin_home");
    if (!root) return;
    setupAdmin();
});

async function setupAdmin(){

    sel1.innerHTML="<option value='0'>Ładowanie...</option>";
    sel2.innerHTML="<option value='0'>Ładowanie...</option>";

    try{
        const res2 = await fetch("/../app/db_kontrolers/main_admin.php?action=projekt_1",{method: 'GET'});
        const res3 = await fetch("/../app/db_kontrolers/main_admin.php?action=projekt_2",{method: 'GET'});
        const res4 = await fetch("/../app/db_kontrolers/main_admin.php?action=projekt_fin&opt=0",{method: 'GET'});
        const res5 = await fetch("/../app/db_kontrolers/main_admin.php?action=zespoly&opt=0",{method: 'GET'});

        const data2 = JSON.parse(await res2.text());
        const data3 = JSON.parse(await res3.text());
        const data4 = JSON.parse(await res4.text());
        const data5 = JSON.parse(await res5.text());

        if (!data2.ok) {
            sel2.innerHTML = `<option value="0">${data2.error}</option>`;
        }
        if (!data3.ok) {
            sel3.innerHTML = `<option value="0">${data3.error}</option>`;
        }
        if (!data4.ok) {
            sel5.innerHTML = `<option value="0">${data4.error}</option>`;
            sel7.innerHTML = `<option value="0">${data4.error}</option>`;
        }
        if (!data5.ok) {
            sel6.innerHTML = `<option value="0">${data5.error}</option>`;
        } else {
            sel2.innerHTML = `<option value="0">Wybierz projekt</option>`;
            sel3.innerHTML = `<option value="0">Wybierz projekt</option>`;
            sel5.innerHTML = `<option value="0">Wybierz projekt</option>`;
            sel6.innerHTML = `<option value="0">Wybierz zespół</option>`;
            sel7.innerHTML = `<option value="0">Wybierz projekt</option>`;
        }

        for (const v of data2.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_p;
            opt.textContent = v.nazwa_projektu;
            sel2.appendChild(opt);
        }

        for (const v of data3.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_p;
            opt.textContent = v.nazwa_projektu;
            sel3.appendChild(opt);
        }

        for (const v of data4.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_p;
            opt.textContent = v.nazwa_projektu;
            sel5.appendChild(opt);
            sel7.appendChild(opt.cloneNode(true));
        }

        for (const v of data5.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_ze;
            opt.textContent = v.nazwa;
            sel6.appendChild(opt);
        }

        sel1.innerHTML="<option value='0'>Chwilowo niedostępne</option>";

        sel4.innerHTML="<option value='0'>Chwilowo niedostępne</option>";

    }catch(e){
        console.log(e);
        sel2.innerHTML="<option value='0'>Brak połączenia</option>";
    }
}

document.getElementById("sel5_1").addEventListener('change', async (e) => {
    const res4 = await fetch(`/../app/db_kontrolers/main_admin.php?action=projekt_fin&opt=${encodeURIComponent(e.target.value)}`,{method: 'GET'});
    const data = await res4.json();

    if(!data.ok){
        sel5.innerHTML = `<option value="0">${data.error}</option>`;
        return;
    }
    sel5.innerHTML = `<option value="0">Wybierz projekt</option>`;

    for (const v of data.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_p;
            opt.textContent = v.nazwa_projektu;
            sel5.appendChild(opt);
    }
});

document.getElementById("sel7_1").addEventListener('change', async (e) => {
    const res4 = await fetch(`/../app/db_kontrolers/main_admin.php?action=projekt_fin&opt=${encodeURIComponent(e.target.value)}`,{method: 'GET'});
    const data = await res4.json();

    if(!data.ok){
        sel7.innerHTML = `<option value="0">${data.error}</option>`;
        return;
    }
    sel7.innerHTML = `<option value="0">Wybierz projekt</option>`;

    for (const v of data.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_p;
            opt.textContent = v.nazwa_projektu;
            sel7.appendChild(opt);
    }
});

document.getElementById("sel6_1").addEventListener('change', async (e) => {
    const res4 = await fetch(`/../app/db_kontrolers/main_admin.php?action=zespoly&opt=${encodeURIComponent(e.target.value)}`,{method: 'GET'});
    //console.log(res4.text());
    const data = await res4.json();

    if(!data.ok){
        sel6.innerHTML = `<option value="0">${data.error}</option>`;
        return;
    }
    sel6.innerHTML = `<option value="0">Wybierz zespół</option>`;

    for (const v of data.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_ze;
            opt.textContent = v.nazwa;
            sel6.appendChild(opt);
    }
});