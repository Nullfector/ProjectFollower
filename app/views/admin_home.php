<div class="main-sep"></div>
<section id="admin_home">
<h2>Część admina</h2>

<a href="admin_panel.php"><button>Do panelu</button></a>
<a href="admin_reports.php"><button>Do raportów</button></a><br/>

<div class="mid-sep"></div>

<h3>Podstawowe zarządzanie zadaniami i projektami:</h3><br/>
<label for="sel2">Aktywuj projekt</label>
<select id="sel2"></select><br/>
<button id="but1">Wyślij</button><br/><br/>

<label for="sel3"> Zakończ projekt</label>
<select id="sel3"></select><br/>
<button id="but3">Wyślij</button><br/><br/>

<label for="sel1">Aktywuj zadanie</label>
<select id="sel1"></select> Najpierw wybierz Projekt z sekcji "Zakończ projekt" aby móc wybrać Zadanie<br/>
<button id="but2">Wyślij</button><br/><br/>

<label for="sel4">Zakończ zadanie</label>
<select id="sel4"></select> Najpierw wybierz Projekt z sekcji "Zakończ projekt" aby móc wybrać Zadanie<br/>
<button id="but4">Wyślij</button><br/><br/>

<p id="response"></p>
<div class="mid-sep"></div>

<h3>Podgląd zadań</h3>
<label for="sel5">Wybierz projekt</label>
<select id="sel5"></select>
<label for="sel5_1">Wybierz zakres</label>
<select id="sel5_1">
    <option value="0">Aktywne i nieaktywne</option>
    <option value="1">Nieaktywne</option>
    <option value="2">Aktywne</option>
</select><br/><br/>

<h3>Podgląd zespołów</h3>
<label for="sel6">Wybierz zespół</label> <!--użytkownicy i przypisane zadania-projekty (z info o statusie)-->
<select id="sel6"></select>
<label for="sel6_1">Wybierz zakres</label>
<!--tutaj-->
<select id="sel6_1">
    <option value="0">Aktywne</option>
    <option value="1">Nieaktywne</option>
    <option value="2">Aktywne i nieaktywne</option>
</select><br/><br/>

<h3>Informacje o projekcie</h3>
<label for="sel7">Wybierz projekt</label> <!--wsyzstko co związane z projektem-->
<select id="sel7"></select>
<label for="sel7_1">Wybierz zakres</label>
<select id="sel7_1">
    <option value="0">Aktywne i nieaktywne</option>
    <option value="1">Nieaktywne</option>
    <option value="2">Aktywne</option>
</select><br/><br/><br/>

<div id='divall'></div>
</section>

<script src="/../app/views/assets/admin_homeOperations.js"></script>