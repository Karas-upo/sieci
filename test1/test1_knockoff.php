<?php
// 1. Konfiguracja połączenia
$host = 'localhost';
$user = 'root';
$pass = '1234';
$db   = 'pytania';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Błąd połączenia: " . $conn->connect_error); }

// 2. Pobranie WSZYSTKICH zadań z tabeli knockoff
// Sortujemy według ID, aby zadania były po kolei
$sql = "SELECT id, nazwa_zdjecia FROM knockoff ORDER BY id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wszystkie Zadania</title>
    <style>
        /* Twoje oryginalne style */
        body { font-family: 'Segoe UI', sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; background: #eceff1; color: #333; }
        .nav-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn-back { text-decoration: none; color: #555; background: #fff; padding: 8px 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); font-weight: bold; }
        
        /* Kontener dla kart, aby trzymały się w kolumnie */
        .questions-column { display: flex; flex-direction: column; gap: 20px; }

        .question-card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-left: 8px solid #607d8b; }
        
        .image-box { margin: 20px 0; text-align: center; background: #f9f9f9; padding: 15px; border-radius: 8px; }
        .image-box img { max-width: 100%; height: auto; border-radius: 8px; }
        
        h2 { color: #455a64; margin: 0; }
        .task-title { font-size: 1.4em; font-weight: bold; margin-top: 0; color: #455a64; }
    </style>
</head>
<body>

    <div class="nav-top">
        <a href="menu_test1.php" class="btn-back">&larr; Powrót</a>
        <h2>Lista zadan nie ABCD</h2>
    </div>

    <div class="questions-column">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                
                <div class="question-card">
                    <p class="task-title">
                        Zadanie <?php echo $row['id']; ?>
                    </p>

                    <div class="image-box">
                        <img src="img/<?php echo htmlspecialchars($row['nazwa_zdjecia']); ?>" alt="Grafika do zadania <?php echo $row['id']; ?>">
                    </div>
                </div>

            <?php endwhile; ?>
        <?php else: ?>
            <div class="question-card">
                <p>Brak zadań w bazie danych.</p>
            </div>
        <?php endif; ?>
    </div>

    <div style="text-align: center; margin-top: 40px;">
        <p style="color: #90a4ae;">To już wszystkie zadania.</p>
    </div>

</body>
</html>