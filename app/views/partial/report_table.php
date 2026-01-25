<table class="report-table">
  <thead>
    <tr>
      <?php foreach ($headers as $_ => $label): ?>
        <th><?= htmlspecialchars($label) ?></th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($rows)): ?>
      <tr><td colspan="<?= count($headers) ?>">Brak danych</td></tr>
    <?php else: ?>
      <?php foreach ($rows as $r): ?>
        <tr>
          <?php foreach ($headers as $key => $_): ?>
            <td><?= htmlspecialchars((string)($r[$key] ?? '')) ?></td>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>

<style>
  .report-table{
    background-color: #f2f2f2;  /* ten sam odcień co raport-box */
    border: 1px solid #bbb;
    border-radius: 6px;
    padding: 12px 14px;
    margin-top: 10px;
}

.report-table thead th {
  padding: 8px 10px;
  text-align: left;
  border-bottom: 3px solid #000;   
  border-right: 2px solid #000;    
}

.report-table thead th:last-child {
  border-right: none;
}

.report-table tbody td {
  padding: 6px 10px;
  border-bottom: 1px solid #ccc;
  border-right: 2px solid #000;   
}

.report-table tbody td:last-child {
  border-right: none;
}
</style>