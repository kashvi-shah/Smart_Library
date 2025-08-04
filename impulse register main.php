<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $reg = $_POST['reg'] ?? '';
  $isbn = $_POST['isbn'] ?? '';

  if ($reg && $isbn) {
    // Save to file
    $data = "$reg,$isbn," . date("Y-m-d H:i:s") . "\n";
    file_put_contents("borrow_log.txt", $data, FILE_APPEND);

    // Save session
    $_SESSION['users'][$reg] = ['name' => $isbn];

    // Redirect to library
    header("Location: impulse library.php?reg=" . urlencode($reg));
    exit();
  }
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <style>
    :root {
      --bg: #0f172a;
      --text: #ffffff;
      --form-bg: rgba(255,255,255,0.1);
    }

    .light-theme {
      --bg: #f3f4f6;
      --text: #111827;
      --form-bg: rgba(255, 255, 255, 0.8);
    }

    body {
      font-family: sans-serif;
      background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
        url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=1470&q=80') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: var(--text);
      transition: background 0.3s ease, color 0.3s ease;
    }

    .form-container {
      background: var(--form-bg);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.4);
      backdrop-filter: blur(8px);
      min-width: 300px;
    }

    input {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      background: #3b82f6;
      color: white;
      border: none;
      padding: 10px 20px;
      font-weight: bold;
      border-radius: 5px;
      cursor: pointer;
    }

    h2 {
      text-align: center;
    }

    .theme-toggle {
      text-align: center;
      margin-bottom: 20px;
    }

    .theme-toggle button {
      background: #10b981;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body id="body">
   

  <h2>📚 Manual Registration</h2>

<form method="POST">
  Reg No: <input name="reg" required>
  Name: <input name="isbn" required>
  <button type="submit">Send</button>
</form>

<?php if (!empty($message)): ?>
  <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>


  <script>
    function toggleTheme() {
      const body = document.getElementById('body');
      body.classList.toggle('light-theme');
      localStorage.setItem('theme', body.classList.contains('light-theme') ? 'light' : 'dark');
    }

    window.onload = () => {
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'light') {
        document.getElementById('body').classList.add('light-theme');
      }
    };
  </script>
</body>
</html>
