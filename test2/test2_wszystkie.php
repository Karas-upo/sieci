<?php
// 1. Konfiguracja połączenia
$host = 'localhost';
$user = 'root';
$pass = '1234';
$db   = 'pytania';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Błąd połączenia: " . $conn->connect_error); }

$quiz = [];
$score = null;
$result_class = ""; 
$user_answers = [];
$is_submitted = false;

// 2. Pobieranie pytań i odpowiedzi (ZMIENIONO NAZWY TABEL NA pytania2 i odpowiedzi2)
$sql = "SELECT p.id as p_id, p.tresc as p_tresc, p.obrazek, p.multiple,
               o.id as o_id, o.tresc as o_tresc, o.czy_poprawna 
        FROM pytania2 p 
        LEFT JOIN odpowiedzi2 o ON p.id = o.pytanie_id
        ORDER BY p.id ASC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $quiz[$row['p_id']]['tresc'] = $row['p_tresc'];
    $quiz[$row['p_id']]['obrazek'] = $row['obrazek'];
    $quiz[$row['p_id']]['multiple'] = $row['multiple'];
    $quiz[$row['p_id']]['odpowiedzi'][] = [
        'id' => $row['o_id'],
        'tresc' => $row['o_tresc'],
        'poprawna' => $row['czy_poprawna']
    ];
}

