<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['deck_name']) && !isset($_POST['search'])) {
    $deck_name = trim($_POST['deck_name']);
    if ($deck_name !== "") {
        $stmt = $conn->prepare("INSERT INTO deck (deckname, number_of_cards) VALUES (?, 0)");
        $stmt->bind_param("s", $deck_name);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

$search = "";
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $sql = "SELECT d.id, d.deckname, COUNT(c.id) AS number_of_cards
            FROM deck d
            LEFT JOIN cards c ON d.id = c.deck_id
            WHERE d.deckname LIKE ?
            GROUP BY d.id, d.deckname";
    $stmt = $conn->prepare($sql);
    $like = "%$search%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT d.id, d.deckname, COUNT(c.id) AS number_of_cards
            FROM deck d
            LEFT JOIN card c ON d.id = c.deck_id
            GROUP BY d.id, d.deckname";
    $result = $conn->query($sql);
}

$decks = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $decks[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cards</title>
    <link rel="stylesheet" href="Style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="Script.js" defer></script>
</head>

<body>

    <form class="new-form" id="newForm" method="POST" action="">
        <div style="display: flex; align-items: center; justify-content: flex-start; width: 100%; gap: 100px;">
            <button class="material-icons back-btn" style="color: black;" onclick="closeNewDeckForm()">chevron_left</button>
            <h2>New Deck</h2>
        </div>
        <input type="text" placeholder="Deck Name" required class="deck-name" name="deck_name">
        <button type="submit" class="create-deck" name="create_deck">Create Deck</button>
    </form>

    <div class="card-container">
        <header class="nav">
            <div class="container2">
                <div class="card_t">
                    <span class="material-icons">bolt</span>
                    <h2>C A R D S</h2>
                </div>
            </div>
        </header>
        <div class="main">
            <form class="searchbar" method="GET" action="">
                <input type="text" placeholder="Search Cards" class="search-input" name="search" value="<?= htmlspecialchars($search) ?>">
                <button class="search" type="submit"><img src="search.svg" alt="search"></button>
            </form>

            <div class="lists">
                <?php foreach ($decks as $deck): ?>
                    <form method="GET" action="detail.php" style="display:inline;">
                        <input type="hidden" name="deck_id" value="<?= htmlspecialchars($deck['id']) ?>">
                        <button type="submit" style="border: none; background-color: transparent; padding: 0; cursor: pointer; width: 313px;">
                            <ul class="card">
                                <li class="Card_cont">
                                    <p><?= htmlspecialchars($deck['deckname']) ?></p>
                                    <p><?= htmlspecialchars($deck['number_of_cards']) ?> cards</p>
                                </li>
                            </ul>
                        </button>
                    </form>
                <?php endforeach; ?>
            </div>
            <div class="add_card">
                <button class="btn-add" onclick="openNewDeckForm()">➕</button>
            </div>
        </div>
    </div>
</body>

</html>