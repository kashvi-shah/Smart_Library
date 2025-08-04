<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $reg = $_POST['reg'] ?? '';
  $isbn = $_POST['isbn'] ?? '';

  if ($reg && $isbn) {
    // Save to file
    $data = "$reg,$isbn," . date("Y-m-d H:i:s") . "\n";
    file_put_contents("borrow_log.txt", $data, FILE_APPEND);
    echo "Data received!";
  } else {
    echo "Missing reg or isbn!";
  }
}
?>
