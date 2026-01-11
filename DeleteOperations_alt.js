const form7_2 = document.getElementById("form_del_type");
const kom7_2 = document.getElementById("form_del_type_kom");

async function DelType() {
    kom7_2.style.color = "green";
    if(form7_2.pole7_2.selectedIndex == 0){
        kom7_2.style.color = "red";
        kom7_2.textContent = "Nie wybrałeś co usunąć!"
        setTimeout(() => {kom7_2.textContent = "";}, 3000);
        return;
    }
        const id = form7_2.pole7_2.value;
        const payload = {
            action: "del_typ",
            id: id
        };
    try {

        const res = await fetch("deletephp.php", {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
        });

        const data = await res.json();
        
        if (data.ok) {
            kom7_2.style.color = "green";
            kom7_2.textContent = "BUM BUM BUM BUM!"
            setTimeout(() => {kom7_2.textContent = "";}, 3000);

            form7_2.pole7_2.remove(form7_2.pole7_2.selectedIndex);
            form7_2.reset();

        } else {
            kom7_2.textContent = data.error;
            kom7_2.style.color = 'red';
        }


    } catch (err) {
        console.log(err);
        kom7_2.textContent = 'Błąd połączenia z serwerem.';
        kom7_2.style.color = 'red';
    }
}


const form7_3 = document.getElementById("form_del_user");
const kom7_3 = document.getElementById("form_del_user_kom");

async function DelUser() {
    kom7_3.style.color = "green";
    if(form7_3.pole7_3.selectedIndex == 0){
        kom7_3.style.color = "red";
        kom7_3.textContent = "Nie wybrałeś kogo usunąć z egzystęcji!"
        setTimeout(() => {kom7_3.textContent = "";}, 3000);
        return;
    }
    
const id = form7_3.pole7_3.value;
        const payload = {
            action: "del_user",
            id: id
        };
        
    try {

        const res = await fetch("deletephp.php", {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
        });

        const data = await res.json();
        
        if (data.ok) {
            kom7_3.style.color = "green";
            kom7_3.textContent = "BUM BUM BUM BUM!"
            setTimeout(() => {kom7_3.textContent = "";}, 3000);

            form7_3.pole7_3.remove(form7_3.pole7_3.selectedIndex);
            form7_3.reset();

        } else {
            kom7_3.textContent = data.error;
            kom7_3.style.color = 'red';
        }


    } catch (err) {
        console.log(err);
        kom7_3.textContent = 'Błąd połączenia z serwerem.';
        kom7_3.style.color = 'red';
    }

    kom7_3.style.color = "green";
    kom7_3.textContent = "BUM BUM BUM BUM!"
    setTimeout(() => {kom7_3.textContent = "";}, 3000);
    form7_3.reset();
}


const form7_4 = document.getElementById("form_del_team");
const kom7_4 = document.getElementById("form_del_team_kom");

async function DelTeam() {
    kom7_4.style.color = "green";
    if(form7_4.pole7_4.selectedIndex == 0){
        kom7_4.style.color = "red";
        kom7_4.textContent = "Nie wybrałeś co usunąć!"
        setTimeout(() => {kom7_4.textContent = "";}, 3000);
        return;
    }
    
const id = form7_4.pole7_4.value;
        const payload = {
            action: "del_zesp",
            id: id
        };
    try {

        const res = await fetch("deletephp.php", {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
        });

        const data = await res.json();
        
        if (data.ok) {
            kom7_4.style.color = "green";
            kom7_4.textContent = "BUM BUM BUM BUM!"
            setTimeout(() => {kom7_4.textContent = "";}, 3000);

            form7_4.pole7_4.remove(form7_4.pole7_4.selectedIndex);
            form7_4.reset();

        } else {
            kom7_4.textContent = data.error;
            kom7_4.style.color = 'red';
        }


    } catch (err) {
        console.log(err);
        kom7_4.textContent = 'Błąd połączenia z serwerem.';
        kom7_4.style.color = 'red';
    }

    kom7_4.style.color = "green";
    kom7_4.textContent = "BUM BUM BUM BUM!"
    setTimeout(() => {kom7_4.textContent = "";}, 3000);
    form7_4.reset();
}


const form7_5 = document.getElementById("form_del_project");
const kom7_5 = document.getElementById("form_del_project_kom");

