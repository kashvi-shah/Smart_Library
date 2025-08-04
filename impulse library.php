<?php
session_start();

$reg = $_GET['reg'] ?? '';
$name = $_SESSION['users'][$reg]['name'] ?? 'Guest';

$bookList = [
  "The Great Gatsby" => ["img" => "https://covers.openlibrary.org/b/id/7222246-L.jpg", "author" => "F. Scott Fitzgerald"],
  "1984" => ["img" => "https://covers.openlibrary.org/b/id/7222241-L.jpg", "author" => "George Orwell"],
  "To Kill a Mockingbird" => ["img" => "https://covers.openlibrary.org/b/id/8228691-L.jpg", "author" => "Harper Lee"],
  "Pride and Prejudice" => ["img" => "https://covers.openlibrary.org/b/id/8091016-L.jpg", "author" => "Jane Austen"],
  "Brave New World" => ["img" => "https://covers.openlibrary.org/b/id/8776043-L.jpg", "author" => "Aldous Huxley"],
  "Moby Dick" => ["img" => "https://covers.openlibrary.org/b/id/7222256-L.jpg", "author" => "Herman Melville"],
  "The Hobbit" => ["img" => "https://covers.openlibrary.org/b/id/6979861-L.jpg", "author" => "J.R.R. Tolkien"],
  "Harry Potter" => ["img" => "https://covers.openlibrary.org/b/id/7888784-L.jpg", "author" => "J.K. Rowling"],
  "The Catcher in the Rye" => ["img" => "https://covers.openlibrary.org/b/id/8231856-L.jpg", "author" => "J.D. Salinger"],
  "Fahrenheit 451" => ["img" => "https://covers.openlibrary.org/b/id/8101354-L.jpg", "author" => "Ray Bradbury"],
  "Jane Eyre" => ["img" => "https://covers.openlibrary.org/b/id/8225260-L.jpg", "author" => "Charlotte Brontë"],
  "Wuthering Heights" => ["img" => "https://covers.openlibrary.org/b/id/8288781-L.jpg", "author" => "Emily Brontë"],
  "Crime and Punishment" => ["img" => "https://covers.openlibrary.org/b/id/8231992-L.jpg", "author" => "Fyodor Dostoevsky"],
  "The Odyssey" => ["img" => "https://covers.openlibrary.org/b/id/8056125-L.jpg", "author" => "Homer"],
  "Les Misérables" => ["img" => "https://covers.openlibrary.org/b/id/8222113-L.jpg", "author" => "Victor Hugo"],
  "Don Quixote" => ["img" => "https://covers.openlibrary.org/b/id/8194816-L.jpg", "author" => "Miguel de Cervantes"]
];

if (!isset($_SESSION['userCopies'][$reg])) {
  foreach ($bookList as $title => $_) {
    $_SESSION['userCopies'][$reg][$title] = rand(0, 3);
  }
}

if (!isset($_SESSION['borrowed'][$reg])) {
  $_SESSION['borrowed'][$reg] = [];
}

if (isset($_GET['borrow'])) {
  $book = $_GET['borrow'];
  $borrowed = array_count_values($_SESSION['borrowed'][$reg])[$book] ?? 0;
  $available = $_SESSION['userCopies'][$reg][$book];

  if ($borrowed < $available) {
    $_SESSION['borrowed'][$reg][] = $book;
  }
}

if (isset($_GET['return'])) {
  $book = $_GET['return'];
  foreach ($_SESSION['borrowed'][$reg] as $i => $b) {
    if ($b === $book) {
      unset($_SESSION['borrowed'][$reg][$i]);
      $_SESSION['borrowed'][$reg] = array_values($_SESSION['borrowed'][$reg]);
      break;
    }
  }
}

