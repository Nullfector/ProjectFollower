const usel1 = document.getElementById("usel1");
const udiv2 = document.getElementById("udiv2");
const usel3 = document.getElementById("usel3");

const udiv1 = document.getElementById("udiv1");
const udiv3 = document.getElementById("udiv3");
//-------------
const utxt = document.getElementById("utxt");
const ub = document.getElementById("ub");
const uresp = document.getElementById("uresp");


usel1.addEventListener('change', async (e)=>{
    if(e.target.value == "0"){
        udiv1.innerHTML = "";
    } else {
        const resp = await fetch(`/../app/db_kontrolers/user_control.php?action=users&id=${encodeURIComponent(e.target.value)}`,{method: 'GET'});
        const html = await resp.text();
        udiv1.innerHTML = html;
    }
});

usel3.addEventListener('change', async (e)=>{
    if(e.target.value == "0"){
        udiv3.innerHTML = "";
    } else {
        const resp = await fetch(`/../app/db_kontrolers/user_control.php?action=unfinished&id=${encodeURIComponent(e.target.value)}`,{method: 'GET'});
        const html = await resp.text();
        udiv3.innerHTML = html;
    }
});

ub.addEventListener('click', async ()=>{
    if(utxt.value.trim()===""){
        uresp.style.color='red';
        uresp.textContent = "Nic nie napisano.";
    } else {
        //console.log( utxt.value.trim());
        const res = await fetch(`/../app/db_kontrolers/user_control.php`, {
            method: 'PUT',
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({'nowe': utxt.value.trim(), 'action':'pass'})
        });
        const data = await res.json();
        //console.log(data);

        if (data.ok) {
            uresp.textContent = data.message;
            uresp.style.color = 'green';
            setTimeout(() => {uresp.textContent = "";}, 3000);
            utxt.value="";
        } else {
            uresp.textContent = data.error;
            uresp.style.color = 'red';
        }
    }
});




document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById("user_home");
    if (!root) return;
    setupUser();
});

async function setupUser(){

    usel1.innerHTML="<option value='0'>Ładowanie...</option>";
    usel3.innerHTML="<option value='0'>Ładowanie...</option>";

    try{
        const res1 = await fetch(`/../app/db_kontrolers/user_control.php?action=team`,{method: 'GET'});
        const res2 = await fetch(`/../app/db_kontrolers/user_control.php?action=table`,{method: 'GET'});
        const res3 = await fetch(`/../app/db_kontrolers/user_control.php?action=zads`,{method: 'GET'});

        const data1 = await res1.json();
        const data3 = await res3.json();
        const data2 = await res2.text();

        if (!data1.ok) {
            usel1.innerHTML = `<option value="0">${data2.error}</option>`;
        }
        if (!data3.ok) {
            usel3.innerHTML = `<option value="0">${data3.error}</option>`;
        }
        else {
            usel1.innerHTML = `<option value="0">Wybierz Zespół</option>`;
            usel3.innerHTML = `<option value="0">Wybierz Projekt</option>`;
        }

        udiv2.innerHTML = data2;

        for (const v of data1.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_ze;
            opt.textContent = v.nazwa;
            usel1.appendChild(opt);
        }

        for (const v of data3.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_p;
            opt.textContent = v.nazwa_projektu;
            usel3.appendChild(opt);
        }

    }catch(e){
        console.log(e);
        usel1.innerHTML="<option value='0'>Brak połączenia</option>";
        usel3.innerHTML="<option value='0'>Brak połączenia</option>";
    }
}