<?php get_backheader(); ?>
<main class="content_back">
   <a class="button" href="/backoffice/utilisateurs">Retour</a>
   <h2><?= $data['h2'] ?></h2>
  <form method="post" id="form_add">
     <input type="hidden" name="id" value="<?= $data['user']['id'] ?>">       
      <fieldset>
         <select name="id_role">
            <?php foreach($data['roles'] as $d){?>
                <option value="<?= $d['id'] ?>" <?= $d['id'] == $data['user']['id_role'] ? "selected" : "" ;  ?> ><?= $d['name'] ?></option>
            <?php }?>
        </select>
      </fieldset>
      <fieldset>
         <input type="text" placeholder="Nom" name="lastname" value="<?= $data['user']['lastname'] ?>">
      </fieldset>
      <fieldset>
         <input type="text" placeholder="Prénom" name="firstname" value="<?= $data['user']['firstname'] ?>">
      </fieldset>
      <fieldset>
         <input type="mail" placeholder="Mail" name="mail" value="<?= $data['user']['mail'] ?>">
      </fieldset>
      <fieldset>
         <input type="number" placeholder="Âge" name="age" value="<?= $data['user']['age'] ?>">
      </fieldset>
      <!-- <fieldset>
         <input type="password" placeholder="Mot de passe" name="password">
      </fieldset> -->
      <button onclick="submitSave(event)" ><?= $data['button'] ?></button>
      <?php if($data['user']['id'] !=""){  ?>
      <button onclick="_delete(event)" >Supprimer</button>
      <?php }  ?>
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
