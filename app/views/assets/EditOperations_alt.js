const form1 = document.getElementById("form_edit_user");
const kom1 = document.getElementById("form_edit_user_kom");

function UserEditCheck(){
    var res = "Niepoprawny format danych w nie-pustych polach: ";
    kom1.style.color = "red";
    var count = 0;

    if(form1.pole8_0.selectedIndex == 0){ //id
        kom1.textContent = "No, nie zedytujesz obiektu jak nie wyspecyfikujesz o jaki ci chodzi";
        return false;
    }
    if(form1.pole8_4.value.trim() === "" && form1.pole8_3.value.trim() === "" && form1.pole8_2.value.trim() === "" && form1.pole8_1.value.trim() === "" && form1.pole8_5.value.trim() === "" && form1.pole8_6.selectedIndex === 0){
        kom1.textContent = "bruh";
        return false;
    }

    if(form1.pole8_1.value != "" && !(/^[a-zA-ZąćńęłżźóĄĘĆŻŹÓŃŁ]+( [a-zA-ZąćńęłżźóĄĘĆŻŹÓŃŁ]+)*\s*$/.test(form1.pole8_1.value))){ //imie
        count++;
        form1.pole8_1.value = "";
        res += "imie";
    }
    if(form1.pole8_2.value != "" && !(/^[a-zA-ZąćńęłżźóĄĘĆŻŹÓŃŁ]+(-[a-zA-ZąćńęłżźóĄĘĆŻŹÓŃŁ]+)*\s*$/.test(form1.pole8_2.value))){ //nazwisko
        if(count == 1) res+=", ";
        count++;
        form1.pole8_2.value = "";
        res += "nazwisko";
    }
    if(form1.pole8_3.value != "" && !(/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/.test(form1.pole8_3.value))){ //mail
        if(count >= 1) res+=", ";
        count++;
        form1.pole8_3.value = "";
        res += "mail";
    }
    if(form1.pole8_4.value != "" && !(/^[a-zA-Z0-9_&$]{4,}$/.test(form1.pole8_4.value))){ //login
        if(count >= 1) res+=", ";
        count++;
        form1.pole8_4.value = "";
        res += "login";
    }
    if(count!=0) {kom1.textContent = res+="!"; return false;}
    return true;
}

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
   if(form2.pole9_1.value == "" && form2.pole9_2.value=='0' && form2.pole9_8.value=='0'){
        kom2.textContent = "I po co w ten przycisk klikasz, co?";
        return false;
   }

    if(count!=0) {kom2.textContent = res+="!"; return false;}
    return true;
}


const form3 = document.getElementById("form_edit_project");
const kom3 = document.getElementById("form_edit_project_kom");

function ProjectEditCheck(){
    var res = "Niepoprawny format danych w nie-pustych polach: ";
    kom3.style.color = "red";
    var count = 0;

    if(document.getElementById("pole10_0").selectedIndex == 0){ //id
        kom3.textContent = "No, nie zedytujesz obiektu jak nie wyspecyfikujesz o jaki ci chodzi";
        return false;
    }

    if(form3.pole10_1.value != "" && !(/^[a-zA-Z0-9ąćńęłżźóĄĘĆŻŹÓŃŁ &/\-]+$/.test(form3.pole10_1.value))){ //nazwa
        count++;
        res += "nazwa";
        form3.pole10_1.value = "";
    }
    if(form3.pole10_1.value == "" && form3.pole10_2.value == "0" && form3.pole10_3.value == "0" && form3.pole10_6.value == "0" && form3.pole10_5.value == "0"){
        kom3.textContent = "No, halo, co?";
        return false;
    }
    if(count!=0) {kom3.textContent = res+="!"; return false;}

    return true;
}


const form4 = document.getElementById("form_edit_task");
const kom4 = document.getElementById("form_edit_task_kom");

