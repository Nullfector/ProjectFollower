<h1>Panel sterujący</h1><br/>
<p><a href="admin.php" class="app-link">Powrót</a></p>

<div class="sep-big"></div>

        <p><a href="admin_panel_cr.php" class="app-link">Strona tworzenia</a></p>
        <ul>
            <li>Typ projektu</li>
            <li>Użytkownik</li>
            <li>Zespół</li>
            <li>Projekt</li>
            <li>Zadanie</li>
        </ul>
        <br/><br/>
        <div class="sep-big"></div>
        <h2>Usuwanie</h2><br/>
        <ul>
            <form name="form_del_type" id="form_del_type">
            <li>
                <label for="pole7_2">Typ projektu</label>
                <select name="pole7_2" id="pole7_2"></select>
                <button type="button" name="b8" id="b8" onclick="DelType();">BUM!</button>
                <p name="form_del_type_kom" id="form_del_type_kom"></p>
            </li>
            </form><br/>
            <form name="form_del_user" id="form_del_user">
            <li>
                <label for="pole7_3">Użytkownik</label>
                <select name="pole7_3" id="pole7_3"></select>
                <button type="button" name="b9" id="b9" onclick="DelUser();">BUM!</button>
                <p name="form_del_user_kom" id="form_del_user_kom"></p>
            </li>
            </form><br/>
            <form name="form_del_team" id="form_del_team">
            <li>
                <label for="pole7_4">Zespół</label>
                <select name="pole7_4" id="pole7_4"></select>
                <button type="button" name="b10" id="b10" onclick="DelTeam();">BUM!</button>
                <p name="form_del_team_kom" id="form_del_team_kom"></p>
            </li>
            </form><br/>
            <form name="form_del_project" id="form_del_project">
            <li>
                <label for="pole7_5">Projekt</label>
                <select name="pole7_5" id="pole7_5"></select>
                <button type="button" name="b11" id="b11" onclick="DelProject();">BUM!</button>
                <p name="form_del_project_kom" id="form_del_project_kom"></p>
            </li>
            </form>
            <!--
            <form name="form_del_task" id="form_del_task">
            <li>
                <label for="pole7_6">Zadanie</label>
                <select name="pole7_6" id="pole7_6">
                <option value="0">Wybierz zadanie do usunięcia</option>
                <option value="1">Numer tetowy</option></select>
                <button type="button" name="b12" id="b12" onclick="DelTask();">BUM!</button>
                <p name="form_del_task_kom" id="form_del_task_kom"></p>
            </li>
            </form>
            -->
            <!--USUWANIE ZADŃ BĘDZIE W EDYCJI PROJEKTU-->
        </ul>
        <br/><br/>
        <div class="sep-big"></div>
        <p><a href="admin_panel_ed.php" class="app-link">Strona edycji</a></p>
        <ul>
            <li>Użytkownik</li>
            <li>Zespół</li>
            <li>Projekt</li>
            <li>Zadanie</li>
        </ul>

<div id="panel-root"></div>
<script src="/../app/views/assets/DeleteOperations_alt.js"></script>