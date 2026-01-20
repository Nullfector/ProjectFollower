const form2 = document.getElementById("form_edit_team");
const kom2 = document.getElementById("form_edit_team_kom");

function TeamEditCheck(){
    var res = "Niepoprawny format danych w nie-pustych polach: ";
    kom2.style.color = "red";
    var count = 0;

    if(ev1.selectedIndex == 0){ //id
        kom2.textContent = "No, nie zedytujesz obiektu jak nie wyspecyfikujesz o jaki ci chodzi";
        return false;
    }

    if(form2.pole9_1.value != "" && !(/^[a-zA-Z0-9_& \-]+\s*$/.test(form2.pole9_1.value))){ //nazwa teamu
        res+="nazwa";
        count++;
        form2.pole9_1.value = "";
    }
   if(form2.pole9_1.value == "" && form2.pole9_8.value=='0'){
        kom2.textContent = "I po co w ten przycisk klikasz, co?";
        return false;
   }

    if(count!=0) {kom2.textContent = res+="!"; return false;}
    return true;
}

document.addEventListener('DOMContentLoaded', () => {
  setup();
});

async function setup()
{
    const zesp = document.getElementById("pole9_0");
    const proj1 = document.getElementById("pole9_7");
    const sel = document.getElementById("sel1");

    zesp.innerHTML="<option value='0'>Ładowanie...</option>";
    proj1.innerHTMl = "<option value='0'>Ładowanie...</option>";
    sel.innerHTMl = "<option value='0'>Ładowanie...</option>";

    try{
        const res2 = await fetch(`/../app/db_kontrolers/user_control.php?action=team`,{method: 'GET'});
    const data2 = JSON.parse(await res2.text());

    const res1 = await fetch(`/../app/db_kontrolers/user_control.php?action=zads`,{method: 'GET'});
    const data1 = JSON.parse(await res1.text());

    const res3 = await fetch(`/../app/db_kontrolers/main_admin.php?action=projekt_2`,{method: 'GET'});
    const data3 = JSON.parse(await res3.text());

    if (!data2.ok) {
            zesp.innerHTML = `<option value="0">${data2.error}</option>`;
    }
    if (!data1.ok) {
            proj1.innerHTML = `<option value="0">${data1.error}</option>`;
    }
    if (!data3.ok) {
            sel.innerHTML = `<option value="0">${data3.error}</option>`;
    }
        zesp.innerHTML = `<option value="0">Wybierz zespół</option>`;
        proj1.innerHTML = `<option value="0">Wybierz projekt</option>`;
        sel.innerHTML = `<option value="0">Wybierz projekt</option>`;

        for (const v of data2.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_ze;
            opt.textContent = v.nazwa;
            zesp.appendChild(opt);
        }

        for (const v of data1.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_p;
            opt.textContent = v.nazwa_projektu;
            proj1.appendChild(opt);
        }

        for (const v of data3.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_p;
            opt.textContent = v.nazwa_projektu;
            sel.appendChild(opt);
        }
        const opt = document.createElement('option');
        opt.value = "0";
        opt.textContent = "Chwilowo niedostępne";
        document.getElementById("pole9_3").appendChild(opt.cloneNode(true));
        document.getElementById("pole9_4").appendChild(opt.cloneNode(true));
        document.getElementById("pole9_5").appendChild(opt.cloneNode(true));
        document.getElementById("pole9_6").appendChild(opt.cloneNode(true));

        document.getElementById("sel2").appendChild(opt.cloneNode(true));
        document.getElementById("sel3").appendChild(opt.cloneNode(true));
        
        }catch (e) {
            //tu trzeba się pobawić
            console.log(e);
            zesp.innerHTML = `<option value="0">Błąd połączenia</option>`;
            proj1.innerHTML = `<option value="0">Błąd połączenia</option>`;
        }
}

async function get_requiredZesp_NOaso()
{
    const lid =document.getElementById("pole9_2"); //poza curent leader
    const new_u = document.getElementById("pole9_3"); //każdy kto jeszcz nie jest 
    const del_u = document.getElementById("pole9_4"); //każdy kto jużjest i nie jest liderem

    const id = document.getElementById("pole9_0").value;//id zespołu

    try{
        
        var wynik, str;

        str = `aso_u_ze_create&id=${id}`;
        wynik = await setupSimplify_get(str,new_u,"Nic nie dodawaj","/../app/db_kontrolers/start_edit.php");
        if(wynik.ok) setupSimplify_put(wynik.val,"id_u","login",new_u,false);

        str = `aso_u_ze_del&id=${id}`;
        wynik = await setupSimplify_get(str,del_u,"Nic nie usuwaj","/../app/db_kontrolers/start_edit.php");
        if(wynik.ok) setupSimplify_put(wynik.val,"id_u","login",del_u,false);

    } catch (e) {
            //tu trzeba się pobawić
            console.log(e);
            lid.innerHTML = `<option value="0">Błąd połączenia</option>`;
            new_u.innerHTML = `<option value="0">Błąd połączenia</option>`;
            del_u.innerHTML = `<option value="0">Błąd połączenia</option>`;
            //zad.innerHTML=`<option value="0">Błąd połączenia</option>`;
        }
    
}

