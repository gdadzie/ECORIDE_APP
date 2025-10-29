<?php
$success = $_GET['success'] ?? null;
$error   = $_GET['error'] ?? null;

if(session_status() === PHP_SESSION_NONE) session_start();

// Fallback si aucun utilisateur
$utilisateurs = $utilisateurs ?? [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des utilisateurs - EcoRide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #F5F5F5; font-family:'Inter',sans-serif; color:#212121; }
        .table-container { max-width: 1400px; margin:40px auto; background:#FFFFFF; border-radius:16px; padding:30px; box-shadow:0 8px 25px rgba(0,0,0,0.08); }
        h1 { color:#00C853; font-weight:700; margin-bottom:30px; text-align:center; }
        .table thead th { background-color:#00C853; color:#fff; font-weight:600; cursor:pointer; }
        .table-striped tbody tr:nth-of-type(odd){ background-color:#F0FFF0; }
        .table-hover tbody tr:hover { background-color:#E0F8E0; }
        .badge-admin { background-color:#00C853; color:#FFFFFF; }
        .badge-user { background-color:#66FF99; color:#212121; }
        .badge-actif { font-size:0.85em; padding:0.35em 0.75em; border-radius:50px; }
        .btn-delete { background-color:#B9F6CA; border-color:#B9F6CA; color:#212121; }
        .btn-delete:hover { background-color:#66FF99; border-color:#66FF99; color:#212121; }
        .search-bar { max-width:400px; margin-bottom:20px; }
        .pagination li a { color:#00C853; border-radius:6px; }
        .pagination li a.active { background-color:#00C853; color:#fff; }
        .sort-indicator { font-size:0.7em; margin-left:5px; }
        .btn-theme-green {background-color: #00C853;border-color: #00C853;color: #FFFFFF;border-radius: 8px;transition: 0.3s;}
        .btn-theme-green:hover {background-color: #66FF99;border-color: #66FF99;color: #212121;}

    </style>
</head>
<body>

<div class="container table-container">
    <h1>Liste des utilisateurs</h1>

    <?php if($success): ?>
        <div class="alert alert-success text-center">Utilisateur supprimé avec succès.</div>
    <?php elseif($error): ?>
        <div class="alert alert-danger text-center">Erreur lors de la suppression de l'utilisateur.</div>
    <?php endif; ?>

    <input type="text" id="searchAll" class="form-control search-bar" placeholder="Rechercher...">

    <div class="table-responsive mt-3">
        <table class="table table-striped table-hover align-middle" id="userTable">
            <thead>
            <tr>
                <th data-column="id">ID <span class="sort-indicator">⇅</span></th>
                <th data-column="pseudo">Pseudo <span class="sort-indicator">⇅</span></th>
                <th data-column="nom">Nom <span class="sort-indicator">⇅</span></th>
                <th data-column="prenom">Prénom <span class="sort-indicator">⇅</span></th>
                <th data-column="email">Email <span class="sort-indicator">⇅</span></th>
                <th data-column="tel">Téléphone <span class="sort-indicator">⇅</span></th>
                <th data-column="role">Rôle <span class="sort-indicator">⇅</span></th>
                <th data-column="type">Type <span class="sort-indicator">⇅</span></th>
                <th data-column="actif">Actif <span class="sort-indicator">⇅</span></th>
                <th data-column="date">Date <span class="sort-indicator">⇅</span></th>
                <?php if(isset($_SESSION['user']) && $_SESSION['user']->getRole()===2): ?><th>Supprimer</th><?php endif; ?>
            </tr>
            </thead>
            <tbody id="tableBody">
            <?php if(!empty($utilisateurs)): ?>
                <?php foreach($utilisateurs as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user->getIdUtilisateur()) ?></td>
                        <td><?= htmlspecialchars($user->getPseudo()) ?></td>
                        <td><?= htmlspecialchars($user->getNom()) ?></td>
                        <td><?= htmlspecialchars($user->getPrenom()) ?></td>
                        <td><?= htmlspecialchars($user->getEmail()) ?></td>
                        <td><?= htmlspecialchars($user->getTelephone()) ?></td>
                        <td>
                            <?= $user->getRole()==2 ? '<span class="badge badge-admin">Admin</span>' : '<span class="badge badge-user">User</span>' ?>
                        </td>
                        <td><?= htmlspecialchars($user->getTypeCovoiturage()) ?></td>
                        <td>
                            <?= $user->getActif() ? '<span class="badge badge-actif bg-success">Oui</span>' : '<span class="badge badge-actif bg-danger">Non</span>' ?>
                        </td>
                        <td><?= htmlspecialchars($user->getDateCreation()) ?></td>
                        <?php if(isset($_SESSION['user']) && $_SESSION['user']->getRole()===2): ?>
                            <td>
                                <a href="index.php?entity=utilisateurs&action=supprimer&id=<?= $user->getIdUtilisateur() ?>"
                                   class="btn btn-delete btn-sm" onclick="return confirm('Supprimer cet utilisateur ?');">Supprimer</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="<?= isset($_SESSION['user']) && $_SESSION['user']->getRole()===2?11:10 ?>" class="text-center">Aucun utilisateur trouvé.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
        <!-- Bouton Accueil -->
        <div class="d-flex justify-content-end mb-3">
            <a href="index.php" class="btn btn-theme-green">
                Accueil
            </a>
        </div>

    </div>

    <nav>
        <ul class="pagination justify-content-center mt-3" id="pagination"></ul>
    </nav>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Recherche multi-colonnes
    document.getElementById('searchAll').addEventListener('input', function(){
        const val = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tableBody tr');
        rows.forEach(row=>{
            let show=false;
            row.querySelectorAll('td').forEach(td=>{
                if(td.textContent.toLowerCase().includes(val)) show=true;
            });
            row.style.display = show?'':'none';
        });
    });

    // Tri des colonnes
    document.querySelectorAll('#userTable thead th[data-column]').forEach(header=>{
        let asc=true;
        header.addEventListener('click',()=>{
            const table = header.closest('table');
            const tbody = table.querySelector('tbody');
            Array.from(tbody.querySelectorAll('tr'))
                .sort((a,b)=>{
                    const aText=a.cells[header.cellIndex].textContent.trim();
                    const bText=b.cells[header.cellIndex].textContent.trim();
                    if(!isNaN(aText) && !isNaN(bText)) return asc? aText-bText : bText-aText;
                    return asc? aText.localeCompare(bText) : bText.localeCompare(aText);
                })
                .forEach(tr=>tbody.appendChild(tr));
            asc = !asc;
        });
    });

    // Pagination simple
    const rowsPerPage = 10;
    const table = document.getElementById('tableBody');
    const rows = Array.from(table.querySelectorAll('tr'));
    const pagination = document.getElementById('pagination');
    let currentPage = 1;

    function showPage(page){
        currentPage = page;
        const start = (page-1)*rowsPerPage;
        const end = start+rowsPerPage;
        rows.forEach((r,i)=> r.style.display=(i>=start && i<end)?'':'none');
        renderPagination();
    }

    function renderPagination(){
        const pageCount = Math.ceil(rows.length/rowsPerPage);
        pagination.innerHTML='';
        for(let i=1;i<=pageCount;i++){
            const li = document.createElement('li');
            li.className='page-item';
            li.innerHTML=`<a class="page-link ${i===currentPage?'active':''}" href="#">${i}</a>`;
            li.addEventListener('click',e=>{e.preventDefault(); showPage(i);});
            pagination.appendChild(li);
        }
    }

    showPage(1);
</script>
</body>
</html>
