<?php
if (!empty($_GET['q'])) {
    switch ($_GET['q']) {
        case 'info':
            phpinfo();
            exit;
    }
}

function stringToColor($string) {
    $rgb = substr(dechex(crc32($string)), 0, 6);
    $darker = 1.5;
    list($R16, $G16, $B16) = str_split($rgb, 2);
    $R = sprintf('%02X', floor(hexdec($R16) / $darker));
    $G = sprintf('%02X', floor(hexdec($G16) / $darker));
    $B = sprintf('%02X', floor(hexdec($B16) / $darker));
    return '#' . $R . $G . $B;
}

$items = scandir(__DIR__);
$items = array_filter($items, fn($i) => $i !== '.' && $i !== '..');
natcasesort($items);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Laragon</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">


</head>

<body>

<nav class="navbar">
    <div class="navbar-content">
        <a class="navbar-brand" href="/">Laragon</a>
        <input class="search-input" id="search" placeholder="Buscar..." onkeyup="filterItems()">
    </div>
</nav>

<div class="container">
    <div class="directories" id="directories">

<?php
$count = 0;
foreach ($items as $item) {

    if (in_array($item, ['laragon','bootstrap-5.3.1'])) continue;

    $path = __DIR__ . '/' . $item;
    $isDir = is_dir($path);
    $count++;

    if ($isDir) {
        $label = strtoupper($item[0]);
        $color = stringToColor($label);
        $type  = 'dir';
    } else {
        $ext = strtoupper(pathinfo($item, PATHINFO_EXTENSION));
        $label = $ext ?: 'FILE';
        $color = '#516d7e';
        $type  = 'file';
    }
?>
        <div class="directory-item"
            data-name="<?= strtolower($item) ?>"
            data-type="<?= $type ?>"
            style="--item-color:<?= $color ?>">

            <a class="directory-link" href="<?= htmlspecialchars($item) ?>">
                <div class="directory-icon" style="background:<?= $color ?>">
                    <?= $label ?>
                </div>
                <div class="directory-name"><?= htmlspecialchars($item) ?></div>
            </a>
        </div>
<?php } ?>

<?php if ($count === 0): ?>
    <div class="no-results">Nenhum item encontrado</div>
<?php endif; ?>

    </div>

    <div class="info">
        <?= $_SERVER['SERVER_SOFTWARE'] ?><br>
        PHP <?= phpversion() ?> —
        <a href="/?q=info" style="color:#007bff">phpinfo()</a><br>
        Document Root: <?= $_SERVER['DOCUMENT_ROOT'] ?>
    </div>
</div>

<script>
function filterItems(){
    const q=document.getElementById("search").value.toLowerCase();
    document.querySelectorAll(".directory-item").forEach(el=>{
        el.style.display = el.dataset.name.includes(q) ? "block" : "none";
    });
}
</script>

</body>
</html>