async function get_requiredZesp_ASO()
{
    const new_aso =document.getElementById("pole9_5");
    const aso_del = document.getElementById("pole9_6");

    const id = document.getElementById("pole9_7").value;//id projektu
    const idq =  document.getElementById("pole9_0").value; //id zespołu

    try{
    
        var wynik;
        wynik = await setupSimplify_get(`aso_za_ze_create&id=${id}`,new_aso,"Wybierz nowe zadanie","/../app/db_kontrolers/start_edit.php"); //&idq=${idq}
        if(wynik.ok) setupSimplify_put(wynik.val,"id_za","nazwa_zadania",new_aso,false);

        wynik = await setupSimplify_get(`aso_za_ze_del&id=${id}&idq=${idq}`,aso_del,"Wybierz zadanie do usunięcia","/../app/db_kontrolers/start_edit.php");
        if(wynik.ok) setupSimplify_put(wynik.val,"id_za","nazwa_zadania",aso_del,false);


    } catch (e) {
            //tu trzeba się pobawić
            console.log(e);
            new_aso.innerHTML = `<option value="0">Błąd połączenia</option>`;
            aso_del.innerHTML = `<option value="0">Błąd połączenia</option>`;
            //zad.innerHTML=`<option value="0">Błąd połączenia</option>`;
        }
}

const ev1 = document.getElementById("pole9_0");
ev1.addEventListener('change', async (e) => {
    if(e.target.value == "0"){
        document.getElementById("pole9_2").innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
        document.getElementById("pole9_3").innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
        document.getElementById("pole9_4").innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
    } else {
        get_requiredZesp_NOaso();
    }
    if(document.getElementById("pole9_7").value!="0")
    {
        document.getElementById("pole9_5").innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
        document.getElementById("pole9_6").innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
    }
}); 

const ev2 = document.getElementById("pole9_7");
ev2.addEventListener('change', async (e) => {
    if(e.target.value == "0" || document.getElementById("pole9_0").value=="0"){
        document.getElementById("pole9_5").innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
        document.getElementById("pole9_6").innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
    } else {
        get_requiredZesp_ASO();
    }
}); 

async function setupSimplify_get(action, field, tekst, php){
    const res = await fetch(`${php}?action=${action}`,{method: 'GET'});
    //console.log(`${php}?action=${action}`);
    const data = JSON.parse(await res.text());
    //console.log(data);
    if (!data.ok) {
            field.innerHTML = `<option value="0">${data.error}</option>`;
            return {ok:false};
    } else {
        field.innerHTML = `<option value="0">${tekst}</option>`;
        return {ok: true, val: data};
    }
}

function setupSimplify_put(data, id_name, other_name, field, isproj){
    for (const v of data.ret_val) {
            const opt = document.createElement('option');
            opt.value = v[id_name];
            opt.textContent = v[other_name];
            field.appendChild(opt);
            if(isproj){
                document.getElementById("pole9_7").appendChild(opt.cloneNode(true));
                document.getElementById("pole11_0").appendChild(opt.cloneNode(true));
            }
        }
}


const b1 = document.getElementById("but2");
const b2 = document.getElementById("but3");
const res = document.getElementById("response");

//te eventlistenery do podmianki zmiennych
b1.addEventListener('click', async () =>{
    if(sel2.value == "0"){
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

b2.addEventListener('click', async () =>{
    if(sel3.value == "0"){
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
//do tąd

const sel2 = document.getElementById("sel2");

const sel3 = document.getElementById("sel3");
document.getElementById("sel1").addEventListener('change', async (e) =>{
    if(e.target.value == "0"){
        sel3.innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
        sel2.innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
    } else {
        get_required();
        get_required2();
    }
});

async function get_required(){
    const id = document.getElementById("sel1").value;//id projektu

    try{
        const res1 = await fetch(`/../app/db_kontrolers/main_admin.php?action=zadanie_1&id=${encodeURIComponent(id)}`,{method: 'GET'});
 
        const data1 = JSON.parse(await res1.text());
        if (!data1.ok) {
            sel2.innerHTML = `<option value="0">${data1.error}</option>`;
            return;
        } else {
            sel2.innerHTML = `<option value="0">Wybierz zadanie</option>`;
        }

        for (const v of data1.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_za;
            opt.textContent = v.nazwa_zadania;
            sel2.appendChild(opt);
            
        }
    } catch (e) {
            //tu trzeba się pobawić
            console.log(e);
            sel2.innerHTML = `<option value="0">Błąd połączenia</option>`;
        }
}

async function get_required2(){
    const id = document.getElementById("sel1").value;//id projektu

    try{
        const res1 = await fetch(`/../app/db_kontrolers/main_admin.php?action=zadanie_2&id=${encodeURIComponent(id)}`,{method: 'GET'});
 
        const data1 = JSON.parse(await res1.text());
        if (!data1.ok) {
            sel3.innerHTML = `<option value="0">${data1.error}</option>`;
            return;
        } else {
            sel3.innerHTML = `<option value="0">Wybierz zadanie</option>`;
        }

        for (const v of data1.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_za;
            opt.textContent = v.nazwa_zadania;
            sel3.appendChild(opt);
            
        }
    } catch (e) {
            //tu trzeba się pobawić
            console.log(e);
            sel3.innerHTML = `<option value="0">Błąd połączenia</option>`;
        }
}
