<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $reg = $_POST['reg'] ?? '';
  $isbn = $_POST['isbn'] ?? '';

  if ($reg && $isbn) {
    $data = "$reg,$isbn," . date("Y-m-d H:i:s") . "\n";
    file_put_contents("borrow_log.txt", $data, FILE_APPEND);
    $message = "✅ Data saved successfully!";
  } else {
    $message = "❌ Please fill in all fields.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register from Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 40px;
      background: #f9fafb;
    }
    form {
      background: #ffffff;
      padding: 20px;
      border-radius: 10px;
      width: 300px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    input, button {
      display: block;
      margin-top: 10px;
      width: 100%;
      padding: 10px;
      font-size: 16px;
    }
    .message {
      margin-top: 20px;
      font-weight: bold;
    }
  </style>
</head>
<body>

<h2>📚 Manual Registration</h2>

<form method="POST">
  Reg No: <input name="reg" required>
  Name: <input name="isbn" required>
  <button type="submit">Send</button>
</form>

<?php if (!empty($message)): ?>
  <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

</body>
</html>
