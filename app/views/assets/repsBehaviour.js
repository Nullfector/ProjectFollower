const d = document.getElementById("divall");

async function rep_percent(){
    try{
        const res = await fetch('/../app/db_kontrolers/admin_reports.php?action=percent', {method: 'GET'});
        const html = await res.text();
        d.innerHTML = html;
    } catch(e){
        console.log(e);
        d.innerHTML = 'Coś po stronie backendu się wywaliło';
    }
}

function rep_user_activ(){

}

function rep_team_activ(){

}