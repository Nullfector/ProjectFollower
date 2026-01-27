const form2 = document.getElementById("form_type");
const kom2 = document.getElementById("form_type_kom");

/*function TypeCheck() {
    if(!(/^[a-zA-Z0-9_& \-]+\s*$/.test(form2.pole2.value))){ //nazwa typu
        kom2.style.color = "red";
        kom2.textContent = "Niepoprawny format danych!";
        form2.pole2.value = "";
        return false;
    }
    return true;
}*/

const but2 = document.getElementById("b2");

async function communication(form, action, dosetup, kom){
   
  const formData = new FormData(form);
  formData.append('action', action);

  const res = await fetch('/../app/db_kontrolers/createphp.php', {
        method: 'POST',
        body: formData
    });
    const data = await res.json();
    console.log(data);
      
    if (data.ok) {
        kom.textContent = 'Zapisano.';
        kom.style.color = 'green';
        setTimeout(() => {kom.textContent = "";}, 3000);
        form.reset();

        if(dosetup) setup();

      } else {
        kom.textContent = data.error;
        kom.style.color = 'red';
      }
}

but2.addEventListener('click', async () => {

  if (!TypeCheck()) {
    return;
  }
    try{
      communication(form2,'add_typ',true,kom2);
    } catch (err) {
      console.log(err);
      kom2.textContent = 'Błąd połączenia z serwerem.';
      kom2.style.color = 'red';
  }
});


const form3 = document.getElementById("form_user");
const kom3 = document.getElementById("form_user_kom");

function UserCheck() {
    var count = 0;
    kom3.style.color = "red";
    var res = "Niepoprawne (lub brak) dane w polach: ";
    if(!(/^[a-zA-ZąćńęłżźóĄĘĆŻŹÓŃŁ]+( [a-zA-ZąćńęłżźóĄĘĆŻŹÓŃŁ]+)*\s*$/.test(form3.pole3_1.value))){ //imie
        count++;
        res += "imie";
    }
    if(!(/^[a-zA-ZąćńęłżźóĄĘĆŻŹÓŃŁ]+(-[a-zA-ZąćńęłżźóĄĘĆŻŹÓŃŁ]+)*\s*$/.test(form3.pole3_2.value))){ //nazwisko
        if(count == 1) res+=", ";
        count++;
        res += "nazwisko";
    }
    if(!(/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/.test(form3.pole3_3.value))){ //mail
        if(count >= 1) res+=", ";
        count++;
        res += "mail";
    }
    if(!(/^[a-zA-Z0-9_&$]{4,}$/.test(form3.pole3_4.value))){ //login
        if(count >= 1) res+=", ";
        count++;
        res += "login";
    }
    if(form3.pole3_5.value == ""){ //hasło (NA RAZIE NIE MA SPRAWDZANIA CZY HASŁO JEST WYSTARCZAJĄCE)
        if(count >= 1) res+=", ";
        count++;
        res += "hasło(puste)";
    }
    if(form3.pole3_6.selectedIndex == 0){ //rola
        if(count >= 1) res+="! Oraz wybrać Role";
        else res = "Należy wybrać rolę"; 
        count++;
    }
    if(count!=0) {kom3.textContent = res+="!"; return false;}
    return true;
}

const but3 = document.getElementById("b3");

but3.addEventListener('click', async () => {

  if (!UserCheck()) {
    return;
  }
  try{
      communication(form3,'add_user',false,kom3);
    } catch (err) {
      console.log(err);
      kom3.textContent = 'Błąd połączenia z serwerem.';
      kom3.style.color = 'red';
    }
});

const form4 = document.getElementById("form_team");
const kom4 = document.getElementById("form_team_kom");

function TeamCheck() {
    var count = 0;
    kom4.style.color = "red";
    var res = "Niepoprawne (lub brak) dane w polach: ";
    if(!(/^[a-zA-Z0-9_& \-]+\s*$/.test(form4.pole4_1.value))){ //nazwa teamu
        res+="nazwa";
        count++;
        form4.pole4_1.value = "";
    }
    if(form4.pole4_2.value <= 0 || form4.pole4_2.value == ""){ //id lidera
        if(count == 1) res+=", ";
        res+="lider";
        count++;
        form4.pole4_2.value = "";
    }
    if(count!=0) {kom4.textContent = res+"!"; return false;}
    return true;
}

const but4 = document.getElementById("b4");

but4.addEventListener('click', async () => {

  if (!TeamCheck()) {
    return;
  }
  try{
      communication(form4,'add_zesp',false,kom4);
    } catch (err) {
      console.log(err);
      kom4.textContent = 'Błąd połączenia z serwerem.';
      kom4.style.color = 'red';
    }
});


const form5 = document.getElementById("form_project");
const kom5 = document.getElementById("form_project_kom");

function ProjectCheck() {
    var count = 0;
    kom5.style.color = "red";
    var res = "Niepoprawne (lub brak) dane w polach: ";
    if(!(/^[a-zA-Z0-9ąćńęłżźóĄĘĆŻŹÓŃŁ &/\-]+$/.test(form5.pole5_1.value))){ //nazwa
        count++;
        res += "nazwa";
        form5.pole5_1.value = "";
    }
    if(form5.pole5_3.value == ""){ //data startu
        if(count >= 1) res+=", ";
        count++;
        res += "data";
    }
    if(form5.pole5_4.value < 0 && form5.pole5_4.value !=""){ //id administratora - raczej potrzebne nie jest
        if(count >= 1) res+=", ";
        count++;
        res += "administrator";
        form5.pole5_4.value = "";
    }
    if(form5.pole5_2.selectedIndex == 0){ //typ projektu
        if(count >= 1) res+="! Oraz wybrać Typ";
        else res = "Należy wybrać typ"; 
        count++;
    }
    if(count!=0) {kom5.textContent = res+="!"; return false;}
    return true;
}

