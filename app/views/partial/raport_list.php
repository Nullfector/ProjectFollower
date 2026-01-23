<?php
$map = [true => 'Tak', false => 'Nie'];
?>

<ul>
    <li>Id: <?= htmlspecialchars((string)($row[0]['id_p']))?></li>
    <li>Czy archiwalne: <?= htmlspecialchars($map[$row[0]['archiwalne']]) ?></li>
    <li>Planowany czas rozpoczęcia: <?= htmlspecialchars((string)($row[0]['czas_startu'] ?? 'Brak')) ?></li>
    <li>Planoway czas zakończenia: <?= htmlspecialchars((string)($row[0]['czas_zakończenia'] ?? 'Brak')) ?></li>
    <li>Faktyczy czas rozpoczęcia: <?= htmlspecialchars((string)($row[0]['fakt_czas_startu'] ?? 'Jeszcze nie zaczęty')) ?></li>
    <li>Faktyczy czas zakończenia: <?= htmlspecialchars((string)($row[0]['fakt_czas_zak'] ?? 'Jeszcze nie zakończony')) ?></li>
</ul>