<?php get_header(); ?>
<main class="container">
    <div id="reponse_form"></div>
    <section class="lodgement_form">
        <form method="post" id="form_add">
            <input type="hidden" name="id_user" value="<?= $_SESSION['user']['id'] ?>">
            <input type="hidden" name="id" value="<?= $data['lodgement']['id'] ?>">
            <fieldset>
                <label for="title">Titre</label>
                <input type="text" id="title" placeholder="Titre" name="title" value="<?= $data['lodgement']['title'] ?>">
            </fieldset>
            <fieldset>
                <label for="city">Ville</label>
                <input type="text" id="city" placeholder="Ville" name="city" value="<?= $data['lodgement']['city'] ?>">
            </fieldset>
            <fieldset>
                <label for="price">Prix</label>
                <input type="number" step="0.01" id="price" placeholder="0.00" name="price" value="<?= $data['lodgement']['price'] ?>">
            </fieldset>
            <fieldset>
                <label for="peoples">Nombre de personne</label>
                <input type="number" id="peoples" placeholder="" name="peoples" value="<?= $data['lodgement']['peoples'] ?>">
            </fieldset>
            <fieldset>
                <label for="photo">Choisissez une photo :</label>
                <input type="file" id="picture" name="picture" accept="image/*">
            </fieldset>
            <fieldset>
                <label for="description">Description</label>
                <textarea name="description" id="description" cols="30" rows="10"><?= $data['lodgement']['description'] ?></textarea>
            </fieldset>
            <button onclick="submitSave(event)" type="submit"><?= $data['button'] ?></button>
            <?php if ( $data['lodgement']['id'] != 0) { ?>
                <button onclick="_delete(event)" class="delete" ><i class="fa-regular fa-trash-can"></i></button>
            <?php } ?>
        </form>
    </section>
</main>
<?php get_footer(); ?>

<script>
    var input_add= document.querySelectorAll("#form_add input");
    function submitSave(event){
        event.preventDefault()
        var alert =  document.getElementById('reponse_form');
        var form = document.getElementById("form_add");
        var formData = new FormData(form);
        alert.classList.remove(...alert.classList);
        input_add.forEach(input => {
            input.title = '';
            input.classList.remove('error');
        });
        
        fetch('/save_lodgement', 
        {
            method: "POST",
            body : formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status == 'success') {
                input_add.forEach(input => {
                    input.title = '';
                    input.classList.remove('error');
                    
                });
                alert.classList.add('success');
                alert.textContent=data.message;
                setTimeout( () => {
                     window.location.href = '/moncompte';
                    }, 1500);
            }else {
                alert.classList.add('error');
                alert.textContent=data.message;
                for (const key in data.key) {
                    const value = data.key[key];
                    console.log("#form_add input[name="+ key +"]")
                    document.querySelector("#form_add input[name="+ key +"]").title = value;
                    document.querySelector("#form_add input[name="+ key +"]").classList.add('error');
                }          
            }
        })
        .catch(error => {
            console.error("Erreur lors de la requête Fetch:", error);
        });
    }
    function _delete(event){
        event.preventDefault()
        if(confirm('Êtes-vous sûr de vouloir supprimer ce logement ?')){
            var alert =  document.getElementById('reponse_form');
            var form = document.getElementById("form_add");
            var formData = new FormData(form);
            alert.classList.remove(...alert.classList);
            fetch('/delete_lodgement', 
            {
                method: "POST",
                body : formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status == 'success') {
                    alert.classList.add('success');
                    alert.textContent=data.message;
                    setTimeout( () => {
                        window.location.href = '/moncompte';
                        }, 1500);
                }else{
                    alert.classList.add('error');
                    alert.textContent=data.message;
                }
            })
            .catch(error => {
                console.error("Erreur lors de la requête Fetch:", error);
            });
        }
    }
</script>