const sel1 = document.getElementById("sel1");
const div2 = document.getElementById("div2");
const sel3 = document.getElementById("sel3");

const div1 = document.getElementById("div1");
const div3 = document.getElementById("div3");


sel1.addEventListener('change', async (e)=>{
    if(e.target.value == "0"){
        div1.innerHTML = "";
    } else {
        const resp = await fetch(`/../app/db_kontrolers/user_control.php?action=users&id=${encodeURIComponent(e.target.value)}`,{method: 'GET'});
        const html = await resp.text();
        div1.innerHTML = html;
    }
});

sel3.addEventListener('change', async (e)=>{
    if(e.target.value == "0"){
        div3.innerHTML = "";
    } else {
        const resp = await fetch(`/../app/db_kontrolers/user_control.php?action=unfinished&id=${encodeURIComponent(e.target.value)}`,{method: 'GET'});
        const html = await resp.text();
        div3.innerHTML = html;
    }
});




document.addEventListener('DOMContentLoaded', () => {
  setup();
});

async function setup(){

    sel1.innerHTML="<option value='0'>Ładowanie...</option>";
    sel3.innerHTML="<option value='0'>Ładowanie...</option>";

    try{
        const res1 = await fetch(`/../app/db_kontrolers/user_control.php?action=team`,{method: 'GET'});
        const res2 = await fetch(`/../app/db_kontrolers/user_control.php?action=table`,{method: 'GET'});
        const res3 = await fetch(`/../app/db_kontrolers/user_control.php?action=zads`,{method: 'GET'});

        const data1 = await res1.json();
        const data3 = await res3.json();
        const data2 = await res2.text();

        if (!data1.ok) {
            sel1.innerHTML = `<option value="0">${data2.error}</option>`;
        }
        if (!data3.ok) {
            sel3.innerHTML = `<option value="0">${data3.error}</option>`;
        }
        else {
            sel1.innerHTML = `<option value="0">Wybierz Zespół</option>`;
            sel3.innerHTML = `<option value="0">Wybierz Projekt</option>`;
        }

        div2.innerHTML = data2;

        for (const v of data1.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_ze;
            opt.textContent = v.nazwa;
            sel1.appendChild(opt);
        }

        for (const v of data3.ret_val) {
            const opt = document.createElement('option');
            opt.value = v.id_p;
            opt.textContent = v.nazwa_projektu;
            sel3.appendChild(opt);
        }

    }catch(e){
        console.log(e);
        sel1.innerHTML="<option value='0'>Brak połączenia</option>";
        sel3.innerHTML="<option value='0'>Brak połączenia</option>";
    }
}