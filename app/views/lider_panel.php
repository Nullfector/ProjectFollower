
<p><a href="me.php" class="app-link">Powrót</a></p><br?>
<div class="sep-big"></div>
<h3>Zespół</h3><br/>
        <ul>
            <li>
                <label for="pole9_0">Wybierz zespół do edytowania</label>
                <select name="pole9_0" id="pole9_0"></select>
            </li>
            <form name="form_edit_team" id="form_edit_team">
                <br/>
                <li>
                    <label for="pole9_1">Nowa nazwa</label>
                    <input type="text" size="20" name="pole9_1" id="pole9_1"><br/>
                </li>
                <li>
                    <label for="pole9_8">Zarchiwizować?</label>
                    <select name="pole9_8" id="pole9_8">
                        <option value="0">Nie</option>
                        <option value="1">Tak</option>
                    </select>
                </li>
                <button type="button" name="b17" id="b17">Edytuj!</button>
                <br/>
            </form>
            <br/><div class="sep-soft"></div>
            <form name="form_edit_aso_u" id="form_edit_aso_u">
                <li>
                    <label for="pole9_3">Dodaj nowego członka</label>
                    <select name="pole9_3" id="pole9_3"></select></li>
                </li>
                <li>
                    <label for="pole9_4">Usuń członka</label>
                    <select name="pole9_4" id="pole9_4"></select></li>
                </li>
                    <button type="button" name="b18" id="b18">Edytuj!</button>
                <br/>
            </form>
            <br/><div class="sep-soft"></div>
            <form name="form_edit_aso_za" id="form_edit_aso_za">
                <li>
                    <label for="pole9_7">Z jakiego projektu</label>
                    <select name="pole9_7" id="pole9_7"></select>
                </li>
                <li>
                    <label for="pole9_5">Powiąż z zadaniem</label>
                    <select name="pole9_5" id="pole9_5"></select>
                </li>
                <li>
                    <label for="pole9_6">Usuń powiązanie</label>
                    <select name="pole9_6" id="pole9_6"></select>
                </li>
                <button type="button" name="b14" id="b14">Edytuj!</button>
            </form>
        </ul>
        <p name="form_edit_team_kom" id="form_edit_team_kom"></p>
        <br/><div class="sep-mid"></div>

<h3>Aktywacja zadania</h3>
<p id="response"></p><br/>

<label for="sel1">Wybierz projekt</label>
<select id="sel1"></select><br/><br/>

<label for="sel2">Aktywuj zadanie</label>
<select id="sel2"></select>Najpierw wybierz Projekt powyżej aby móc wybrać Zadanie<br/>
<button id="but2">Wyślij</button><br/><br/>

<label for="sel3">Zakończ zadanie</label>
<select id="sel3"></select>Najpierw wybierz Projekt powyżej aby móc wybrać Zadanie<br/>
<button id="but3">Wyślij</button><br/><br/>

<script src="/../app/views/assets/EditLider.js"></script>