<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Palette de Couleurs - Charte Graphique</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
            color: #333;
        }
        h1, h2 {
            margin-top: 40px;
        }
        .palette {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
        }
        .color-box {
            width: 150px;
            height: 100px;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #000;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            position: relative;
        }
        .color-box span {
            background: rgba(255,255,255,0.7);
            padding: 2px 5px;
            border-radius: 4px;
            font-size: 12px;
            margin-top: 5px;
        }
        .copied {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(0,0,0,0.7);
            color: #fff;
            padding: 2px 5px;
            border-radius: 4px;
            font-size: 10px;
            display: none;
        }
    </style>
</head>
<body>

<h1>Charte Graphique - Visualisation des Couleurs</h1>

<h2>🎨 Palette Jaune – Style Citigo</h2>
<div class="palette">
    <div class="color-box" style="background:#FFD700;" onclick="copyColor('#FFD700', this)">
        Jaune vif
        <span>#FFD700</span>
        <div class="copied">Copié !</div>
    </div>
    <div class="color-box" style="background:#FFB347;" onclick="copyColor('#FFB347', this)">
        Orange doux
        <span>#FFB347</span>
        <div class="copied">Copié !</div>
    </div>
    <div class="color-box" style="background:#FFF4B1;" onclick="copyColor('#FFF4B1', this)">
        Jaune pastel
        <span>#FFF4B1</span>
        <div class="copied">Copié !</div>
    </div>
    <div class="color-box" style="background:#333333; color: #fff;" onclick="copyColor('#333333', this)">
        Texte principal
        <span>#333333</span>
        <div class="copied">Copié !</div>
    </div>
    <div class="color-box" style="background:#666666; color: #fff;" onclick="copyColor('#666666', this)">
        Texte secondaire
        <span>#666666</span>
        <div class="copied">Copié !</div>
    </div>
    <div class="color-box" style="background:#FFFDF5;" onclick="copyColor('#FFFDF5', this)">
        Fond principal
        <span>#FFFDF5</span>
        <div class="copied">Copié !</div>
    </div>
    <div class="color-box" style="background:#E0E0E0;" onclick="copyColor('#E0E0E0', this)">
        Bordures / ombres
        <span>#E0E0E0</span>
        <div class="copied">Copié !</div>
    </div>
</div>

<h2>🎨 Palette Verte – Écologique / Émeraude</h2>
<div class="palette">
    <div class="color-box" style="background:#00C853; color:#fff;" onclick="copyColor('#00C853', this)">
        Vert émeraude
        <span>#00C853</span>
        <div class="copied">Copié !</div>
    </div>
    <div class="color-box" style="background:#66FF99;" onclick="copyColor('#66FF99', this)">
        Vert clair
        <span>#66FF99</span>
        <div class="copied">Copié !</div>
    </div>
    <div class="color-box" style="background:#B9F6CA;" onclick="copyColor('#B9F6CA', this)">
        Vert pastel
        <span>#B9F6CA</span>
        <div class="copied">Copié !</div>
    </div>
    <div class="color-box" style="background:#212121; color:#fff;" onclick="copyColor('#212121', this)">
        Texte principal
        <span>#212121</span>
        <div class="copied">Copié !</div>
    </div>
    <div class="color-box" style="background:#757575; color:#fff;" onclick="copyColor('#757575', this)">
        Texte secondaire
        <span>#757575</span>
        <div class="copied">Copié !</div>
    </div>
    <div class="color-box" style="background:#FFFFFF;" onclick="copyColor('#FFFFFF', this)">
        Fond principal
        <span>#FFFFFF</span>
        <div class="copied">Copié !</div>
    </div>
    <div class="color-box" style="background:#E0E0E0;" onclick="copyColor('#E0E0E0', this)">
        Bordures / ombres
        <span>#E0E0E0</span>
        <div class="copied">Copié !</div>
    </div>
</div>

<script>
    function copyColor(color, element) {
        navigator.clipboard.writeText(color).then(() => {
            const copiedDiv = element.querySelector('.copied');
            copiedDiv.style.display = 'block';
            setTimeout(() => copiedDiv.style.display = 'none', 1000);
        });
    }
</script>

</body>
</html>
