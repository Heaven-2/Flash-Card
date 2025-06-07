<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todo";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Always get deck_id from POST if available, otherwise from GET
$deck_id = isset($_POST['deck_id']) ? intval($_POST['deck_id']) : (isset($_GET['deck_id']) ? intval($_GET['deck_id']) : 0);
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && $deck_id > 0) {
    $card_name = trim($_POST['card_name']);
    $answer = trim($_POST['answer']);
    if ($card_name !== "" && $answer !== "") {
        $stmt = $conn->prepare("INSERT INTO card (card_name, answer, deck_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $card_name, $answer, $deck_id);
        $stmt->execute();
        $stmt->close();
        header("Location: detail.php?deck_id=" . $deck_id);
        exit();
    } else {
        $message = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Card</title>
    <link rel="stylesheet" href="Style.css">
    <script src="Script.js" defer></script>
</head>

<body>
    <div class="container">
        <header>
            <a class="back-btn" id="back" href="detail.php?deck_id=<?= htmlspecialchars($deck_id) ?>">&#8592;</a>
            <h1>New Card</h1>
        </header>
        <div class="card-form">
            <?php if ($message): ?>
                <p><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <input type="hidden" name="deck_id" value="<?= htmlspecialchars($deck_id) ?>">
                <label class="card-label">Card</label>
                <input type="text" name="card_name" placeholder="Question" required>
                <textarea name="answer" placeholder="Answer" required></textarea>
                <button type="submit" class="create-btn">Create Card</button>
            </form>
        </div>
    </div>
</body>

</html>