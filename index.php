<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wybierz Test - System Testowy</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 900px; margin: 40px auto; padding: 20px; background: #eceff1; color: #333; }
        h1 { text-align: center; color: #333; margin-bottom: 40px; }
        .grid-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .card { background: white; padding: 30px 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; text-decoration: none; color: #333; transition: transform 0.2s, box-shadow 0.2s; border-top: 5px solid #007bff; display: block; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 8px 15px rgba(0,0,0,0.15); }
        .card h2 { margin: 0 0 10px 0; font-size: 1.5em; color: #007bff; }
        .card p { margin: 0; color: #666; font-size: 0.9em; }
    </style>
</head>
<body>

    <h1>Wybierz test do rozwiązania</h1>

    <div class="grid-container">
        <a href="./test1/menu_test1.php" class="card">
            <h2>Test 1</h2>
            <p>Podstawy sieci komputerowych</p>
        </a>
        
        <a href="./test2/menu_test2.php" class="card">
            <h2>Test 2</h2>
            <p>Bazy danych i SQL</p>
        </a>

        <a href="menu_test3.php" class="card">
            <h2>Test 3</h2>
            <p>Programowanie obiektowe</p>
        </a>
    </div>

</body>
</html>