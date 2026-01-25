    <h1>Edytowanie obiektu w bazie danych:</h1><br/>
        <p><a href="admin_panel.php" class="app-link">Powrót</a></p>
        <div class="sep-big"></div>
        <h3>Użytkownik</h3><br/>
        <ul>
            <form name="form_edit_user" id="form_edit_user">
                <li>
                    <label for="pole8_0">Wybierz użytkownika do edytowania</label>
                    <select name="pole8_0" id="pole8_0"></select>
                </li>
                <br/>
                <li>
                    <label for="pole8_1">Nowe imie</label>
                    <input type="text" size="20" name="pole8_1" id="pole8_1"><br/>
                </li>
                <li>
                    <label for="pole8_2">Nowe nazwisko</label>
                    <input type="text" size="20" name="pole8_2" id="pole8_2"><br/>
                </li>
                <li>
                    <label for="pole8_3">Nowe mail</label>
                    <input type="text" size="20" name="pole8_3" id="pole8_3"><br/>
                </li>
                <li>
                    <label for="pole8_4">Nowy login</label>
                    <input type="text" size="20" name="pole8_4" id="pole8_4"><br/>
                </li>
                <li>
                    <label for="pole8_5">Nowe hasło</label>
                    <input type="text" size="20" name="pole8_5" id="pole8_5"><br/>
                </li>
                <li>
                    <label for="pole8_6">Nowa rola</label>
                    <select name="pole8_6" id="pole8_6"> <!--ROLE SĄ TYLKO 2 WIĘC TU NIE TRZEBA SIĘ BAWIĆ-->
                    <option value="0">Pozostaw obecną rolę</option>
                    <option value="1">Zwykły użytkownik</option>
                    <option value="2">Administrator systemowy</option></select>
                </li>
                <button type="button" name="b13" id="b13">Edytuj!</button>
            </form>
        </ul>
        <p name="form_edit_user_kom" id="form_edit_user_kom"></p>
        <br/>
        <div class="sep-mid"></div>
        <!--NIE WAŻNE, NIE DASZ RADY KLIKNĄĆ W DWA EDITY NA RAZ!-->
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
                    <label for="pole9_2">Nowy lider</label>
                    <select name="pole9_2" id="pole9_2"></select></li>
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
            <br/>
            <div class="sep-soft"></div>
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
            <br/>
            <div class="sep-soft"></div>
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
        <br/>
        <div class="sep-mid"></div>
        <h3>Projekt</h3><br/>
        <ul>
            <li>
                <label for="pole10_0">Wybierz projekt do edytowania</label>
                <select name="pole10_0" id="pole10_0"></select>
            </li>
            <br/>
            <form name="form_edit_project" id="form_edit_project">
                <li>
                    <label for="pole10_1">Nowa nazwa</label>
                    <input type="text" size="20" name="pole10_1" id="pole10_1"><br/>
                </li>
                <li>
                    <!--to wszystko po indeksach idzie-->
                    <label for="pole10_2">Nowy administrator</label>
                    <select name="pole10_2" id="pole10_2"></select><br/>
                </li>
                <li>
                    <label for="pole10_3">Nowy typ</label>
                    <select name="pole10_3" id="pole10_3"></select>
                </li>
                <!--<li>
                    <label for="pole10_6">Zakończyć?</label>
                    <select name="pole10_6" id="pole10_6">
                        <option value="0">Nie</option>
                        <option value="1">Tak</option>
                    </select>
                </li>-->
                <li>
                    <label for="pole10_5">Zarchiwizować?</label>
                    <select name="pole10_5" id="pole10_5">
                        <option value="0">Nie</option>
                        <option value="1">Tak</option>
                    </select>
                </li>
                <button type="button" name="b19" id="b19" >Edytuj!</button>
            </form>
            <br/><div class="sep-soft"></div>
            <li>
                <label for="pole10_4">Zadanie do usunięcia</label>
                <select name="pole10_4" id="pole10_4"></select>
            </li>
            <button type="button" name="b15" id="b15" >Edytuj!</button>
        </ul>
        <p name="form_edit_project_kom" id="form_edit_project_kom"></p>
        <br/>
        <div class="sep-mid"></div>
        <h3>Zadanie</h3><br/>
        <ul>
            <!--<form name="form_edit_task" id="form_edit_task">-->
                <li>
                    <label for="pole11_0">Wybierz projekt do edytowania</label>
                    <select name="pole11_0" id="pole11_0"></select>
                </li>
                <br/>
                <li>
                    <label for="pole11_8">Wybierz zadanie z projektu</label>
                    <select name="pole11_8" id="pole11_8"></select>
                </li>
                <br/>
                <form name="form_edit_task" id="form_edit_task">
                <li>
                    <label for="pole11_1">Nowa nazwa</label>
                    <input type="text" size="20" name="pole11_1" id="pole11_1"><br/>
                </li>
                <li>
                    <label for="pole11_2">Nowy priorytet</label>
                    <!--<input type="number" size="20" name="pole11_2" id="pole11_2">-->
                    <select name="pole11_2" id="pole11_2">
                        <option value="0">Nie zmieniaj</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">Usuń</option>
                    </select><br/>
                </li>
                <li>
                    <label for="pole11_4">Nowy czas startu</label>
                    <input type="date" size="20" name="pole11_4" id="pole11_4"><br/>
                </li>
                <li>
                    <label for="pole11_5">Nowy czas końca</label>
                    <input type="date" size="20" name="pole11_5" id="pole11_5"><br/>
                </li>
                <!--<li>
                    <label for="pole11_9">Zakończyć?</label>
                    <select name="pole11_9" id="pole11_9">
                        <option value="0">Nie</option>
                        <option value="1">Tak</option>
                    </select>
                </li>-->
                <button type="button" name="b20" id="b20">Edytuj!</button>
                <br/>
                </form>
                <div class="sep-soft"></div>
                <p>Zadnie przez nas wybrane jest zadaniem podległym (relacja: podległe->blokujące)</p><br/>
                <form name="form_edit_zself" id="form_edit_zself">
                <li>
                    <label for="pole11_6">Stwórz nowe powiązanie między zadaniami</label>
                    <select name="pole11_6" id="pole11_6"></select>
                </li>
                <li>
                    <label for="pole11_7">Usuń powiązanie między zadaniami</label>
                    <select name="pole11_7" id="pole11_7"></select>
                </li>
                <button type="button" name="b16" id="b16">Edytuj!</button>
            </form>
        </ul>
        <p name="form_edit_task_kom" id="form_edit_task_kom"></p>

        <script src="/../app/views/assets/EditOperations_alt.js"></script>