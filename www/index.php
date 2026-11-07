<?php
require_once 'db.php';

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
    <title>COMPROVENDO Orologi</title>
</head>
<body>
    <!--i primi 3 punti anche senza classe-->
    <!--fare menu per spostarsi nelle altre pagine-->
    <!--collegare php al db-->
    <!--dati in tabella-->

    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="aggiungi.php">Aggiungi</a></li>
            <li><a href="elimina.php">Elimina</a></li>
        </ul>
    </nav>

    <h2>Catalogo Orologi</h2>

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
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>