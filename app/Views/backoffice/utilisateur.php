<?php get_backheader(); ?>
<main class="content_back">
    <p id="reponse_form"></p>
    <form method="post" id="form_add">
        <div class="action">
            <a class="button back" href="/backoffice/utilisateurs"><i class="fa-solid fa-square-caret-left"></i> <span>Retour</span></a>
            <button onclick="submitSave(event)" class="save"><i class="fa-regular fa-floppy-disk"></i> <span><?= $data['button'] ?></span></button>
            <?php if($data['user']['id'] != 0){  ?>
                <button onclick="_delete(event)" class="delete" ><i class="fa-regular fa-trash-can"></i> <span>Supprimer</span></button>
                <?php if($data['user']['actif'] == 1){  ?>
                    <button onclick="_switch(event,0)" class="switch" ><i class="fa-solid fa-user-lock"></i></i> <span>Désactiver</span></button>
                <?php }else{  ?>
                    <button onclick="_switch(event,1)" class="switch" ><i class="fa-solid fa-lock-open"></i></i> <span>Activer</span></button>
                <?php }  ?>
            <?php }  ?>
        </div>
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
            <input type="tel" id="mail" placeholder="06XXXXXXXX" name="phone" value="<?= $data['user']['phone'] ?>">
        </fieldset>    
        <fieldset>
            <label for="photo">Choisissez une photo :</label>
            <input type="file" id="picture" name="picture" accept="image/*">
        </fieldset>
        <fieldset>
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password">
        </fieldset>
        <?php if($data['user']['id'] != 0){ ?>
            <small><i class="fa-solid fa-triangle-exclamation"></i> Si vous saissisez un mot de passe celui ci remplacera le mot de passe actuel ! <i class="fa-solid fa-triangle-exclamation"></i></small> 
        <?php } ?>
    </form>
    <script>
    var input_add= document.querySelectorAll("#form_add input");
    function submitSave(event){
        event.preventDefault()
        var alert =  document.getElementById('reponse_form');
        var form = document.getElementById("form_add");
        var formData = new FormData(form);
        input_add.forEach(input => {
            input.title = '';
            input.classList.remove('error');
        });
        alert.classList.remove(...alert.classList);
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
                alert.classList.add('success');
                alert.textContent=data.message;
                setTimeout( () => {
                     window.location.href = '/backoffice/utilisateurs';
                    }, 1500);
            }else {
                alert.classList.add('error');
                alert.textContent=data.message;
                for (const key in data.key) {
                    const value = data.key[key];
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
        if(confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')){
            var alert =  document.getElementById('reponse_form');
            var form = document.getElementById("form_add");
            var formData = new FormData(form);
            alert.classList.remove(...alert.classList);
            fetch('/delete_user', 
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
                        window.location.href = '/backoffice/utilisateurs';
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

    function _switch(event, statut){
        event.preventDefault()
        if(confirm('Êtes-vous sûr de vouloir changer de statut cet utilisateur ?')){
            var alert =  document.getElementById('reponse_form');
            var formData = new FormData();
            formData.append('switch', statut);
            formData.append('id', document.querySelector('[name=id]').value);
            fetch('/switch_user', 
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
                        window.location.href = '/backoffice/utilisateurs';
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
</main>
<?php get_backfooter(); ?>
