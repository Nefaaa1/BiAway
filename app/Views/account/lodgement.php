<?php get_header(); ?>
<main class="container">
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
            <label for="photo">Choisissez une photo :</label>
             <input type="file" id="picture" name="picture" accept="image/*">
             <fieldset>
                <label for="description">Description</label>
                <textarea name="description" id="description" cols="30" rows="10"><?= $data['lodgement']['description'] ?></textarea>
            </fieldset>
            <button onclick="submitSave(event)" type="submit"><?= $data['button'] ?></button>
            <?php if ( $data['lodgement']['id'] != "") { ?>
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
        var form = document.getElementById("form_add");
        var formData = new FormData(form);
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
                setTimeout( () => {
                     window.location.href = '/moncompte';
                    }, 500);
            }else {
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
</script>