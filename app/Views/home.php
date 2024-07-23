<?php get_homeheader(); ?>

<main class="container">
    <section class="resultat_logement">
        <h2>Nos logements</h2>
        <div class="liste_logement">

        </div>
    </section>
</main>

<script>
    let form_search = document.getElementById('form_logement');
    const resultat = document.querySelector('.liste_logement ');
    form_search.addEventListener('submit', function(event){
        event.preventDefault();    
        search();
    });

    function search(){
        let data = new FormData(form_search);
        fetch('/search_lodgement', {
                method: "POST",
                body : data
            })
            .then(response => response.json())
            .then(data => {
                var html ='';
                if(data.length >0){
                    data.forEach(d => {
                        const img = (d.picture != null ? d.picture : 'default_lodgment.webp');
                        html += '<figure data-id="'+ d.id+'">';
                        html += '<img src="/public/assets/img/lodgements/'+img+'" alt="photo du logement">';
                        html += '<figcaption>';
                        html += '<div>'+ d.title+'</div>';
                        html += '<div class="price">'+ d.price+'€/nuit</div>';
                        html += '</figcaption>';
                        html += '</figure>';
                    });
                }else{
                    html = '<p>Aucun résultat trouvé </p>';
                }
                resultat.innerHTML = html;
                var resultats = document.querySelectorAll('.resultat_logement .liste_logement figure');
                resultats.forEach(result => {
                    result.addEventListener('click',function(e){
                        let id= this.getAttribute('data-id');
                        window.location.href = `/logement/${id}`;
                    });
                });
            })
            .catch(error => {
                console.log(error);
            });
    };
    search();
</script>

<?php get_footer(); ?>