function TaskEditCheck(){
    var res = "Niepoprawny format danych w nie-pustych polach: ";
    kom4.style.color = "red";
    var count = 0;

    if(document.getElementById("pole11_0").value == "0" || document.getElementById("pole11_8").value == "0"){ //id
        kom4.textContent = "No, nie zedytujesz obiektu jak nie wyspecyfikujesz o jaki ci chodzi";
        return false;
    }
    if(form4.pole11_1.value != "" && !(/^[a-zA-Z0-9ąćńęłżźóĄĘĆŻŹÓŃŁ &/\-]+$/.test(form4.pole11_1.value))){ //nazwa
        count++;
        res += "nazwa";
        form4.pole5_1.value = "";
    }

    if((form4.pole11_4.value >= form4.pole11_5.value) && (form4.pole11_4.value != "" && form4.pole11_5.value != "")){ //daty
        kom4.textContent = "Czas płynie w przeciwną stronę, ani jego pedkość nie wynosi \u221e!";
        return false;
    }

    /*if(form4.pole11_2.value != "" && (form4.pole11_2.value < 1 || form4.pole11_2.value >5)){
        if(count >= 1) res+=", ";
        count++;
        res += "priorytet(jak go wstawiasz to musi być między 1 a 5)";
    }*/
    if(form4.pole11_2.value === "" && form4.pole11_1.value === "" && form4.pole11_4.value === "" && form4.pole11_5.value === "" && form4.pole11_9.value === "0"){
        kom4.textContent = "No i po co?";
        return false;
    }

    if(count!=0) {kom4.textContent = res+="!"; return false;}
    return true;
}

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


document.addEventListener('DOMContentLoaded', () => {
  setup();
});

