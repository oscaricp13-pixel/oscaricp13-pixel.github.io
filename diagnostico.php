<?php
header('Content-Type: text/html; charset=utf-8');

$conexion = new mysqli("localhost", "root", "", "yedi");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Diagnóstico YEDI</title>
<style>
body{font-family:monospace;padding:20px;background:#1e1e1e;color:#d4d4d4;}
h2{color:#4ec9b0;}
table{border-collapse:collapse;width:100%;margin-bottom:30px;}
th{background:#264f78;color:white;padding:8px 12px;text-align:left;}
td{padding:7px 12px;border-bottom:1px solid #333;}
tr:hover td{background:#2d2d2d;}
.ok{color:#4ec9b0;}
.err{color:#f44747;}
.warn{color:#dcdcaa;}
pre{background:#252526;padding:15px;border-radius:6px;overflow-x:auto;}
</style>
</head>
<body>

<h2>🔍 Diagnóstico YEDI</h2>

<?php if($conexion->connect_error): ?>
  <p class="err">❌ Error de conexión: <?= $conexion->connect_error ?></p>
<?php else: ?>
  <p class="ok">✅ Conexión MySQL OK</p>
<?php endif; ?>

<h2>👥 Usuarios registrados</h2>
<table>
<tr><th>ID</th><th>usuario (email)</th><th>nombre</th><th>rol</th></tr>
<?php
$r = $conexion->query("SELECT id, usuario, nombre, rol FROM usuarios ORDER BY id");
while($f = $r->fetch_assoc()):
?>
<tr>
  <td><?= $f['id'] ?></td>
  <td class="warn">"<?= htmlspecialchars($f['usuario']) ?>"</td>
  <td><?= htmlspecialchars($f['nombre']) ?></td>
  <td class="<?= in_array(strtolower($f['rol']), ['vendedor','comprador','cliente']) ? 'ok' : 'err' ?>">
    "<?= htmlspecialchars($f['rol']) ?>"
  </td>
</tr>
<?php endwhile; ?>
</table>

<h2>💬 Últimos 20 mensajes en BD</h2>
<table>
<tr><th>ID</th><th>de_usuario</th><th>para_usuario</th><th>mensaje</th><th>fecha</th></tr>
<?php
$r = $conexion->query("SELECT * FROM mensajes ORDER BY id DESC LIMIT 20");
if($r && $r->num_rows > 0):
  while($f = $r->fetch_assoc()):
?>
<tr>
  <td><?= $f['id'] ?></td>
  <td class="warn">"<?= htmlspecialchars($f['de_usuario']) ?>"</td>
  <td class="warn">"<?= htmlspecialchars($f['para_usuario']) ?>"</td>
  <td><?= htmlspecialchars(mb_substr($f['mensaje'],0,40)) ?></td>
  <td><?= $f['fecha'] ?></td>
</tr>
<?php endwhile;
else: ?>
<tr><td colspan="5" class="err">⚠️ Sin mensajes — tabla vacía o no existe</td></tr>
<?php endif; ?>
</table>

<h2>📦 Tabla mensajes — estructura</h2>
<pre><?php
$r = $conexion->query("DESCRIBE mensajes");
if($r) while($f=$r->fetch_assoc()) echo $f['Field']." | ".$f['Type']." | ".$f['Null']." | ".$f['Key']."\n";
else echo "❌ Tabla mensajes no existe: ".$conexion->error;
?></pre>

<h2>🧪 Test: insertar mensaje de prueba</h2>
<?php
$stmt = $conexion->prepare("INSERT INTO mensajes (de_usuario, para_usuario, mensaje) VALUES (?,?,?)");
if(!$stmt){
    echo "<p class='err'>❌ Prepare falló: ".$conexion->error."</p>";
} else {
    $de   = "test_comprador@test.com";
    $para = "test_vendedor@test.com";
    $msg  = "Mensaje de prueba ".date("H:i:s");
    $stmt->bind_param("sss", $de, $para, $msg);
    if($stmt->execute()){
        echo "<p class='ok'>✅ INSERT de prueba OK — ID: ".$stmt->insert_id."</p>";
        // Limpiar prueba
        $conexion->query("DELETE FROM mensajes WHERE de_usuario='test_comprador@test.com'");
    } else {
        echo "<p class='err'>❌ INSERT falló: ".$stmt->error."</p>";
    }
    $stmt->close();
}
?>

<?php $conexion->close(); ?>
</body>
</html>
