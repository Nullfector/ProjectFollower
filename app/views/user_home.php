<section id="user_home">
<h1>Strona główna</h1>

<h3>Moje zespoły</h3>
<label for="usel1">Wybierz zepsół</label>
<select id="usel1"></select>
<label for="usel1_1">Wybierz zakres</label>
<select id="usel1_1">
    <option value="0">Aktywne</option>
    <option value="1">Nieaktywne</option>
    <option value="2">Aktywne i nieaktywne</option>
</select><br/>

<div id='udiv1'></div><br/>

<h3>Moje aktualne zadania</h3>
<div id='udiv2'></div><br/>

<h3>Sprawdź twoje zadania z nieskończonych projektów</h3>
<label for="usel3">Wybierz projekt</label>
<select id="usel3"></select><br/>
<div id='udiv3'></div><br/>

<h3>Zmień swoje hasło:</h3>
<label for="utxt">Nowe hasło</label>
<input type="text" size="20" id="utxt">
<button type="button" id="ub">Edytuj!</button><br/>
<p id="uresp"></p><br/>

</secton>

<script src="/../app/views/assets/user_homeOperations.js"></script>