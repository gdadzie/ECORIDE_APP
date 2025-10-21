
    <div class="d-flex justify-content-center">
        <div class="card shadow p-4" style="max-width: 500px; width: 100%;">
            <h2 class="text-center mb-3">Supprimer un utilisateur</h2>


                <ul class="list-group">

                        <li class="list-group-item d-flex justify-content-between align-items-center">

                            <form method="post" action="index.php?action=destroy" style="margin:0;">
                                <input type="hidden" name="id_utilisateur" value="{{ user.id_utilisateur }}">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?')">Supprimer</button>
                            </form>
                        </li>

                </ul>

                <p class="text-center">Aucun utilisateur à supprimer.</p>

        </div>
    </div>

