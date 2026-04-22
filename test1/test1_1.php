<?php
// 1. Konfiguracja połączenia
$host = 'localhost';
$user = 'root';
$pass = '1234';
$db   = 'pytania';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Błąd połączenia: " . $conn->connect_error); }

$is_submitted = false;
$q_id = null;
$user_answers = [];
$card_class = "";
$status_text = "";
$result_class = "";

// 2. Obsługa żądania (POST - sprawdzanie, GET - losowanie)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pytanie_id'])) {
    // Sprawdzamy przesłane pytanie
    $is_submitted = true;
    $q_id = (int)$_POST['pytanie_id'];
    $user_answers = $_POST['ans'] ?? [];
} else {
    // Losujemy nowe pytanie z bazy
    $rand_res = $conn->query("SELECT id FROM pytania ORDER BY RAND() LIMIT 1");
    if ($rand_res && $rand_res->num_rows > 0) {
        $q_id = $rand_res->fetch_assoc()['id'];
    } else {
        die("Brak pytań w bazie danych.");
    }
}

// 3. Pobranie danych dla wybranego pytania
$sql = "SELECT p.id as p_id, p.tresc as p_tresc, p.obrazek, p.multiple,
               o.id as o_id, o.tresc as o_tresc, o.czy_poprawna 
        FROM pytania p 
        LEFT JOIN odpowiedzi o ON p.id = o.pytanie_id
        WHERE p.id = $q_id";
$result = $conn->query($sql);

$q = [
    'odpowiedzi' => []
];

while ($row = $result->fetch_assoc()) {
    $q['p_id'] = $row['p_id'];
    $q['tresc'] = $row['p_tresc'];
    $q['obrazek'] = $row['obrazek'];
    $q['multiple'] = $row['multiple'];
    $q['odpowiedzi'][] = [
        'id' => $row['o_id'],
        'tresc' => $row['o_tresc'],
        'poprawna' => $row['czy_poprawna']
    ];
}