const but5 = document.getElementById("b5");

but5.addEventListener('click', async () => {

  if (!ProjectCheck()) {
    return;
  }
  try{
      communication(form5,'add_proj',true,kom5);
    } catch (err) {
      console.log(err);
      kom5.textContent = 'Błąd połączenia z serwerem.';
      kom5.style.color = 'red';
    }
}); 


const form6 = document.getElementById("form_zadanie");
const kom6 = document.getElementById("form_zadanie_kom");

function ZadanieCheck() {
    var count = 0;
    kom6.style.color = "red";
    var res = "Niepoprawne (lub brak) dane w polach: ";
    if(!(/^[a-zA-Z0-9ąćńęłżźóĄĘĆŻŹÓŃŁ &/\-]+$/.test(form6.pole6_1.value))){ //nazwa
        count++;
        res += "nazwa";
        form5.pole5_1.value = "";
    }
    if(form6.pole6_3.value == ""){ //data startu
        if(count >= 1) res+=", ";
        count++;
        res += "data startu";
    }
    if(form6.pole6_4.value == ""){ //data zakończenia
        if(count >= 1) res+=", ";
        count++;
        res += "data zakończenia";
    }
    if((form6.pole6_3.value >= form6.pole6_4.value) && (form6.pole6_3.value != "" || form6.pole6_4.value != "")){// poprawność dat względem siebie
        kom6.textContent = "Czas płynie w przeciwną stronę, ani jego pedkość nie wynosi \u221e!";
        return false;
    }

    //priorytet NIE JEST KONIECZNYM POLEM - tylko poglądowym! ale jeżeli istnieje to musi być sprawdzony
    if(form6.pole6_5.value != "" && (form6.pole6_5.value < 1 || form6.pole6_5.value >5)){
        if(count >= 1) res+=", ";
        count++;
        res += "priorytet(jak go wstawiasz to musi być między 1 a 5)";
    }
    if(form6.pole6_2.selectedIndex == 0){ //nadrzędny projekt
        if(count >= 1) res+="! Oraz wybrać nadrzędny projekt";
        else res = "Należy wybrać nadrzędny projekt"; 
        count++;
    }
    if(count!=0) {kom6.textContent = res+="!"; return false;}
    return true;
}

const but6 = document.getElementById("b6");

but6.addEventListener('click', async () => {

  if (!ZadanieCheck()) {
    return;
  }
  try{
      communication(form6,'add_zad',false,kom6);
    } catch (err) {
      console.log(err);
      kom6.textContent = 'Błąd połączenia z serwerem.';
      kom6.style.color = 'red';
    }
});

document.addEventListener('DOMContentLoaded', () => {
  setup();
});

async function setup()
{
    const typ = document.getElementById("pole5_2");
    const proj = document.getElementById("pole6_2");
    const lid = document.getElementById("pole4_2");
    const adm = document.getElementById("pole5_4");

    typ.innerHTML="<option value='0'>Ładowanie...<option/>";
    proj.innerHTML="<option value='0'>Ładowanie...<option/>";
    lid.innerHTML="<option value='0'>Ładowanie...<option/>";
    adm.innerHTML="<option value='0'>Ładowanie...<option/>";

    try{
        const res_proj = await fetch('/../app/db_kontrolers/start_crate.php?action=projekt',{method: 'GET'});
        const typ_proj = await fetch('/../app/db_kontrolers/start_crate.php?action=typ',{method: 'GET'});
        const lid_u = await fetch('/../app/db_kontrolers/start_crate.php?action=user&param=1',{method: 'GET'});

        const data2 = JSON.parse(await typ_proj.text());

        const data = JSON.parse(await res_proj.text());
        //console.log(lid_u.text());

        const data3 = JSON.parse(await lid_u.text());

        if (!data.ok) {
            proj.innerHTML = `<option value="0">${data.error}</option>`;
            return;
        }
        if (!data2.ok) {
            typ.innerHTML = `<option value="0">${data2.error}</option>`;
            return;
        }
        if (!data3.ok) {
            lid.innerHTML = `<option value="0">${data3.error}</option>`;
            return;
        }

        proj.innerHTML = `<option value="0">Wybierz projekt</option>`;
        for (const v of data.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_p;
            opt.textContent = v.nazwa_projektu;
            proj.appendChild(opt);
        }

        typ.innerHTML = `<option value="0">Wybierz Typ</option>`;
        for (const v of data2.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_t;
            opt.textContent = v.nazwa_typu;
            typ.appendChild(opt);
        }

        lid.innerHTML = `<option value="0">Wybierz lidera</option>`;
        adm.innerHTML = `<option value="0">Brak administratora</option>`;
        for (const v of data3.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_u;
            opt.textContent = v.login;
            lid.appendChild(opt);
            adm.appendChild(opt.cloneNode(true));

        }

        }catch (e) {
            //tu trzeba się pobawić
            console.log(e);
            proj.innerHTML = `<option value="0">Błąd połączenia</option>`;
            typ.innerHTML = `<option value="0">Błąd połączenia</option>`;
            lid.innerHTML=`<option value="0">Błąd połączenia</option>`;
            adm.innerHTML=`<option value="0">Błąd połączenia</option>`;
        }
}

