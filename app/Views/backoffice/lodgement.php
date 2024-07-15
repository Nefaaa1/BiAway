<?php get_backheader(); ?>
<main class="content_back">
    <form method="post" id="form_add">
        <a class="button back" href="/backoffice/logements"><i class="fa-solid fa-square-caret-left"></i> <span>Retour</span></a>
        <button onclick="submitSave(event)" class="save"><i class="fa-regular fa-floppy-disk"></i> <span><?= $data['button'] ?></span></button>
        <?php if($data['lodgement']['id'] !=""){  ?>
        <button onclick="_delete(event)" class="delete" ><i class="fa-regular fa-trash-can"></i> <span>Supprimer</span></button>
        <?php }  ?>
        <h2><?= $data['h2'] ?></h2>
        <input type="hidden" name="id" value="<?= $data['lodgement']['id'] ?>">
        <fieldset>
            <label for="id_user">Propriétaire</label>
            <select name="id_user" id="id_user">
                <?php foreach($data['users'] as $d){?>
                    <option value="<?= $d['id'] ?>" <?= $d['id'] == $data['lodgement']['id_user'] ? "selected" : "" ;  ?> ><?= $d['lastname'].' '. $d['firstname'] ?></option>
                <?php }?>
            </select>
        </fieldset>       
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
            <input type="text" id="price" placeholder="0.00" name="price" value="<?= $data['lodgement']['price'] ?>">
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
    </form>
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
                     window.location.href = '/backoffice/logements';
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
    function _delete(event){
        if(confirm('Êtes-vous sûr de vouloir supprimer ce logement ?')){
            event.preventDefault()
            var form = document.getElementById("form_add");
            var formData = new FormData(form);
            fetch('/delete_lodgement', 
            {
                method: "POST",
                body : formData
            })
            .then(response => response.json())
            .then(data => {
                console.log(data)
                // window.location.href = '/backoffice/logements';
            })
            .catch(error => {
                console.error("Erreur lors de la requête Fetch:", error);
            });
        }
    }
  </script>
</main>
<?php get_backfooter(); ?>
