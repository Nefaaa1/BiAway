<?php get_backheader(); ?>
<main class="content_back">
    <form method="post" id="form_add">
        <a class="button back" href="/backoffice/utilisateurs"><i class="fa-solid fa-square-caret-left"></i> <span>Retour</span></a>
        <button onclick="submitSave(event)" class="save"><i class="fa-regular fa-floppy-disk"></i> <span><?= $data['button'] ?></span></button>
        <?php if($data['user']['id'] !=""){  ?>
        <button onclick="_delete(event)" class="delete" ><i class="fa-regular fa-trash-can"></i> <span>Supprimer</span></button>
        <?php }  ?>
        <h2><?= $data['h2'] ?></h2>
        <input type="hidden" name="id" value="<?= $data['user']['id'] ?>">       
        <fieldset>
            <label for="id_role">Rôle</label>
            <select name="id_role" id="id_role">
                <?php foreach($data['roles'] as $d){?>
                    <option value="<?= $d['id'] ?>" <?= $d['id'] == $data['user']['id_role'] ? "selected" : "" ;  ?> ><?= $d['name'] ?></option>
                <?php }?>
            </select>
        </fieldset>
        <fieldset>
            <label for="name">Nom</label>
            <input type="text" id="name" placeholder="Nom" name="lastname" value="<?= $data['user']['lastname'] ?>">
        </fieldset>
        <fieldset>
            <label for="firstname">Prénom</label>
            <input type="text" id="firstname" placeholder="Prénom" name="firstname" value="<?= $data['user']['firstname'] ?>">
        </fieldset>
        <fieldset>
            <label for="mail">Mail</label>
            <input type="email" id="mail" placeholder="Mail" name="mail" value="<?= $data['user']['mail'] ?>">
        </fieldset>
        <fieldset>
            <label for="mail">Téléphone</label>
            <input type="tel" id="mail" placeholder="Mail" name="phone" value="<?= $data['user']['phone'] ?>">
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
        
        fetch('/save_user', 
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
                     window.location.href = '/backoffice/utilisateurs';
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
        if(confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')){
            event.preventDefault()
            var form = document.getElementById("form_add");
            var formData = new FormData(form);
            fetch('/delete_user', 
            {
                method: "POST",
                body : formData
            })
            .then(data => {
                window.location.href = '/backoffice/utilisateurs';
            })
            .catch(error => {
                console.error("Erreur lors de la requête Fetch:", error);
            });
        }
    }
  </script>
</main>
<?php get_backfooter(); ?>
