<?php get_backheader(); ?>
<main class="content_back">
    <p id="reponse_form"></p>
    <form method="post" id="form_add">
        <a class="button back" href="/backoffice/reservations"><i class="fa-solid fa-square-caret-left"></i> <span>Retour</span></a>
        <button onclick="submitSave(event)" class="save"><i class="fa-regular fa-floppy-disk"></i> <span><?= $data['button'] ?></span></button>
        <?php if($data['reservation']['id'] !=0){  ?>
        <button onclick="_delete(event)" class="delete" ><i class="fa-regular fa-trash-can"></i> <span>Supprimer</span></button>
        <?php }  ?>
        <h2><?= $data['h2'] ?></h2>
        <input type="hidden" name="id" value="<?= $data['reservation']['id'] ?>">
        <fieldset>
            <label for="id_user">Propriétaire</label>
            <select name="id_user" id="id_user">
                <option value=""></option>
                <?php foreach($data['users'] as $d){?>
                    <option value="<?= $d['id'] ?>" <?= $d['id'] == $data['reservation']['id_user'] ? "selected" : "" ;  ?> ><?= $d['id'].' - '.$d['lastname'].' '. $d['firstname'] ?></option>
                <?php }?>
            </select>
        </fieldset>       
        <fieldset>
            <label for="id_lodgement">Logement</label>
            <select name="id_lodgement" id="id_lodgement">
            <option value=""></option>
                <?php foreach($data['lodgements'] as $d){?>
                    
                    <option value="<?= $d['id'] ?>" <?= $d['id'] == $data['reservation']['id_lodgement'] ? "selected" : "" ;  ?> ><?= $d['id'].' - '.$d['title']?></option>
                <?php }?>
            </select>
            <!-- <input type="text" id="id_lodgement" placeholder="Titre" name="id_lodgement" value="<?= $data['reservation']['id_lodgement'] ?>"> -->
        </fieldset>
        <fieldset>
            <label for="start">Date de début</label>
            <input type="date" id="start" name="start" value="<?= $data['reservation']['start'] ?>">
        </fieldset>
        <fieldset>
            <label for="end">Date de fin</label>
            <input type="date" id="end" name="end" value="<?= $data['reservation']['end'] ?>">
        </fieldset>
    </form>
    <script>
    var input_add= document.querySelectorAll("#form_add input");
    function submitSave(event){
        event.preventDefault()
        var form = document.getElementById("form_add");
        var formData = new FormData(form);
        var alert =  document.getElementById('reponse_form');
        input_add.forEach(input => {
            input.title = '';
            input.classList.remove('error');
        });
        
        fetch('/save_reservation', 
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
                     window.location.href = '/backoffice/reservations';
                    }, 1500);
            }else {
                alert.classList.add('error');
                alert.textContent=data.message;
                for (const key in data.key) {
                    const value = data.key[key];
                    document.querySelector("#form_add [name="+ key +"]").title = value;
                    document.querySelector("#form_add [name="+ key +"]").classList.add('error');
                }          
            }
        })
        .catch(error => {
            console.error("Erreur lors de la requête Fetch:", error);
        });
    }
    function _delete(event){
        event.preventDefault()
        if(confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')){
            var alert =  document.getElementById('reponse_form');
            var form = document.getElementById("form_add");
            var formData = new FormData(form);
            fetch('/delete_reservation', 
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
                        window.location.href = '/backoffice/reservations';
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
