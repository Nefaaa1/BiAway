<?php get_backheader(); ?>
<main class="content_back">
    <form id='form_search'>
        <input type="text" name="recherche" placeholder="Nom">
    </form>
    <h2>Listes des réservations</h2>
    <a href="/backoffice/reservation" class='button'>Ajouter une réservation</a>
    <section>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom / Prénom</th>
                    <th>Date</th>
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
               
                fetch('/search_reservation', {
                    method: "POST",
                    body : formData
                })
                .then(response => response.json())
                .then(data => {
                    var html ='';
                    data.forEach(d => {
                        html += `<tr data-id="${d.id}">
                                    <td data-nom='ID'>${d.id}</td>
                                    <td data-nom='Nom / Prénom'>${d.lastname} ${d.firstname}</td>
                                    <td data-nom='Date'>${new Date(d.start).toLocaleDateString('fr-CA').split('-').reverse().join('/')} -> ${new Date(d.end).toLocaleDateString('fr-CA').split('-').reverse().join('/')}</td>
                                    <td data-nom='Prix'>${d.price}</td>
                                    <td data-nom='Ville'>${d.city}</td>
                                </tr>`;
                    });
                    resultat.innerHTML = html;
                    var resultats = document.querySelectorAll('#liste_resultat tr');
                    resultats.forEach(result => {
                        result.addEventListener('click',function(e){
                            let id= this.getAttribute('data-id');
                            window.location.href = `/backoffice/reservation/${id}`;
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
