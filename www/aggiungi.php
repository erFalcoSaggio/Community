<?php
require_once 'db.php';

$messaggio = "";

// Se il modulo è stato inviato via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marca_nome = trim($_POST['marca']);
    $modello = trim($_POST['modello']);
    $anno = $_POST['anno'];
    $movimento = $_POST['movimento'];
    $prezzo_stimato = $_POST['prezzo_stimato'];
    // Se non è selezionato nessun proprietario salvo NULL nel db
    $utente_id = !empty($_POST['utente_id']) ? $_POST['utente_id'] : null;

    // controlla se la marca esiste
    $stmt = $pdo->prepare("SELECT id FROM brand WHERE LOWER(nome_brand) = LOWER(:nome)");
    $stmt->execute([':nome' => $marca_nome]);
    $brand = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($brand) {
        $brand_id = $brand['id'];
    } else {
        // Se non esiste la creo
        $stmt = $pdo->prepare("INSERT INTO brand (nome_brand) VALUES (:nome)");
        $stmt->execute([':nome' => $marca_nome]);
        $brand_id = $pdo->lastInsertId();
    }

    $sql = "INSERT INTO orologi (brand_id, utente_id, modello, anno, movimento, prezzo_stimato) 
            VALUES (:brand_id, :utente_id, :modello, :anno, :movimento, :prezzo_stimato)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':brand_id' => $brand_id,
        ':utente_id' => $utente_id,
        ':modello' => $modello,
        ':anno' => $anno,
        ':movimento' => $movimento,
        ':prezzo_stimato' => $prezzo_stimato
    ]);

    $messaggio = "Orologio inserito con successo!";
}

// Recupero utenti
$stmt = $pdo->query("SELECT id, username FROM utenti ORDER BY username");
$utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Orologio</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="aggiungi.php">Aggiungi</a></li>
            <li><a href="elimina.php">Elimina</a></li>
        </ul>
    </nav>

    <h2>Aggiungi un nuovo orologio</h2>

    <?php if ($messaggio != ""): ?>
        <p><?php echo htmlspecialchars($messaggio); ?></p>
    <?php endif; ?>

    <form action="aggiungi.php" method="POST">
        <p>
            <label>Marca:</label><br>
            <input type="text" name="marca" placeholder="Es. Rolex, Omega, Seiko, Casio..." required>
        </p>

        <p>
            <label>Modello:</label><br>
            <input type="text" name="modello" required>
        </p>

        <p>
            <label>Anno:</label><br>
            <input type="number" name="anno" min="1900" max="2099">
        </p>

        <p>
            <label>Movimento:</label><br>
            <select name="movimento" required>
                <option value="">-- Seleziona Movimento --</option>
                <option value="Automatico">Automatico</option>
                <option value="Manuale">Manuale</option>
                <option value="Quarzo">Quarzo</option>
                <option value="Solare">Solare</option>
            </select>
        </p>

        <p>
            <label>Prezzo stimato (€):</label><br>
            <input type="number" step="0.01" name="prezzo_stimato">
        </p>

        <p>
            <label>Proprietario (opzionale):</label><br>
            <select name="utente_id">
                <option value="">-- Nessun proprietario --</option>
                <?php foreach ($utenti as $utente): ?>
                    <option value="<?php echo htmlspecialchars($utente['id']); ?>">
                        <?php echo htmlspecialchars($utente['username']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <input type="submit" value="Salva Orologio">
        </p>
    </form>

    <hr>
    <p>
        <a href="elimina.php"><button type="button">Elimina elementi</button></a>
    </p>
</body>
</html>
