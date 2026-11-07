<?php
require_once 'db.php';

$messaggio = "";

// Gestione eliminazione (sia POST che eventuale GET)
$id_da_eliminare = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id_da_eliminare = $_POST['id'];
} elseif (isset($_GET['id'])) {
    $id_da_eliminare = $_GET['id'];
}

if ($id_da_eliminare) {
    $stmt = $pdo->prepare("DELETE FROM orologi WHERE id = :id");
    $stmt->execute([':id' => $id_da_eliminare]);
    $messaggio = "Orologio eliminato con successo!";
}

// Recupera tutti gli orologi da mostrare nella lista
$query = "SELECT orologi.id, brand.nome_brand AS marca, orologi.modello, orologi.anno, orologi.movimento, orologi.prezzo_stimato, utenti.username AS proprietario
          FROM orologi
          INNER JOIN brand ON orologi.brand_id = brand.id
          LEFT JOIN utenti ON orologi.utente_id = utenti.id";
$stmt = $pdo->query($query);
$orologi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione - Elimina Orologi</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="aggiungi.php">Aggiungi</a></li>
            <li><a href="elimina.php">Elimina</a></li>
        </ul>
    </nav>

    <h2>Gestione / Eliminazione Orologi</h2>

    <?php if ($messaggio != ""): ?>
        <p><?php echo htmlspecialchars($messaggio); ?></p>
    <?php endif; ?>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Marca</th>
                <th>Modello</th>
                <th>Anno</th>
                <th>Movimento</th>
                <th>Prezzo stimato</th>
                <th>Proprietario</th>
                <th>Azione</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orologi as $orologio): ?>
                <tr>
                    <td><?php echo htmlspecialchars($orologio['id']); ?></td>
                    <td><?php echo htmlspecialchars($orologio['marca']); ?></td>
                    <td><?php echo htmlspecialchars($orologio['modello']); ?></td>
                    <td><?php echo htmlspecialchars($orologio['anno']); ?></td>
                    <td><?php echo htmlspecialchars($orologio['movimento']); ?></td>
                    <td><?php echo htmlspecialchars($orologio['prezzo_stimato']); ?> €</td>
                    <td><?php echo htmlspecialchars($orologio['proprietario'] ?? ''); ?></td>
                    <td>
                        <form action="elimina.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($orologio['id']); ?>">
                            <input type="submit" value="Elimina" onclick="return confirm('Sei sicuro di voler eliminare questo orologio?');">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