async function setup()
{
    //zadanie zostanie ruszone potem bo reworka wymaga
    const user = document.getElementById("pole8_0"); //org
    const zesp = document.getElementById("pole9_0"); //org
    const proj = document.getElementById("pole10_0"); //org
    const proj1 = document.getElementById("pole9_7"); //org
    const zad = document.getElementById("pole11_0"); 

    proj.innerHTML="<option value='0'>Ładowanie...</option>";
    proj1.innerHTML="<option value='0'>Ładowanie...</option>";
    user.innerHTML="<option value='0'>Ładowanie...</option>";
    zesp.innerHTML="<option value='0'>Ładowanie...</option>";
    zad.innerHTML="<option value='0'>Ładowanie...<option/>";

    try{
        
        var wynik;
        wynik = await setupSimplify_get('user',user,"Wybierz Użytkownika do edycji","/../app/db_kontrolers/start_crate.php");
        if(wynik.ok) setupSimplify_put(wynik.val,"id_u","login",user,false);

        wynik = await setupSimplify_get('zesp',zesp,"Wybierz Zespół do edycji","/../app/db_kontrolers/start_crate.php");
        if(wynik.ok) setupSimplify_put(wynik.val,"id_ze","nazwa",zesp,false);

        proj1.innerHTML = `<option value="0">Wybierz projekt</option>`;
        zad.innerHTML = `<option value="0">Wybierz projekt</option>`;
        wynik = await setupSimplify_get('projekt',proj,"Wybierz Projekt do edycji","/../app/db_kontrolers/start_crate.php");
        if(wynik.ok) setupSimplify_put(wynik.val,"id_p","nazwa_projektu",proj,true);

        const opt = document.createElement('option');
        opt.value = "0";
        opt.textContent = "Chwilowo niedostępne";
        document.getElementById("pole9_2").appendChild(opt);
        document.getElementById("pole9_3").appendChild(opt.cloneNode(true));
        document.getElementById("pole9_4").appendChild(opt.cloneNode(true));
        document.getElementById("pole9_5").appendChild(opt.cloneNode(true));
        document.getElementById("pole9_6").appendChild(opt.cloneNode(true));
        document.getElementById("pole10_2").appendChild(opt.cloneNode(true));
        document.getElementById("pole10_3").appendChild(opt.cloneNode(true));
        document.getElementById("pole11_8").appendChild(opt.cloneNode(true));
        document.getElementById("pole11_6").appendChild(opt.cloneNode(true));
        document.getElementById("pole11_7").appendChild(opt.cloneNode(true));
        document.getElementById("pole10_4").appendChild(opt.cloneNode(true));

        }catch (e) {
            //tu trzeba się pobawić
            console.log(e);
            proj1.innerHTML = `<option value="0">Błąd połączenia</option>`;
            proj.innerHTML = `<option value="0">Błąd połączenia</option>`;
            user.innerHTML = `<option value="0">Błąd połączenia</option>`;
            zesp.innerHTML = `<option value="0">Błąd połączenia</option>`;
            zad.innerHTML=`<option value="0">Błąd połączenia</option>`;
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
        str = `lider&id=${id}`;
        wynik = await setupSimplify_get(str,lid,"Nie zmieniaj lidera","/../app/db_kontrolers/start_edit.php");
        if(wynik.ok) setupSimplify_put(wynik.val,"id_u","login",lid,false);

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

async function get_requiredProj()
{
    const admin =document.getElementById("pole10_2");
    const typ = document.getElementById("pole10_3");
    const zad = document.getElementById("pole10_4");

    const id = ev3.value;//id projektu

    try{
    
        var wynik;
        wynik = await setupSimplify_get(`admin&id=${encodeURIComponent(id)}`,admin,"Nie zmieniaj admina","/../app/db_kontrolers/start_edit.php");
        if(wynik.ok) setupSimplify_put(wynik.val,"id_u","login",admin,false);

        wynik = await setupSimplify_get(`typ&id=${encodeURIComponent(id)}`,typ,"Nie zmieniaj typu","/../app/db_kontrolers/start_edit.php");
        if(wynik.ok) setupSimplify_put(wynik.val,"id_t","nazwa_typu",typ,false);

        wynik = await setupSimplify_get(`zadanie&id=${encodeURIComponent(id)}`,zad,"Nie usuwaj zadania","/../app/db_kontrolers/start_edit.php");
        if(wynik.ok) setupSimplify_put(wynik.val,"id_za","nazwa_zadania",zad,false);


    } catch (e) {
            //tu trzeba się pobawić
            console.log(e);
            typ.innerHTML = `<option value="0">Błąd połączenia</option>`;
            admin.innerHTML = `<option value="0">Błąd połączenia</option>`;
            zad.innerHTML=`<option value="0">Błąd połączenia</option>`;
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

const ev3 = document.getElementById("pole10_0");
ev3.addEventListener('change', async (e) => {
    if(e.target.value == "0"){
        document.getElementById("pole10_2").innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
        document.getElementById("pole10_3").innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
        document.getElementById("pole10_4").innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
    } else {
        get_requiredProj();
    }
}); 

const ev4 = document.getElementById("pole11_0");
ev4.addEventListener('change', async (e) => {
    if(e.target.value == "0"){
        document.getElementById("pole11_7").innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
        document.getElementById("pole11_6").innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
        document.getElementById("pole11_8").innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
    } else {
        try{
        const id = ev4.value;
        
       var wynik;
        wynik = await setupSimplify_get(`zadanie&id=${encodeURIComponent(id)}`,document.getElementById("pole11_8"),"Wybierz zadanie","/../app/db_kontrolers/start_edit.php");
        if(wynik.ok) setupSimplify_put(wynik.val,"id_za","nazwa_zadania",document.getElementById("pole11_8"),false);
        

        document.getElementById("pole11_7").innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
        document.getElementById("pole11_6").innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
        }catch(e){
            console.log(e);
            document.getElementById("pole11_8").innerHTML = `<option value="0">Błąd połączenia</option>`;
        }
    }
}); 

const ev5 = document.getElementById("pole11_8");
ev5.addEventListener('change', async (e) => {
    if(e.target.value == "0"){
        document.getElementById("pole11_7").innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
        document.getElementById("pole11_6").innerHTML=`<option value="0">Chwilowo niedostępne</option>`;
    } else {
        //const creat = document.getElementById("pole11_6");
        //const del = document.getElementById("pole11_7");
        try{
        const id = ev5.value;
        const idq = document.getElementById("pole11_0").value;
        
        var wynik;
        wynik = await setupSimplify_get(`zadanie_new&id=${encodeURIComponent(id)}&idq=${encodeURIComponent(idq)}`,document.getElementById("pole11_6"),"Nic nie dodawaj","/../app/db_kontrolers/start_edit.php");
        if(wynik.ok) setupSimplify_put(wynik.val,"id_za","nazwa_zadania",document.getElementById("pole11_6"),false);

        wynik = await setupSimplify_get(`zadanie_del&id=${encodeURIComponent(id)}`,document.getElementById("pole11_7"),"Nic nie usuwaj","/../app/db_kontrolers/start_edit.php");
        if(wynik.ok) setupSimplify_put(wynik.val,"id_za","nazwa_zadania",document.getElementById("pole11_7"),false);
        

        }catch(e){
            console.log(e);
            document.getElementById("pole11_6").innerHTML = `<option value="0">Błąd połączenia</option>`;
            document.getElementById("pole11_7").innerHTML = `<option value="0">Błąd połączenia</option>`;
        }
    }
});

const but1 = document.getElementById("b13"); //user
const but2 = document.getElementById("b17"); //zesp
const but3 = document.getElementById("b18"); //aso uze
const but4 = document.getElementById("b14"); //aso zaze
const but5 = document.getElementById("b19"); //proj
const but6 = document.getElementById("b15"); //zad del

const but7 = document.getElementById("b20"); //zad edit
const but8 = document.getElementById("b16"); //aso zaself

but1.addEventListener('click', async () => {
    if(!UserEditCheck())return;

    const formData = new FormData(form1);
    formData.append('action', 'edit_user');

    const opt = document.getElementById("pole8_0").selectedIndex;
    const new_login = document.getElementById("pole8_4").value.trim();
    //console.log(new_login);

    try {
        const data = await sendOver('PUT',formData,'/../app/db_kontrolers/editphp.php',true);
        resultAction(data,kom1,form1,true);

        if(new_login!==""){
            document.getElementById("pole8_0").options[opt].textContent = new_login;
        }

        } catch (err) {
            console.log(err);
            kom1.textContent = 'Błąd połączenia z serwerem.';
            kom1.style.color = 'red';
        }
});

but2.addEventListener('click', async () => {
    if(!TeamEditCheck())return;

    const formData = new FormData(form2);
    formData.append('action', 'edit_zesp');
    formData.append('pole9_0', `${encodeURIComponent(ev1.value)}`);

    const opt = document.getElementById("pole9_0").selectedIndex;
    const new_name = document.getElementById("pole9_1").value.trim();

    try {
        const data = await sendOver('PUT',formData,'/../app/db_kontrolers/editphp.php',true);
        resultAction(data,kom2,form2,true);

        if(new_name!==""){
            document.getElementById("pole9_0").options[opt].textContent = new_name;
        }

        document.getElementById("pole9_0").value = "0";
        } catch (err) {
            console.log(err);
            kom2.textContent = 'Błąd połączenia z serwerem.';
            kom2.style.color = 'red';
        }
});

async function sendOver(send_method, formdata, uri, isformdata){
    var res;
    //console.log(JSON.stringify(Object.fromEntries(formdata.entries())));
    if(isformdata){
        res = await fetch(uri, {
            method: send_method,
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(Object.fromEntries(formdata.entries()))
        });
    }else if(send_method==='POST'){
        res = await fetch(uri, {
            method: 'POST',
            body: formdata
        });
    } else {
        res = await fetch(uri, {
            method: send_method,
            headers: { "Content-Type": "application/json" },
            body: formdata
        });
    }
    //console.log(res.text());
    const data = await res.json();
    return data;

}

function resultAction(data, kom, form, doreset){
     if (data.ok) {
      kom.textContent = data.message;
      kom.style.color = 'green';
      setTimeout(() => {kom.textContent = "";}, 3000);
      if(doreset) form.reset();
    } else {
      kom.textContent = data.error;
      kom.style.color = 'red';
    }
}

const form7 = document.getElementById("form_edit_aso_u");
but3.addEventListener('click', async () => {
    if(document.getElementById("pole9_3").value==="0" && document.getElementById("pole9_4").value==="0"){
        kom2.textContent = 'No i po co?';
        kom2.style.color = 'red';
        return;}

    const formData = new FormData(form7);
    formData.append('action', 'edit_u_ze');
    formData.append('pole9_0', `${encodeURIComponent(ev1.value)}`);

    const to_add1 = document.getElementById("pole9_4").selectedIndex; //jak dodamy kogoś to dodajemy jego rekord do możliwych do usuwanięcia
    const to_add2 = document.getElementById("pole9_3").selectedIndex; //jak kogoś usuniemy to daodajemy rekord do możliwych do dodania
    //console.log(to_add1);
    //console.log(to_add2);
    
    if(document.getElementById("pole9_3").value!=="0"){
        try {
            const data = await sendOver('POST',formData,'/../app/db_kontrolers/editphp.php',false);
            resultAction(data,kom2,form7,false);

                const opt = document.createElement('option');
                opt.value = document.getElementById("pole9_3")[to_add2].value;
                opt.textContent = document.getElementById("pole9_3")[to_add2].textContent;
                document.getElementById("pole9_4").appendChild(opt);

                document.getElementById("pole9_3").remove(to_add2);

        } catch (err) {
            console.log(err);
            kom2.textContent = 'Błąd połączenia z serwerem.';
            kom2.style.color = 'red';
        }
    }
    if(document.getElementById("pole9_4").value!=="0"){
        try {
            const data = await sendOver('DELETE',formData,'/../app/db_kontrolers/editphp.php',true);
            resultAction(data,kom2,form7,false);

                const opt = document.createElement('option');
                opt.value = document.getElementById("pole9_4")[to_add1].value;
                opt.textContent = document.getElementById("pole9_4")[to_add1].textContent;
                document.getElementById("pole9_3").appendChild(opt);

                document.getElementById("pole9_4").remove(to_add1);
        } catch (err) {
            console.log(err);
            kom2.textContent = 'Błąd połączenia z serwerem.';
            kom2.style.color = 'red';
        }
    }

    //setTimeout(() => {kom2.textContent = "";}, 3000);
    form7.reset();
});

const form8 = document.getElementById("form_edit_aso_za");
but4.addEventListener('click', async () => {
    if(document.getElementById("pole9_5").value ==="0" && document.getElementById("pole9_6").value ==="0"){
        kom2.textContent = 'No i po co?';
        kom2.style.color = 'red';
        return;}

    const formData = new FormData(form8);
    formData.append('action', 'edit_za_ze');
    formData.append('pole9_0', `${encodeURIComponent(ev1.value)}`);

    const to_add1 = document.getElementById("pole9_5").selectedIndex;
    const to_add2 = document.getElementById("pole9_6").selectedIndex;

    if(document.getElementById("pole9_5").value!=="0"){
        try {
            const data = sendOver('POST',formData,'/../app/db_kontrolers/editphp.php',false);
            resultAction(data,kom2,form8,false);

                const opt = document.createElement('option');
                opt.value = document.getElementById("pole9_5")[to_add1].value;
                opt.textContent = document.getElementById("pole9_5")[to_add1].textContent;
                document.getElementById("pole9_6").appendChild(opt);

                document.getElementById("pole9_5").remove(to_add1);


        } catch (err) {
            console.log(err);
            kom2.textContent = 'Błąd połączenia z serwerem.';
            kom2.style.color = 'red';
        }
    }
    if(document.getElementById("pole9_6").value!=="0"){
        try {
            const data = sendOver('DELETE',formData,'/../app/db_kontrolers/editphp.php',true);
            resultAction(data,kom2,form8,false);

                const opt = document.createElement('option');
                opt.value = document.getElementById("pole9_6")[to_add2].value;
                opt.textContent = document.getElementById("pole9_6")[to_add2].textContent;
                document.getElementById("pole9_5").appendChild(opt);

                document.getElementById("pole9_6").remove(to_add2);
        } catch (err) {
            console.log(err);
            kom2.textContent = 'Błąd połączenia z serwerem.';
            kom2.style.color = 'red';
        }
    }

    //setTimeout(() => {kom2.textContent = "";}, 3000);
    form8.reset();
});

but5.addEventListener('click', async () => {
    if(!ProjectEditCheck())return;

    const formData = new FormData(form3);
    formData.append('action', 'edit_proj');
    formData.append('pole10_0', `${encodeURIComponent(ev3.value)}`);

    const opt = document.getElementById("pole10_0").selectedIndex;
    const new_nazwa = document.getElementById("pole10_1").value.trim();

    try {
            const data = await sendOver('PUT',formData,'/../app/db_kontrolers/editphp.php',true);
            //console.log(data);
            resultAction(data,kom3,form3,true);

            if(new_nazwa!==""){
                document.getElementById("pole10_0").options[opt].textContent = new_nazwa;
            }
            if(/*document.getElementById("pole10_6").selectedIndex.value==="1" ||*/ document.getElementById("pole10_5").selectedIndex.value ==="1"){
                document.getElementById("pole10_0").remove(document.getElementById("pole10_0").options[opt]);
            }
            document.getElementById("pole10_0").value = "0";

        } catch (err) {
            console.log(err);
            kom3.textContent = 'Błąd połączenia z serwerem.';
            kom3.style.color = 'red';
        }
    //setTimeout(() => {kom3.textContent = "";}, 3000);
    //form3.reset();
});

but6.addEventListener('click', async () => {
    if(document.getElementById("pole10_4").value=="0")
    {
        kom3.textContent = 'No ale po co jak nic nie usuwasz?';
        kom3.style.color = 'red';
        return;
    }

    const formData = JSON.stringify({
        action: 'del_za',
        pole10_4: `${encodeURIComponent(document.getElementById("pole10_4").value)}`
    });

    try {
            const data = await sendOver('DELETE',formData,'/../app/db_kontrolers/editphp.php',false);
            //console.log(data);
            resultAction(data,kom3,form3,false); //to form3 jest tylko po to by było i żeby funkcja nie spadła z rowerka

            document.getElementById("pole10_4").remove(document.getElementById("pole10_4").selectedIndex);
        } catch (err) {
            console.log(err);
            kom3.textContent = 'Błąd połączenia z serwerem.';
            kom3.style.color = 'red';
        }

    //setTimeout(() => {kom3.textContent = "";}, 3000);
    document.getElementById("pole10_4").value = "0";
});


but7.addEventListener('click', async () => {
    if(!TaskEditCheck())
        return;

    const formData = new FormData(form4);
    formData.append('action', 'edit_zad');
    formData.append('pole11_8', `${encodeURIComponent(ev5.value)}`);

    const opt = document.getElementById("pole11_8").selectedIndex;
    const new_name = document.getElementById("pole11_1").value.trim();

    try {
            const data = await sendOver('PUT',formData,'/../app/db_kontrolers/editphp.php',true);
            console.log(data);
            resultAction(data,kom4,form4,true);

            if(new_name!==""){
            document.getElementById("pole11_8").options[opt].textContent = new_name;
        }
        document.getElementById("pole11_8").value="0";
        } catch (err) {
            console.log(err);
            kom4.textContent = 'Błąd połączenia z serwerem.';
            kom4.style.color = 'red';
        }

    //setTimeout(() => {kom4.textContent = "";}, 3000);
    //form4.reset();
});


const form9 = document.getElementById("form_edit_zself");
but8.addEventListener('click', async () => {
    if(document.getElementById("pole11_6").value=="0" && document.getElementById("pole11_7").value=="0"){
        kom4.textContent = 'No i po co?';
        kom4.style.color = 'red';
        return;}

    const formData = new FormData(form9);
    formData.append('action', 'aso_self');
    formData.append('pole11_8', ev5.value);

    const to_add1 = document.getElementById("pole11_6").selectedIndex;
    const to_add2 = document.getElementById("pole11_7").selectedIndex;
    //console.log(document.getElementById("pole11_6"));

    if(document.getElementById("pole11_6").value!=="0"){
        try {
            const data = await sendOver('POST',formData,'/../app/db_kontrolers/editphp.php',false);
            resultAction(data,kom4,form9,false);

            const opt = document.createElement('option');
                opt.value = document.getElementById("pole11_6").options[to_add1].value;
                opt.textContent = document.getElementById("pole11_6").options[to_add1].textContent;
                document.getElementById("pole11_7").appendChild(opt);

                document.getElementById("pole11_6").remove(to_add1);
        } catch (err) {
            console.log(err);
            kom4.textContent = 'Błąd połączenia z serwerem.';
            kom4.style.color = 'red';
        }
    }
    if(document.getElementById("pole11_7").value!=="0"){
        //console.log("co kuraw");
        try {
            const data = await sendOver('DELETE',formData,'/../app/db_kontrolers/editphp.php',true);
            resultAction(data,kom4,form9,false);

            const opt = document.createElement('option');
                opt.value = document.getElementById("pole11_7")[to_add2].value;
                opt.textContent = document.getElementById("pole11_7")[to_add2].textContent;
                document.getElementById("pole11_6").appendChild(opt);

                document.getElementById("pole11_7").remove(to_add2);
        } catch (err) {
            console.log(err);
            kom4.textContent = 'Błąd połączenia z serwerem.';
            kom4.style.color = 'red';
        }
    }

    //setTimeout(() => {kom4.textContent = "";}, 3000);
    form9.reset();
});