// 4. Weryfikacja poprawności (jeśli formularz został wysłany)
if ($is_submitted) {
    $correct_ids = [];
    foreach($q['odpowiedzi'] as $o) {
        if($o['poprawna'] == 1) $correct_ids[] = (string)$o['id'];
    }
    
    // Rzutowanie na tablicę, niezależnie czy to radio (string) czy checkbox (array)
    $user_selected = (array)$user_answers;
    
    sort($correct_ids);
    sort($user_selected);
    
    if ($correct_ids === $user_selected) {
        $card_class = "card-correct";
        $result_class = "result-pass";
        $status_text = "Dobra odpowiedź!";
    } else {
        $card_class = "card-wrong";
        $result_class = "result-fail";
        $status_text = "Niestety, zła odpowiedź.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Pojedyncze Pytanie - Trening</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; background: #eceff1; color: #333; }
        .nav-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn-back { text-decoration: none; color: #555; background: #fff; padding: 8px 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); font-weight: bold; }
        
        .question-card { background: white; padding: 30px; margin-bottom: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-left: 8px solid #607d8b; transition: 0.3s; }
        
        .card-correct { background-color: #f0fff4 !important; border-left-color: #28a745 !important; }
        .card-wrong { background-color: #fff5f5 !important; border-left-color: #dc3545 !important; }

        .multiple-info { font-size: 0.85em; color: #666; font-style: italic; display: block; margin-bottom: 15px; }

        label { display: flex; align-items: center; padding: 15px; margin: 10px 0; cursor: pointer; border-radius: 8px; border: 1px solid #ddd; background: white; transition: 0.2s; font-size: 1.1em; }
        label:hover:not(.disabled) { background: #f8f9fa; border-color: #b0bec5; }
        input { margin-right: 15px; transform: scale(1.3); }

        .ans-correct { background-color: #d4edda !important; border-color: #28a745 !important; color: #155724; font-weight: bold; }
        .ans-wrong { background-color: #f8d7da !important; border-color: #dc3545 !important; color: #721c24; }
        .disabled { cursor: not-allowed; opacity: 0.9; }
        
        .image-box { margin: 20px 0; text-align: center; background: #f9f9f9; padding: 15px; border-radius: 8px; }
        .image-box img { max-width: 100%; height: auto; border-radius: 8px; }
        
        .result-sticky { padding: 20px; border-radius: 10px; text-align: center; font-size: 1.4em; margin-bottom: 25px; font-weight: bold; color: white; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        .result-pass { background-color: #28a745; }
        .result-fail { background-color: #dc3545; }
        
        .btn-action { display: block; width: 100%; max-width: 350px; margin: 30px auto; padding: 15px; border: none; border-radius: 30px; font-size: 1.2em; font-weight: bold; cursor: pointer; text-align: center; text-decoration: none; transition: 0.2s; }
        .btn-action:hover { opacity: 0.9; transform: translateY(-2px); }
        .btn-submit { background: #007bff; color: white; box-shadow: 0 4px 10px rgba(0,123,255,0.3); }
        .btn-new { background: #17a2b8; color: white; box-shadow: 0 4px 10px rgba(23,162,184,0.3); }
    </style>
</head>
<body>

    <div class="nav-top">
        <a href="menu_test1.php" class="btn-back">&larr; Powrót</a>
        <h2 style="margin: 0; color: #455a64;">Trening - 1 pytanie</h2>
    </div>

    <?php if ($is_submitted): ?>
        <div class="result-sticky <?php echo $result_class; ?>">
            <?php echo $status_text; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="pytanie_id" value="<?php echo $q['p_id']; ?>">

        <div class="question-card <?php echo $card_class; ?>">
            <span class="multiple-info">
                <?php echo $q['multiple'] ? "Pytanie wielokrotnego wyboru - zaznacz wszystkie poprawne" : "Pytanie jednokrotnego wyboru - zaznacz jedną odpowiedź"; ?>
            </span>
            <p style="font-size: 1.2em; font-weight: 500; margin-top: 0;">
                <?php echo htmlspecialchars($q['tresc']); ?>
            </p>

            <?php if ($q['obrazek'] && $q['obrazek'] !== 'false'): ?>
                <div class="image-box"><img src="img/<?php echo $q['obrazek']; ?>" alt="Grafika do pytania"></div>
            <?php endif; ?>

            <div class="answers">
                <?php 
                $inputType = $q['multiple'] ? 'checkbox' : 'radio';
                $inputName = $q['multiple'] ? 'ans[]' : 'ans'; // Radio musi mieć tę samą nazwę bez nawiasów kwadratowych, aby dobrze grupować wybór

                foreach ($q['odpowiedzi'] as $ans): 
                    $ans_id = (string)$ans['id'];
                    $ans_class = "";
                    $checked = "";

                    if ($is_submitted) {
                        $is_selected = in_array($ans_id, (array)$user_answers);
                        $is_correct = ($ans['poprawna'] == 1);

                        if ($is_selected) $checked = "checked";
                        
                        // Podświetlamy prawidłowe na zielono, a wybrane błędne na czerwono
                        if ($is_correct) {
                            $ans_class = "ans-correct disabled"; 
                        } elseif ($is_selected && !$is_correct) {
                            $ans_class = "ans-wrong disabled"; 
                        } else {
                            $ans_class = "disabled";
                        }
                    }
                ?>
                    <label class="<?php echo $ans_class; ?>">
                        <input type="<?php echo $inputType; ?>" 
                               name="<?php echo $inputName; ?>" 
                               value="<?php echo $ans_id; ?>"
                               <?php echo $checked; ?>
                               <?php if ($is_submitted) echo "disabled"; ?>>
                        <?php echo htmlspecialchars($ans['tresc']); ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (!$is_submitted): ?>
            <button type="submit" class="btn-action btn-submit">Sprawdź odpowiedź</button>
        <?php else: ?>
            <a href="test1_1.php" class="btn-action btn-new">Następne pytanie &rarr;</a>
        <?php endif; ?>
    </form>

</body>
</html>