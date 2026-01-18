const from = document.getElementById("f1");
const res = document.getElementById("response");
const b = document.getElementById("but1");
const b2 = document.getElementById("but2");

const b3 = document.getElementById("but3");
const b4 = document.getElementById("but4");

const sel1 = document.getElementById("sel1"); //zadanie
const sel2 = document.getElementById("sel2"); //projekt

const sel3 = document.getElementById("sel3"); //projekt
const sel4 = document.getElementById("sel4"); //zadanie

async function get_required(){
    const id = sel2.value;//id projektu

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

sel2.addEventListener('change', async (e) =>{
    if(e.target.value == "0"){
        sel1.innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
    } else {
        get_required();
    }
});

sel3.addEventListener('change', async (e) =>{
    if(e.target.value == "0"){
        sel4.innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
    } else {
        get_required2();
    }
});

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
        const resp = await fetch(`/../app/db_kontrolers/main_admin.php`,{method: 'PUT', body: {'id': sel3.value, 'val': 1, 'action': 'projekt_edit'}});
        const data = await resp.json();

        if(!data.ok){
            res.textContent = `Nie można zakończyć projektu!`;
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
  setup();
});

async function setup(){

    sel1.innerHTML="<option value='0'>Ładowanie...</option>";
    sel2.innerHTML="<option value='0'>Ładowanie...</option>";

    try{
        const res2 = await fetch("/../app/db_kontrolers/main_admin.php?action=projekt_1",{method: 'GET'});
        const res3 = await fetch("/../app/db_kontrolers/main_admin.php?action=projekt_2",{method: 'GET'});

        const data2 = JSON.parse(await res2.text());
        const data3 = JSON.parse(await res3.text());

        if (!data2.ok) {
            sel2.innerHTML = `<option value="0">${data2.error}</option>`;
        }
        if (!data3.ok) {
            sel3.innerHTML = `<option value="0">${data3.error}</option>`;
        } else {
            sel2.innerHTML = `<option value="0">Wybierz projekt</option>`;
            sel3.innerHTML = `<option value="0">Wybierz projekt</option>`;
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

        sel1.innerHTML="<option value='0'>Chwilowo niedostępne</option>";

        sel4.innerHTML="<option value='0'>Chwilowo niedostępne</option>";

    }catch(e){
        console.log(e);
        sel2.innerHTML="<option value='0'>Brak połączenia</option>";
    }
}