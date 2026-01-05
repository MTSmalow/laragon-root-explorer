<?php
$ROOT = realpath(__DIR__);

// UTILIDADES
function stringToColor($string) {
    $rgb = substr(dechex(crc32($string)), 0, 6);
    $darker = 1.5;
    list($R16, $G16, $B16) = str_split($rgb, 2);
    return sprintf(
        '#%02X%02X%02X',
        floor(hexdec($R16) / $darker),
        floor(hexdec($G16) / $darker),
        floor(hexdec($B16) / $darker)
    );
}

function isImage($file) {
    return preg_match('/\.(jpg|jpeg|png|gif|webp|svg)$/i', $file);
}

$relativePath = $_GET['path'] ?? '';
$relativePath = trim($relativePath, '/');

$currentPath = realpath($ROOT . '/' . $relativePath);

// Bloqueia path traversal
if ($currentPath === false || strpos($currentPath, $ROOT) !== 0) {
    $currentPath = $ROOT;
    $relativePath = '';
}

// LISTAGEM
$items = scandir($currentPath);
$items = array_filter($items, fn($i) => $i !== '.' && $i !== '..');
natcasesort($items);

// BREADCRUMB
$breadcrumbs = [];
$accum = '';
foreach (explode('/', $relativePath) as $part) {
    if ($part === '') continue;
    $accum .= ($accum ? '/' : '') . $part;
    $breadcrumbs[] = [
        'name' => $part,
        'path' => $accum
    ];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Laragon Root UI</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{margin:0;padding:0;box-sizing:border-box}
body{
    font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto;
    background:#1a1a1a;color:#fff;min-height:100vh
}
a{color:inherit;text-decoration:none}

.navbar{
    background:#2d2d2d;
    padding:.75rem 1rem;
    position:fixed;top:0;left:0;right:0;
    border-bottom:1px solid #444;z-index:1000
}
.navbar-content{
    max-width:1200px;margin:auto;
    display:flex;justify-content:space-between;align-items:center;gap:1rem
}
.brand{font-weight:bold;font-size:1.2rem}
.search-input{
    padding:.5rem 1rem;
    background:#1a1a1a;color:#fff;
    border:1px solid #444;border-radius:6px;width:260px
}

.container{max-width:1200px;margin:auto;padding:6rem 1rem 2rem}

.breadcrumb{
    margin-bottom:1.5rem;
    font-size:.9rem;
    color:#aaa
}
.breadcrumb a{color:#8bb3c3}
.breadcrumb span{margin:0 .25rem}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:1.5rem
}
.card{
    background:#2d2d2d;
    border-radius:12px;
    padding:1.25rem;
    border:1px solid #444;
    transition:.3s;
    position:relative
}
.card::before{
    content:"";
    position:absolute;top:0;left:0;right:0;height:3px;
    background:linear-gradient(90deg,var(--c),transparent)
}
.card:hover{
    transform:translateY(-4px);
    border-color:var(--c);
    box-shadow:0 10px 30px rgba(0,0,0,.4)
}
.card a{
    display:flex;align-items:center;gap:1rem
}

/* ICON */
.icon{
    width:50px;height:50px;
    border-radius:12px;
    display:flex;align-items:center;justify-content:center;
    font-weight:bold;font-size:.85rem;
    background:var(--c)
}
.name{
    word-break:break-all;
    font-size:1rem
}

/* IMAGE PREVIEW */
.preview{
    margin-top:.75rem;
    border-radius:8px;
    overflow:hidden;
    border:1px solid #444
}
.preview img{
    width:100%;
    max-height:180px;
    object-fit:cover;
    display:block
}

/* INFO */
.info{
    margin-top:3rem;
    background:#2d2d2d;
    padding:1.5rem;
    border-radius:12px;
    border:1px solid #444;
    line-height:1.7
}

.hidden{display:none}
</style>
</head>

<body>

<nav class="navbar">
    <div class="navbar-content">
        <div class="brand">Laragon Root UI</div>
        <input id="search" class="search-input" placeholder="Buscar..." onkeyup="filterItems()">
    </div>
</nav>

<div class="container">

    <div class="breadcrumb">
        <a href="?">root</a>
        <?php foreach ($breadcrumbs as $b): ?>
            <span>/</span>
            <a href="?path=<?= urlencode($b['path']) ?>">
                <?= htmlspecialchars($b['name']) ?>
            </a>
        <?php endforeach ?>
    </div>

    <div class="grid" id="grid">

<?php foreach ($items as $item):
    $full = $currentPath . '/' . $item;
    $isDir = is_dir($full);
    $isImg = !$isDir && isImage($item);

    if ($isDir) {
        $label = strtoupper($item[0]);
        $color = stringToColor($label);
        $link  = '?path=' . urlencode(trim($relativePath . '/' . $item, '/'));
        $type  = 'dir';
    } else {
        $ext = strtoupper(pathinfo($item, PATHINFO_EXTENSION)) ?: 'FILE';
        $label = $ext;
        $color = '#516d7e';
        $link  = ($relativePath ? $relativePath.'/' : '') . $item;
        $type  = 'file';
    }
?>
        <div class="card" data-name="<?= strtolower($item) ?>" style="--c:<?= $color ?>">
            <a href="<?= htmlspecialchars($link) ?>">
                <div class="icon"><?= $label ?></div>
                <div class="name"><?= htmlspecialchars($item) ?></div>
            </a>

            <?php if ($isImg): ?>
                <div class="preview">
                    <img src="<?= htmlspecialchars($link) ?>" alt="">
                </div>
            <?php endif ?>
        </div>
<?php endforeach ?>

    </div>

    <div class="info">
        <?= $_SERVER['SERVER_SOFTWARE'] ?><br>
        PHP <?= phpversion() ?><br>
        Root: <?= $currentPath ?>
    </div>
</div>

<script>
function filterItems(){
    const q = document.getElementById('search').value.toLowerCase();
    document.querySelectorAll('.card').forEach(c=>{
        c.style.display = c.dataset.name.includes(q) ? 'block' : 'none';
    });
}
</script>

</body>
</html>