async function DelProject() {
    kom7_5.style.color = "green";
    if(form7_5.pole7_5.selectedIndex == 0){
        kom7_5.style.color = "red";
        kom7_5.textContent = "Nie wybrałeś co usunąć!"
        setTimeout(() => {kom7_5.textContent = "";}, 3000);
        return;
    }
    
    const id = form7_5.pole7_5.value;
        const payload = {
            action: "del_proj",
            id: id
        };
    try {

        const res = await fetch("deletephp.php", {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
        });

        const data = await res.json();
        
        if (data.ok) {
            kom7_5.style.color = "green";
            kom7_5.textContent = "BUM BUM BUM BUM!"
            setTimeout(() => {kom7_5.textContent = "";}, 3000);

            form7_5.pole7_5.remove(form7_5.pole7_5.selectedIndex);
            form7_5.reset();

        } else {
            kom7_5.textContent = data.error;
            kom7_5.style.color = 'red';
        }


    } catch (err) {
        console.log(err);
        kom7_5.textContent = 'Błąd połączenia z serwerem.';
        kom7_5.style.color = 'red';
    }


    kom7_5.style.color = "green";
    kom7_5.textContent = "BUM BUM BUM BUM!"
    setTimeout(() => {kom7_5.textContent = "";}, 3000);
    form7_5.reset();
}

document.addEventListener('DOMContentLoaded', () => {
  setup();
});

async function setup()
{
    //ała
    const typ = document.getElementById("pole7_2");
    const user = document.getElementById("pole7_3");
    const zesp = document.getElementById("pole7_4");
    const proj = document.getElementById("pole7_5");

    typ.innerHTML="<option value='0'>Ładowanie...<option/>";
    proj.innerHTML="<option value='0'>Ładowanie...<option/>";
    user.innerHTML="<option value='0'>Ładowanie...<option/>";
    zesp.innerHTML="<option value='0'>Ładowanie...<option/>";

    try{
        const res_proj = await fetch('start_crate.php?action=projekt',{method: 'GET'});
        const typ_proj = await fetch('start_crate.php?action=typ',{method: 'GET'});
        const res_user = await fetch('start_crate.php?action=user',{method: 'GET'});
        const res_zesp = await fetch('start_crate.php?action=zesp',{method: 'GET'});

        const data2 = JSON.parse(await typ_proj.text());
        const data3 = JSON.parse(await res_user.text());
        const data4 = JSON.parse(await res_zesp.text());
        const data = JSON.parse(await res_proj.text());

        if (!data.ok) {
            proj.innerHTML = `<option value="0">${data.error}</option>`;
            return;
        }
        if (!data2.ok) {
            typ.innerHTML = `<option value="0">${data2.error}</option>`;
            return;
        }
        if (!data3.ok) {
            user.innerHTML = `<option value="0">${data2.error}</option>`;
            return;
        }
        if (!data4.ok) {
            zesp.innerHTML = `<option value="0">${data2.error}</option>`;
            return;
        }

        proj.innerHTML = `<option value="0">Wybierz projekt do usunięcia</option>`;
        for (const v of data.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_p;
            opt.textContent = v.nazwa_projektu;
            proj.appendChild(opt);
        }

        typ.innerHTML = `<option value="0">Wybierz Typ do usunięcia</option>`;
        for (const v of data2.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_t;
            opt.textContent = v.nazwa_typu;
            typ.appendChild(opt);
        }

        zesp.innerHTML = `<option value="0">Wybierz Zespół do usunięcia</option>`;
        for (const v of data4.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_Ze;
            opt.textContent = v.nazwa;
            zesp.appendChild(opt);
        }

        user.innerHTML = `<option value="0">Wybierz Użytkownika do zabicia</option>`;
        for (const v of data3.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_u;
            opt.textContent = v.login;
            user.appendChild(opt);
        }

        }catch (e) {
            //tu trzeba się pobawić
            console.log(e);
            proj.innerHTML = `<option value="0">Błąd połączenia</option>`;
            typ.innerHTML = `<option value="0">Błąd połączenia</option>`;
            user.innerHTML = `<option value="0">Błąd połączenia</option>`;
            zesp.innerHTML = `<option value="0">Błąd połączenia</option>`;
        }
}