// 3. Sprawdzanie wyników po wysłaniu formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $is_submitted = true;
    $user_answers = $_POST['ans'] ?? [];
    $correct_total = 0;

    foreach ($quiz as $p_id => $data) {
        // Przygotuj tablicę poprawnych ID z danych, które już mamy
        $correct_ids = [];
        foreach($data['odpowiedzi'] as $o) {
            if($o['poprawna'] == 1) $correct_ids[] = (string)$o['id'];
        }

        $current_user_ans = isset($user_answers[$p_id]) ? (array)$user_answers[$p_id] : [];
        sort($current_user_ans);
        sort($correct_ids);

        if ($current_user_ans === $correct_ids) {
            $correct_total++;
        }
    }

    // Obliczanie procentów i statusu
    $total_questions = count($quiz);
    $percent = ($total_questions > 0) ? round(($correct_total / $total_questions) * 100) : 0;
    
    if ($percent >= 50) {
        $status_text = "Zdałeś!";
        $result_class = "result-pass";
    } else {
        $status_text = "Nie zdałeś";
        $result_class = "result-fail";
    }
    $score = "<strong>$status_text</strong><br>Twój wynik: $percent% ($correct_total / $total_questions)";
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wszystkie Pytania - Podgląd</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; max-width: 900px; margin: 40px auto; padding: 20px; background: #eceff1; color: #333; }
        .header-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .back-btn { text-decoration: none; color: #555; background: white; padding: 10px 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); font-weight: bold; }
        
        /* Karty pytań */
        .question-card { background: white; padding: 25px; margin-bottom: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-left: 8px solid #607d8b; transition: 0.3s; }
        .card-correct { background-color: #f0fff4 !important; border-left-color: #28a745 !important; }
        .card-wrong { background-color: #fff5f5 !important; border-left-color: #dc3545 !important; }

        .multiple-info { font-size: 0.85em; color: #666; font-style: italic; }
        .image-box { margin: 15px 0; text-align: center; background: #f9f9f9; padding: 10px; border-radius: 8px; }
        .image-box img { max-width: 100%; height: auto; border-radius: 4px; }

        /* Odpowiedzi */
        label { display: flex; align-items: center; padding: 12px; margin: 8px 0; cursor: pointer; border-radius: 8px; border: 1px solid #ddd; background: white; transition: 0.2s; }
        input { margin-right: 15px; transform: scale(1.2); }
        .ans-correct { background-color: #d4edda !important; border-color: #28a745 !important; color: #155724; font-weight: bold; }
        .ans-wrong { background-color: #f8d7da !important; border-color: #dc3545 !important; color: #721c24; }

        /* Pasek wyniku */
        .result-sticky { position: sticky; top: 10px; z-index: 100; color: white; padding: 20px; border-radius: 10px; text-align: center; font-size: 1.4em; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); }
        .result-pass { background-color: #28a745; }
        .result-fail { background-color: #dc3545; }

        .btn-action { display: block; width: 100%; max-width: 300px; margin: 30px auto; padding: 15px; border: none; border-radius: 30px; font-size: 1.1em; font-weight: bold; cursor: pointer; text-align: center; text-decoration: none; }
        .btn-submit { background: #007bff; color: white; }
        .btn-new { background: #28a745; color: white; }
    </style>
</head>
<body>

    <div class="header-nav">
        <a href="menu_test2.php" class="back-btn">&larr; Powrót</a>
        <h1 style="margin:0;">Wszystkie Pytania</h1>
        <div style="width: 85px;"></div>
    </div>

    <?php if ($is_submitted): ?>
        <div class="result-sticky <?php echo $result_class; ?>">
            <?php echo $score; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <?php foreach ($quiz as $p_id => $q): ?>
            <?php 
                $card_class = "";
                if ($is_submitted) {
                    $correct_ids = [];
                    foreach($q['odpowiedzi'] as $o) {
                        if($o['poprawna'] == 1) $correct_ids[] = (string)$o['id'];
                    }
                    $user_selected = isset($user_answers[$p_id]) ? (array)$user_answers[$p_id] : [];
                    sort($correct_ids);
                    sort($user_selected);
                    $card_class = ($correct_ids === $user_selected) ? "card-correct" : "card-wrong";
                }
            ?>
            <div class="question-card <?php echo $card_class; ?>">
                <strong>Zadanie <?php echo $p_id; ?></strong>
                <span class="multiple-info">
                    (<?php echo $q['multiple'] ? "Wielokrotny wybór" : "Jednokrotny wybór"; ?>)
                </span>
                <p style="font-size: 1.1em; margin-top: 10px;"><?php echo htmlspecialchars($q['tresc']); ?></p>

                <?php if ($q['obrazek'] !== 'false' && !empty($q['obrazek'])): ?>
                    <div class="image-box">
                        <img src="<?php echo $q['obrazek']; ?>" alt="Brak">
                    </div>
                <?php endif; ?>

                <div class="answers">
                    <?php 
                        $inputType = $q['multiple'] ? 'checkbox' : 'radio';
                        foreach ($q['odpowiedzi'] as $ans): 
                            $ans_id = (string)$ans['id'];
                            $ans_class = "";
                            $checked = "";

                            if ($is_submitted) {
                                $is_selected = isset($user_answers[$p_id]) && in_array($ans_id, (array)$user_answers[$p_id]);
                                $is_correct = ($ans['poprawna'] == 1);

                                if ($is_selected) $checked = "checked";
                                if ($is_correct) {
                                    $ans_class = "ans-correct"; 
                                } elseif ($is_selected && !$is_correct) {
                                    $ans_class = "ans-wrong"; 
                                }
                            }
                    ?>
                        <label class="<?php echo $ans_class; ?>">
                            <input type="<?php echo $inputType; ?>" 
                                   name="ans[<?php echo $p_id; ?>]<?php echo $q['multiple'] ? '[]' : ''; ?>" 
                                   value="<?php echo $ans_id; ?>"
                                   <?php echo $checked; ?>
                                   <?php if ($is_submitted) echo "disabled"; ?>>
                            <?php echo htmlspecialchars($ans['tresc']); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (!$is_submitted): ?>
            <button type="submit" class="btn-action btn-submit">Zakończ i sprawdź wynik</button>
        <?php else: ?>
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn-action btn-new">Spróbuj ponownie</a>
        <?php endif; ?>
    </form>

</body>
</html>