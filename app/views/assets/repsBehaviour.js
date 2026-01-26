const d = document.getElementById("divall");

async function rep_percent(){
    try{
        const res = await fetch('/../app/db_kontrolers/admin_reports.php?action=percent', {method: 'GET'});
        const html = await res.text();
        d.innerHTML = html;
        document.getElementById("userActivChart").style.display="none";
    } catch(e){
        console.log(e);
        d.innerHTML = 'Coś po stronie backendu poszło nie tak';
    }
}

async function rep_user_activ(){
    try{
        const res = await fetch('/../app/db_kontrolers/admin_reports.php?action=activity&format=html', {method: 'GET'});
        const html = await res.text();
        d.innerHTML = html;

        const resJson = await fetch('/../app/db_kontrolers/admin_reports.php?action=activity&format=json');
        const data = await resJson.json();

        if (data.ok) {
            renderUserActivityChart(data.ret_val);
        }
    } catch(e){
        console.log(e);
        d.innerHTML = 'Coś po stronie backendu poszło nie tak';
    }
}

async function rep_team_activ(){
try{
        const res = await fetch('/../app/db_kontrolers/admin_reports.php?action=workload', {method: 'GET'});
        const html = await res.text();
        d.innerHTML = html;
        document.getElementById("userActivChart").style.display="none";
    } catch(e){
        console.log(e);
        d.innerHTML = 'Coś po stronie backendu się wywaliło';
    }
}

function renderUserActivityChart(rows) {

  const wrap = document.getElementById("userActivChart");
  const chart = document.getElementById("barChart");
  if (!wrap || !chart) return;

  chart.innerHTML = "";

  if (!rows || rows.length === 0) {
    wrap.style.display = "none";
    return;
  }

  const getLogin = (r) => r.login ??  "";
  const getPct = (r) => {

    const v =
      r.prcnt_zadań;
    const num = Number(v);
    return Number.isFinite(num) ? num : 0;
  };

  const max = 100;

  rows.forEach((r) => {
    const login = String(getLogin(r));
    const pct = getPct(r);

    const bar = document.createElement("div");
    bar.className = "bar";
    bar.style.height = `${Math.max(0, Math.min(pct, max)) * 2}px`;
    bar.title = `${login}: ${pct.toFixed(2)}%`;

    const pctEl = document.createElement("div");
    pctEl.className = "pct";
    pctEl.textContent = `${pct.toFixed(2)}%`;

    const lbl = document.createElement("div");
    lbl.className = "lbl";
    lbl.textContent = login;

    bar.appendChild(pctEl);
    bar.appendChild(lbl);
    chart.appendChild(bar);
  });

  wrap.style.display = "block";
}