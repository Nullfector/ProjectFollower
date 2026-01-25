<section id="reps">
<h1>Dostępne raporty</h1>

<a class="back-link" href="admin.php">Powrót</a><br/>

<div id="divall"></div></br></br>
<div id="userActivChart" class="chart-wrap" style="display:none;">
    <div class="chart-title">Wykres: Procent wszystkich zadań (aktywni użytkownicy)</div>
    <div id="barChart" class="bar-chart"></div>
  </div>

<div class="rep-card">
<div class="rep-title">Raport: spóźnienia w trwających projektach</div>
<button id="but1" onclick="rep_percent();">Wykonaj!</button>
</div>
<br/><br/>

<div class="rep-card">
<div class="rep-title">Raport: aktwność użytkowników</div>
<button id="but2" onclick="rep_user_activ();">Wykonaj!</button>
</div><br/><br/>

<div class="rep-card">
<div class="rep-title">Raport: nakład pracy zespołów</div>
<button id="but3" onclick="rep_team_activ();">Wykonaj!</button>
</div><br/><br/>


</section>

<script src='/../app/views/assets/repsBehaviour.js'></script>