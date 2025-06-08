<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todo";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$deck_id = isset($_GET['deck_id']) ? intval($_GET['deck_id']) : 0;
$deckname = '';
$cards = [];

if ($deck_id > 0) {
    // Get deck name for display
    $stmt = $conn->prepare("SELECT deckname FROM deck WHERE id = ?");
    $stmt->bind_param("i", $deck_id);
    $stmt->execute();
    $stmt->bind_result($deckname);
    $stmt->fetch();
    $stmt->close();

    // Get cards for this deck
    $stmt = $conn->prepare("SELECT * FROM card WHERE deck_id = ?");
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
    <title>detail</title>
    <link rel="stylesheet" href="Style.css">
    <script src="Script.js" defer></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <div class="ds-container">
        <header class="ds-header">
            <button class="ds-back-btn" onclick="goBack()">
                <span class="material-icons">chevron_left</span>
            </button>
            <h1 class="ds-title"><?= htmlspecialchars($deckname) ?></h1>
        </header>
        <main class="ds-main">
            <ul class="ds-question-list">
                <?php if (empty($cards)): ?>
                    <li class="ds-title">No cards available in this deck.</li>
                <?php else: ?>
                    <?php foreach ($cards as $card): ?>
                        <button class="ds-arrow-btn">
                            <li class="ds-question-card"><?= htmlspecialchars($card['card_name']) ?></li>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <a href="Flash.php?deck_id=<?= htmlspecialchars($deck_id) ?>" class="ds-flash-btn">
                <button class="ds-review-btn">Start Flashcards</button>
            </a>
            <a href="newdeck.php?deck_id=<?= htmlspecialchars($deck_id) ?>" class="ds-fab-btn">
                <span class="material-icons">add</span>
            </a>
        </main>
    </div>

</body>

</html>