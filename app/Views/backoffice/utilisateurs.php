<?php get_backheader(); ?>
<main class="content_back">
    <form id='form_search'>
        <select name="id_role">
            <option value="">Choisir un role</option>
            <?php foreach($data['roles'] as $d){?>
                <option value="<?= $d['id'] ?>"><?= $d['name'] ?></option>
            <?php }?>
        </select>
        <input type="text" name="recherche" placeholder="Nom | Prénom | Mail">
    </form>
    <h2>Listes d'utilisateurs</h2>
    <a href="/backoffice/utilisateur" class='button'>Ajouter un utilisateur</a>
    <section>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom / Prénom</th>
                    <th>Mail</th>
                    <th>Rôle</th>
                </tr>
            </thead>
            <tbody id="liste_resultat">
            </tbody>
        </table>
    </section>
    <script>
       document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("form_search").addEventListener("submit", function(event) {
                event.preventDefault();
            });
            let search = function(){
                var resultat = document.getElementById('liste_resultat');
                var form = document.getElementById("form_search");
                var formData = new FormData(form);
                fetch('/search_user', {
                    method: "POST",
                    body : formData
                })
                .then(response => response.json())
                .then(data => {
                    var html ='';
                    data.forEach(d => {
                        var no_actif= "";
                        if(d.actif == 0){
                            no_actif = 'class="disabled_tr"' 
                        }
                        html += `<tr data-id="${d.id}" ${no_actif}> 
                                    <td data-nom='ID'>${d.id}</td>
                                    <td data-nom='Nom / Prénom'>${d.lastname} ${d.firstname}</td>
                                    <td data-nom='Mail'>${d.mail}</td>
                                    <td data-nom='Rôle'>${d.role_name}</td>
                                </tr>`;
                    });
                    resultat.innerHTML = html;
                    var resultats = document.querySelectorAll('#liste_resultat tr');
                    resultats.forEach(result => {
                        result.addEventListener('click',function(e){
                            let id= this.getAttribute('data-id');
                            window.location.href = `/backoffice/utilisateur/${id}`;
                        });
            });
                })
                .catch(error => {
                    console.error(error);
                });
            }
            search(); 
            document.querySelector('[name=id_role]').addEventListener('change',search);
            document.querySelector('[name=recherche]').addEventListener('change',search);
        });

        
    </script>
</main>
<?php get_backfooter(); ?>
