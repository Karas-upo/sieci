<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Tryb nauki - Test 1</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 900px; margin: 40px auto; padding: 20px; background: #eceff1; color: #333; }
        .header-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .back-btn { text-decoration: none; color: #007bff; font-weight: bold; padding: 10px 15px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .back-btn:hover { background: #f0f7ff; }
        h1 { margin: 0; text-align: center; flex-grow: 1; }
        .grid-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .mode-card { background: white; padding: 40px 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; text-decoration: none; color: #333; transition: transform 0.2s; display: flex; flex-direction: column; justify-content: center; align-items: center; }
        .mode-card:hover { transform: translateY(-5px); background: #fafafa; }
        .mode-card h2 { margin: 0 0 15px 0; color: #28a745; }
        .mode-card p { margin: 0; color: #666; font-size: 0.95em; line-height: 1.4; }
        
        .mode-single { border-bottom: 5px solid #17a2b8; }
        .mode-single h2 { color: #17a2b8; }
        .mode-all { border-bottom: 5px solid #007bff; }
        .mode-all h2 { color: #007bff; }
        .mode-exam { border-bottom: 5px solid #dc3545; }
        .mode-exam h2 { color: #dc3545; }
    </style>
</head>
<body>

    <div class="header-nav">
        <a href="../index.php" class="back-btn">&larr; Powrót</a>
        <h1>Wybrano Test 1</h1>
        <div style="width: 85px;"></div>
    </div>

    <div class="grid-container">
        <a href="test1_1.php" class="mode-card mode-single">
            <h2>1 Pytanie</h2>
            <p>Ucz się krok po kroku. Pytania pojawiają się pojedynczo, od razu widzisz poprawną odpowiedź.</p>
        </a>

        <a href="test1_wszystkie.php" class="mode-card mode-all">
            <h2>Wszystkie Pytania</h2>
            <p>Rozwiąż wszystkie pytania z tego testu na jednej stronie (tryb klasyczny).</p>
        </a>

        <a href="test1_33.php" class="mode-card mode-exam">
            <h2>Test 33 Pytania</h2>
            <p>Symulacja egzaminu. Losowe 33 pytania z tego zestawu na czas.</p>
        </a>

        <a href="test1_knockoff.php" class="mode-card mode-exam">
            <h2>Knockoff Pytania</h2>
            <p>Pytania ktorych logiki nie chcialo mi sie promptowac</p>
        </a>
    </div>

</body>
</html>