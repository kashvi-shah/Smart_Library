<?php
$data = file("borrow_log.txt");
?>

<h2>📚 Borrowed Book Logs</h2>
<table border="1" cellpadding="8">
  <tr><th>Reg No</th><th>Name</th><th>Timestamp</th></tr>
  <?php foreach ($data as $line): ?>
    <?php list($reg, $isbn, $time) = explode(",", trim($line)); ?>
    <tr>
      <td><?= htmlspecialchars($reg) ?></td>
      <td><?= htmlspecialchars($isbn) ?></td>
      <td><?= htmlspecialchars($time) ?></td>
    </tr>
  <?php endforeach; ?>
</table>