$message = '';
if (isset($_POST['confirm'])) {
  $_SESSION['confirmed'][$reg] = true;
  $message = "Thank you for choosing us!";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Impulse Library</title>
  <style>
    :root {
      --bg: #0f172a;
      --text: #e5e7eb;
      --card: #1f2937;
    }

    .light-theme {
      --bg: #f3f4f6;
      --text: #1f2937;
      --card: #ffffff;
    }

    body {
      margin: 0;
      padding: 20px;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
        url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=1470&q=80') no-repeat center center fixed;
      background-size: cover;
      color: var(--text);
      transition: background 0.3s ease, color 0.3s ease;
    }

    .container {
      max-width: 1200px;
      margin: auto;
    }

    h2, h3 {
      text-align: center;
    }

    .theme-toggle {
      text-align: center;
      margin-bottom: 20px;
    }

    .theme-toggle button {
      background: #3b82f6;
      color: white;
      padding: 8px 14px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .book-list {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 20px;
    }

    .book {
      background: var(--card);
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.3);
      text-align: center;
    }

    .book img {
      width: 100px;
      height: 150px;
      object-fit: cover;
      border-radius: 5px;
      cursor: pointer;
    }

    button {
      background: #10b981;
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 5px;
      margin-top: 10px;
      cursor: pointer;
    }

    .return-btn {
      background: #ef4444;
    }

    .borrowed-list {
      margin: 30px 0;
      background: rgba(255,255,255,0.1);
      padding: 20px;
      border-radius: 10px;
      backdrop-filter: blur(6px);
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 10;
      left: 0; top: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.7);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: var(--card);
      padding: 30px;
      border-radius: 10px;
      text-align: center;
      color: var(--text);
      max-width: 400px;
    }

    .modal-content img {
      width: 200px;
      height: 300px;
      object-fit: cover;
      border-radius: 10px;
    }

    .close-btn {
      background: #ef4444;
      color: white;
      border: none;
      padding: 6px 10px;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 10px;
    }

    .nav-btn {
      display: block;
      width: fit-content;
      margin: 40px auto 0;
      background: #3b82f6;
      padding: 10px 18px;
      color: white;
      font-weight: bold;
      border-radius: 8px;
      text-decoration: none;
    }
  </style>
</head>
<body id="body">
  <div class="container">
    <h2>📚 Welcome, <?= htmlspecialchars($name) ?> (<?= htmlspecialchars($reg) ?>)</h2>

    <div class="theme-toggle">
      <button onclick="toggleTheme()">Toggle Light/Dark Theme</button>
    </div>

    <div class="borrowed-list">
      <h3>📦 Borrowed Books:</h3>
      <ul>
        <?php foreach (array_count_values($_SESSION['borrowed'][$reg]) as $title => $count): ?>
          <li>
            <?= htmlspecialchars($title) ?> (<?= $count ?>)
            <form method="GET" style="display:inline;">
              <input type="hidden" name="reg" value="<?= htmlspecialchars($reg) ?>">
              <input type="hidden" name="return" value="<?= htmlspecialchars($title) ?>">
              <button class="return-btn" type="submit">Return</button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>

      <form method="POST" style="text-align: center; margin-top: 15px;">
        <button type="submit" name="confirm" style="background: #3b82f6; padding: 10px 16px; border-radius: 6px; color: white; border: none;">
          ✅ Confirm Borrowed List
        </button>
      </form>

      <?php if (!empty($message)): ?>
        <p style="text-align:center; color: #10b981; font-size: 18px; font-weight: bold; margin-top: 10px;">
          <?= htmlspecialchars($message) ?>
        </p>
      <?php endif; ?>
    </div>

    <div class="book-list">
      <?php foreach ($bookList as $title => $info): ?>
        <?php
          $img = $info['img'];
          $author = $info['author'];
          $max = $_SESSION['userCopies'][$reg][$title] ?? 0;
          $borrowed = array_count_values($_SESSION['borrowed'][$reg])[$title] ?? 0;
          $left = $max - $borrowed;
        ?>
        <div class="book">
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($title) ?>"
               onclick="showModal('<?= htmlspecialchars($title) ?>', '<?= htmlspecialchars($author) ?>', '<?= $img ?>')">
          <p><strong><?= htmlspecialchars($title) ?></strong></p>
          <p>Left: <?= $left ?> / <?= $max ?></p>
          <?php if ($left > 0): ?>
            <form method="GET">
              <input type="hidden" name="reg" value="<?= htmlspecialchars($reg) ?>">
              <input type="hidden" name="borrow" value="<?= htmlspecialchars($title) ?>">
              <button type="submit">Borrow</button>
            </form>
          <?php else: ?>
            <button disabled>No Copies Left</button>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- 🔁 Navigation to Registration Page -->
    <a href="impulse register main.php" class="nav-btn">🔙 Go to Register Page</a>
  </div>

  <!-- Modal -->
  <div class="modal" id="bookModal">
    <div class="modal-content">
      <img id="modalImg" src="" alt="Book">
      <h3 id="modalTitle"></h3>
      <p id="modalAuthor"></p>
      <button class="close-btn" onclick="closeModal()">Close</button>
    </div>
  </div>

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

    function showModal(title, author, imgUrl) {
      document.getElementById('modalTitle').innerText = title;
      document.getElementById('modalAuthor').innerText = "By: " + author;
      document.getElementById('modalImg').src = imgUrl;
      document.getElementById('bookModal').style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('bookModal').style.display = 'none';
    }
  </script>
</body>
</html>
