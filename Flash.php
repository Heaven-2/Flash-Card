<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todo";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$deck_id = isset($_POST['deck_id']) ? intval($_POST['deck_id']) : (isset($_GET['deck_id']) ? intval($_GET['deck_id']) : 0);
$cards = [];
if ($deck_id > 0) {
    $stmt = $conn->prepare("SELECT card_name, answer FROM card WHERE deck_id = ?");
    $stmt->bind_param("i", $deck_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $cards[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flash</title>
    <link rel="stylesheet" href="Style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script>
        window.cards = <?= json_encode($cards) ?>;
    </script>
    <script src="Script.js" defer></script>
</head>

<body class="flash-bg">
    <div class="flash-container">
        <header class="flash-header">
            <button class="material-icons back-btn" style="color: rgb(255, 255, 255); width: 25%;" onclick="history.back()">chevron_left</button>
            <h2>Flashcards</h2>
        </header>
        <main class="flash-main">
            <section class="flash-card">
                <p class="flash-question" ></p>
                <div class="flash-answer-box">
                    <p class="answer" style="display:none; color: black;"></p>
                    <button class="flash-showanswer-btn" type="button">Show Answer</button>
                </div>
            </section>
            <div class="flash-difficulty">
                <p class="flash-diff-label">What is the difficulty level for this question?</p>
                <button type="button" class="flash-btn flash-easy">Easy</button><br>
                <button type="button" class="flash-btn flash-medium">Medium</button><br>
                <button type="button" class="flash-btn flash-hard">Hard</button>
            </div>
        </main>
    </div>
</body>

</html>