<?php
/*
 * Template Name: Test PHPMailer
 */
?>


<?php
$response = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // $data_drive = [];
    // foreach ($_POST as $key => $value) {
    //     // Omitir campos si querés (ejemplo: subject, action, etc)
    //     if (!in_array($key, ['subject', 'action', 'destinatario', 'activegs', 'gsid'])) {
    //         $label = ucfirst(strtolower(str_replace('-', ' ', $key)));
    //         $formattedValue = htmlspecialchars(is_array($value) ? implode(', ', $value) : str_replace(',', ', ', $value));
    //         $data_drive[$label] = $formattedValue;
    //     }
    // }

    // Función que envía a Google Sheets
    $data_drive = [
        'Nombre' => 'nombre',
        'Email' => 'apellido',
        'Mensaje' => 'mensaje'
    ];

    $driveUrl = "https://script.google.com/macros/s/AKfycbzhF1A31oUVpp5qJwSh8xYEgAS8pv4GFXl0KuExjDzgNTZLZPqp1BOCKRyXsfScWIVlmA/exec";
    $response_drive = saveDataGoogleSheet($data_drive, $driveUrl);
    $response = $response_drive ?? 'Error al enviar datos';
    var_dump($response_drive);
}
?>



<?php if ($response): ?>
  <p><strong>Respuesta:</strong> <?= htmlspecialchars($response) ?></p>
<?php endif; ?>

<form method="POST" action="">
  <label>Nombre: <input type="text" name="nombre" required></label><br>
  <label>Email: <input type="email" name="email" required></label><br>
  <label>Mensaje: <textarea name="mensaje" required></textarea></label><br>
  <button type="submit">Enviar</button>
</form>
