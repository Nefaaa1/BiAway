<?php get_backheader(); ?>
<main class="content_back">
    <form id='form_search'>
        <input type="text" name="recherche" placeholder="Titre | Ville">
    </form>
    <h2>Listes de logements</h2>
    <a href="/backoffice/logement" class='button'>Ajouter un logement</a>
    <section>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Nombre de place</th>
                    <th>Prix</th>
                    <th>Ville</th>
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
                fetch('/backoffice/search_lodgement', {
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
                                    <td data-nom='Titre'>${d.title}</td>
                                    <td data-nom='Nombre de place'>${d.peoples}</td>
                                    <td data-nom='Prix'>${d.price} €</td>
                                    <td data-nom='Ville'>${d.city}</td>
                                </tr>`;
                    });
                    resultat.innerHTML = html;
                    var resultats = document.querySelectorAll('#liste_resultat tr');
                    resultats.forEach(result => {
                        result.addEventListener('click',function(e){
                            let id= this.getAttribute('data-id');
                            window.location.href = `/backoffice/logement/${id}`;
                        });
                    });
                })
                .catch(error => {
                    console.error(error);
                });
            }
            search(); 
            document.querySelector('[name=recherche]').addEventListener('change',search);
        });

        
    </script>
</main>
<?php get_backfooter(); ?>
