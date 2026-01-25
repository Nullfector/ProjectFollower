<?php
$map = [true => 'Tak', false => 'Nie'];
?>

<div class="raport-box">
    <h2>Szczegóły projektu</h2>
<ul>
    <li><span class="label">Id:</span> <span class="value"><?= htmlspecialchars((string)($row[0]['id_p']))?></span></li>
    <li><span class="label">Czy archiwalne:</span> <span class="value"><?= htmlspecialchars($map[$row[0]['archiwalne']]) ?></span></li>
    <li><span class="label">Planowany czas rozpoczęcia:</span> <span class="value"><?= htmlspecialchars((string)($row[0]['czas_startu'] ?? 'Brak')) ?></span></li>
    <li><span class="label">Planoway czas zakończenia:</span> <span class="value"><?= htmlspecialchars((string)($row[0]['czas_zakończenia'] ?? 'Brak')) ?></span></li>
    <li><span class="label">Faktyczy czas rozpoczęcia:</span> <span class="value"><?= htmlspecialchars((string)($row[0]['fakt_czas_startu'] ?? 'Jeszcze nie zaczęty')) ?></span></li>
    <li><span class="label">Faktyczy czas zakończenia:</span> <span class="value"><?= htmlspecialchars((string)($row[0]['fakt_czas_zak'] ?? 'Jeszcze nie zakończony')) ?></span></li>
</ul>
</div>