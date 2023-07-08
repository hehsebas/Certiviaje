<?php
use LicenseManagerForWooCommerce\Models\Resources\License as LicenseResourceModel;
use LicenseManagerForWooCommerce\Settings;

defined('ABSPATH') || exit; 

global $wpdb;
$categoriesList = listCategory($wpdb);

$categorias = array();
foreach ($categoriesList as $categoria) {
    $listOfImages = listImage($wpdb, $categoria["id"]);

    array_push($categorias, array(
        'nombre' => $categoria["category_name"],
        'imagenes' => $listOfImages,
    ));
}

function listCategory($wpdb){
    $query = "SELECT * FROM {$wpdb->prefix}diseños";
    $categoriesList = $wpdb->get_results($query, ARRAY_A);
    return $categoriesList;
}

function listImage($wpdb,$id){
    $query = "SELECT url FROM {$wpdb->prefix}imagesD WHERE diseño_id = $id";
    $categoriesList = $wpdb->get_results($query, ARRAY_A);
    return $categoriesList;
}
?>

<h2><?php _e('Your license keys', 'license-manager-for-woocommerce'); ?></h2>
<style>
.modal {
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    overflow-y: auto; /* Agrega un scroll vertical al contenido */
}
.successModal{
    max-width: 1200;
    width: 100%;
    margin: auto;
    padding: 20px;
    background: #fff;
}

.modal-dialog {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    border-radius: 5px;
    padding: 20px;
    width: 60%;
    max-width: 1200px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    overflow-y: auto;
    max-height: calc(100% - 100px); /* Ajusta la altura máxima del modal según sea necesario */
}


.modal-header {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  padding: 10px;
  border-bottom: 1px solid #ccc;
} 

.modal-body {
    display: block;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #ccc;
    overflow-y: auto; /* Agrega un scroll vertical al contenido del modal */
    max-height: calc(100vh - 250px); /* Ajusta la altura máxima del contenido según sea necesario */
}
.modal-content {
  background-color: transparent; /* Establece el fondo del contenido del modal como transparente */
  border: none; /* Quita el borde del contenido del modal */
  padding: 0; /* Elimina el espacio de relleno del contenido del modal */
  overflow-y: auto;
}
.container {
    background-color: #f1f1f1;
    padding: 10px;
    margin-top: 20px;
    border-top: 1px solid gray;
    overflow-y: auto; /* Agrega un scroll vertical al contenido de la caja */
    max-height: calc(100% - 20px); /* Ajusta la altura máxima del contenido de la caja según sea necesario */
}
.image-container {
            overflow-x: auto;
            white-space: nowrap;
            border-radius: 15px;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 1px solid #ccc; /* Ajusta el color y grosor del borde según tus preferencias */
            background-color: #fff;
            position: relative;
        }

    .image-container img {
        display: inline-block;
        width: 16rem;
        height: 16rem;
        object-fit: cover;
        margin-right: 10px;
    }
.image-wrapper {
    position: relative;
    display: inline-block;
}

.button-container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-wrapper:hover .button-container {
    opacity: 1;
}
.form-label {
  font-weight: bold;
  margin-bottom: 5px;
  margin-top: 5px;
}

.form-control {
  width: 100%;
  padding: 8px;
  font-size: 1rem;
  border-radius: 4px;
  border: 1px solid #ccc;
  transition: border-color 0.2s ease-in-out;
}

.form-control:focus {
  outline: none;
  border-color: #007bff;
}

.form-text {
  font-size: 0.9rem;
  color: #777;
  margin-bottom: 3px;
  margin-top:3px;
}

.form-check {
  font-weight: normal;
  display: flex;
  align-items: center;
  vertical-align: middle;
}
.form-check-label {
  margin-top: 4px; /* Ajusta el espacio entre el checkbox y el label */
  margin-right: 5px; /* Ajusta el espacio entre el checkbox y el label */
  display:flex;
  align-items: center;
  vertical-align: middle; /* Añade vertical-align middle para alinear verticalmente los elementos */

}
.form-check-input{
    display:flex;
    align-items: center;
    vertical-align: middle; /* Añade vertical-align middle para alinear verticalmente los elementos */

}
.btn-primary {
  background-color: #f76b6a;
  color: #fff;
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.2s ease-in-out;
  margin-top: 10px;
}
.fs-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
}
.fs-modal-header .close {
        order: 1;
}
.fs-modal-header .alert {
        order: 2;
        margin-right: auto;
}
.btn-primary:hover {
  background-color: #f32321;
}
.close {
  background-color: #f76b6a ;
  color: #fff;
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.2s ease-in-out;
  margin-left: auto;
}
.close:hover{
    background-color: #f32321;
}

#emailError{
    color:red;
}
#fromError{
    color:red;
}
#toError{
    color:red;
}
.button-group{
    display: flex;
    justify-content: space-between;
    margin-right: 20px;
}
.inputGroup{
    margin-right: auto;
}
</style>

<?php foreach ($licenseKeys as $productId => $licenseKeyData): ?>
    <?php $product = wc_get_product($productId); ?>

    <h3 class="product-name">
        <?php if ($product): ?>
            <a href="<?php echo esc_url(get_post_permalink($productId)); ?>">
                <span><?php echo ($licenseKeyData['name']); ?></span>
            </a>
        <?php else: ?>
            <span><?php echo __('Product', 'license-manager-for-woocommerce') . ' #' . $productId; ?></span>
        <?php endif; ?>
    </h3>

    <table class="shop_table shop_table_responsive my_account_orders">
        <thead>
        <tr>
            <th class="license-key"><?php _e('License key', 'license-manager-for-woocommerce'); ?></th>
            <th class="activation"><?php _e('Activation status', 'license-manager-for-woocommerce'); ?></th>
            <th class="valid-until"><?php _e('Valid until', 'license-manager-for-woocommerce'); ?></th>
            <th class="actions"></th>
        </tr>
        </thead>

        <tbody>

        <?php
        /** @var LicenseResourceModel $license */
        foreach ($licenseKeyData['licenses'] as $license):
            $timesActivated    = $license->getTimesActivated() ? $license->getTimesActivated() : '0';
            $timesActivatedMax = $license->getTimesActivatedMax() ? $license->getTimesActivatedMax() : '&infin;';
            $order             = wc_get_order($license->getOrderId());
            ?>
            <tr>
                <td><span class="lmfwc-myaccount-license-key"><?php echo $license->getDecryptedLicenseKey(); ?></span></td>
                <td>
                    <span><?php esc_html_e($timesActivated); ?></span>
                    <span>/</span>
                    <span><?php echo $timesActivatedMax; ?></span>
                </td>
                <td><?php
                    if ($license->getExpiresAt()) {
                        $date = new \DateTime($license->getExpiresAt());
                        printf('<b>%s</b>', $date->format($dateFormat));
                    }
                    ?></td>
                <td class="license-key-actions">
                    <div style="display: flex; justify-content: space-between; margin-right: 20px;">
                        <div class="button-group">
                            <?php if (Settings::get('lmfwc_allow_users_to_activate')): ?>
                                <form method="post" style="margin: 0px;margin-right: 5px;">
                                    <input type="hidden" name="license" value="<?php echo $license->getDecryptedLicenseKey();?>"/>
                                    <input type="hidden" name="action" value="activate">
                                    <?php wp_nonce_field('lmfwc_myaccount_activate_license'); ?>
                                    <button class="button" type="submit"><?php _e('Activate', 'license-manager-for-woocommerce');?></button>
                                </form>
                            <?php endif; ?>

                                <button class="button" name="send" id="btnOpenMod" onclick="openModal(event)" data-license="<?php echo $license->getDecryptedLicenseKey();?>" data-expires="<?php echo $license->getExpiresAt();?>" style="margin: auto; margin-right: 5px;"> <?php _e('Send', 'license-manager-for-woocommerce');?></button>
                            <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="button view" style="margin:auto; box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.5);"><?php _e('Order', 'license-manager-for-woocommerce');?></a>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
<?php endforeach; ?>
<div class="modal" style="display: none;" id="successMessageModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thank You!</h5>
        <button type="button" class="close" id="successCloseButton" onclick="closeSuccessModal()" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Your message has been successfully sent.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeSuccessModal()" style="margin-top:20px;" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="modal" style="display: none;" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Send certificates</h3>  
                <button type="button" class="close" id="btnCloseMod" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label>Please, complete the following information to start giving the gift card</label> 
            </div>
            <div class="container">
            <div class="form-inline" style="display:flex;">
                <div class="inputGroup">
                    <label for="InputEmail" class="form-label">Email address of the recipient</label>
                    <input type="name" name="email" class="form-control" id="InputEmail" aria-describedby="emailHelp" onkeyup="validateEmail()" required>
                    <div id="emailHelp" class="form-text">
                        We'll send the gift card to this email address.
                    </div>
                    <span id="emailError"></span> 
                </div>
                <div class="inputGroup">
                    <label for="InputFrom" class="form-label">From</label>
                    <input type="name" name="from" class="form-control" id="InputFrom" aria-details="fromHelp" onkeyup="validateInputs()" required>
                    <div id="fromHelp" class="form-text">
                        This will be the name of the sender.
                    </div>
                    <span id="fromError"></span> 
                </div>
                <div class="inputGroup">
                    <label for="InputTo" class="form-label">To</label>
                    <input type="name" name="to" class="form-control" id="InputTo" aria-details="toHelp" onkeyup="validateInputs()" required>
                    <div id="toHelp" class="form-text">
                        This will be the name of the recipient.
                    </div>
                    <span id="toError"></span> 
                </div>
            </div>

                <div class="mb-3" id="MessageInputDiv" style="display: none;">
                    <label for="InputMessage" class="form-label">Personalized Message</label>
                    <input type="name" name="message" class="form-control" id="InputMessage">
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <label for="IncludeMessage" class="form-check-label" id="IncludeMessage">Include a personalized message? </label>
                        <input class="form-check-input" type="checkbox" id="IncludeMessageCheckbox" onclick="toggleMessageInput()">
                        <label class="form-check-label" for="IncludeMessageCheckbox" id="checkboxText">Yes</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="InputLanguage" class="form-label" id="textInputLanguage">Which language would you like to send this certificate?</label>
                    <select name="language" class="form-control" id="InputLanguage" onchange="changeFormAction()">
                        <option value="" selected disabled hidden>Select language</option>
                        <option value="english">English</option>
                        <option value="spanish">Spanish</option>
                    </select>
                </div>
                <div class="mb-3" id="categoryDiv">
                    <label for="selectCategory" class="form-label" id="labelSelectCategory">Please select your category design</label>
                    <select name="category" class="form-control" id="selectCategory" onchange="showImages(event)">
                        <option value="" selected disabled hidden>Select category</option>
                        <?php foreach ($categoriesList as $category): ?>
                            <option value="<?php echo $category['id']; ?>"><?php echo $category['category_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="container" style="border-top: none; margin-top: 0;" id="imageContainer">
                    <?php foreach ($categorias as $category): ?>
                        <div class="imagesToHide" id="<?php echo $category['nombre']; ?>" style="display:none;">
                            <h2><?php echo $category['nombre']; ?></h2>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    
                                        <div class="image-container">
                                            <?php foreach ($category["imagenes"] as $imagen): ?>
                                            <div class="image-wrapper">
                                                <img src="<?php echo $imagen['url']; ?>" alt="Card image cap">
                                                <div class="button-container">
                                                    <button style="display: block;" data-url="<?php echo $imagen['url']; ?>" id="enviarCorreo" onclick="sendMail(event)" name="send" class="btn btn-primary">Send Mail</button>
                                                    <button data-url="<?php echo $imagen['url']; ?>" id="mailPreview" name="preview" class="btn btn-primary" onclick="previewEmail(event)">Preview</button>
                                                </div>
                                            </div>
                                            <?php endforeach; ?> 
                                        </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function checkLanguageSelection() {
            var languageSelect = document.getElementById("InputLanguage");
            var categoryDiv = document.getElementById("categoryDiv");
            
            if (languageSelect.value === "") {
                categoryDiv.style.display = "none";  
            } else {
                categoryDiv.style.display = "block";  
            }
        }
        checkLanguageSelection();
        var languageSelect = document.getElementById("InputLanguage");
        languageSelect.addEventListener("change", checkLanguageSelection);
    });
    function validateEmail() {
        var emailInput = document.getElementById('InputEmail');
        var emailError = document.getElementById('emailError');

        if (!emailInput.value.match(/^[A-Za-z\._\-0-9]*[@][A-Za-z]*[\.][a-z]{2,4}$/)) {
            emailError.innerHTML = "Invalid email address, please enter a valid one."
            emailInput.style.borderColor="red";
            disableButtons();
            return false;
        } else {
            emailError.innerHTML="";
            emailInput.style.borderColor="green";
            enableButtons();
            return true;    
        }
    }
    function disableButtons(){
        document.getElementById("enviarCorreo").disabled = true;
        document.getElementById("mailPreview").disabled = true;
    }
    function enableButtons(){
        document.getElementById("enviarCorreo").disabled = false;
        document.getElementById("mailPreview").disabled = false;
    }
    function validateInputs(){
        var fromInput =document.getElementById('InputFrom');
        var toInput=document.getElementById('InputTo');
        var fromError=document.getElementById('fromError');
        var toError=document.getElementById('toError');
        var categoryDiv=document.getElementById('categoryDiv');
        var imageContainer=document.getElementById('imageContainer');
        
        if (fromInput.value===""){
            fromError.innerHTML="Please, fill in the From field";
            fromInput.style.borderColor="red";
            disableButtons();
            categoryDiv.style.display="none";
            imageContainer.style.display="none";
            return false;
        }
        if (toInput.value===""){
            toError.innerHTML="Please, fill in the To field";
            toInput.style.borderColor="red";
            disableButtons();
            categoryDiv.style.display="none";
            imageContainer.style.display="none";
            return false;
        }else {
            fromError.innerHTML="";
            toError.innerHTML="";
            fromInput.style.borderColor="green";
            toInput.style.borderColor="green";
            categoryDiv.style.display="block";
            imageContainer.style.display="block";
            enableButtons();
            return true;
        }
    }
    function validateForm() {
        var email = document.getElementById("InputEmail").value;
        var from = document.getElementById("InputFrom").value;
        var to = document.getElementById("InputTo").value;
        var messageInputDiv = document.getElementById("MessageInputDiv");
        var includeMessageCheckbox = document.getElementById("IncludeMessageCheckbox");
        var includeMessage = document.getElementById("IncludeMessage");
        var checkboxText = document.getElementById("checkboxText");
        var inputLanguage = document.getElementById("InputLanguage");
        var textInputLanguage = document.getElementById("textInputLanguage");

        if (email.trim() === "" || from.trim() === "" || to.trim() === "") {
            messageInputDiv.style.display = "none";
            includeMessageCheckbox.style.display = "none";
            includeMessage.style.display = "none";
            checkboxText.style.display = "none";
            inputLanguage.style.display = "none";
            textInputLanguage.style.display="none";
        }
        else {
                    includeMessageCheckbox.style.display = "block";
                    includeMessage.style.display = "block";
                    checkboxText.style.display = "block";
                    inputLanguage.style.display = "block";
                    textInputLanguage.style.display="block";
        }
    }
    function toggleMessageInput() {
        var messageInputDiv = document.getElementById("MessageInputDiv");
        var includeMessageCheckbox = document.getElementById("IncludeMessageCheckbox");
        
        if (includeMessageCheckbox.checked) {
            messageInputDiv.style.display = "block";
        } else {
            messageInputDiv.style.display = "none";
        }
    }
    
    function changeFormAction() {
        var languageSelect = document.getElementById("InputLanguage");
        var form = document.getElementById("certificateForm");
        let url = "";
        if (languageSelect.value === "english") {
            url = "../../wp-content/plugins/license-manager-for-woocommerce/email/enviar_correo_english.php";
        } else if (languageSelect.value === "spanish") {
            url = "../../wp-content/plugins/license-manager-for-woocommerce/email/enviar_correo_spanish.php";
        }
        return url;
    }
    function showImages(event) {
        var imagenes = Array.from(document.querySelectorAll(".container>.imagesToHide"));
        imagenes.forEach(e =>{
            e.style.display = "none";
        });
        var select = event.target;
        var selectedOption = select.options[select.selectedIndex];
        var nombre = selectedOption.text;
        document.getElementById(nombre).style.display = "block";
    }

    function openModal(event){
        var modal = document.getElementById('myModal');
        modal.style.display ='block';
        let licencia = event.target.getAttribute('data-license');
        let expiresAt =event.target.getAttribute('data-expires');
        window.expiresAt= expiresAt;
        window.licencia = licencia;
        console.log(window.licencia);
        console.log(window.expiresAt);
    }
    function success() {
        var successMessageModal= document.getElementById('successMessageModal');
        var idModal = document.getElementById('myModal');
        successMessageModal.style.display = "block";
        idModal.style.display = "none";
    }
    function closeSuccessModal() {
        var successModal =document.getElementById('successMessageModal');
        successModal.style.display ='none';
    }
    function enviarCorreoElectronico(destinatario, from, to, message, url, urlToPost, licencia, expiresAt) {
        const data = {
            destinatario,
            from,
            to,
            message,
            url,
            licencia,
            expiresAt
        };
        fetch(urlToPost, {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
            'Content-Type': 'application/json'
            }
        })
        .then(response => response.text())
        .then(data => {
            success();
        })
        .catch(error => {
            console.error(error); // Manejar error de la solicitud
        });
    }
    var modal = document.getElementById('myModal');
    var btnOpenMod = document.getElementById('btnOpenMod');
    var btnCloseMod = document.getElementById('btnCloseMod');

    btnCloseMod.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    function previewEmail(event) {

        event.preventDefault();
        console.log(event.target);

        let destinatario = document.getElementById('InputEmail').value;
        let from = document.getElementById('InputFrom').value;
        let to = document.getElementById('InputTo').value;
        let url = encodeURI(event.target.getAttribute('data-url'));
        console.log(url);
        let message = document.getElementById('InputMessage').value;
        let urlToPost = changeFormAction();
        var languageSelect =document.getElementById('InputLanguage');
        if(languageSelect.value === "spanish"){
        var emailHTML = `
        <html>
    <head>
      <meta charset="utf-8">
      <style>
        .gift-card {
          background-color: #f9f9f9;
          border: 1px solid #ddd;
          border-radius: 4px;
          padding: 0px;
          text-align: center;
          font-family: Arial, sans-serif;
          font-size: 16px;
        }
        
        .gift-card-header {
          margin-bottom: 10px;
          width:100%;
          text-align: center;
        }
        
        .gift-card-content {
          margin-bottom:0;
          margin-left: 10px;
          margin-top: 0px;
          text-align: left;
          font-weight: bold;
        }
        .gift-card-content-modal {
          margin-bottom:0;
          margin-left: 10px;
          margin-top: 0px;
          text-align: left;
        }
        
        .gift-card-button {
          background-color: #f76b6a;
          border: none;
          color: #FFFFFF;
          padding: 10px 20px;
          text-align: center;
          text-decoration: none;
          display: inline-block;
          font-size: 16px;
          border-radius: 4px;
        }
        .gift-card-button.custom-color {
          color: #FFFFFF; /* Cambiar el color del texto */
        }
        .textContainer {
            text-align: justify;
            margin-left: 20px;
            margin-bottom: 0px;
            margin-top: 0px;
            font-weight: normal;
            
        }
        
       .gift-card li {
        text-align: left;
       }

      </style>
    </head>
    <body>
      <div class="gift-card" style="text-align:center; margin :auto;"> 
        <img style="width: 100%; height: 100%;" src="${url}" alt="Gift Card"/>
        <h2 class="gift-card-header">Felicidades!</h2>
        <p class="gift-card-content-body">Has recibido una tarjeta de regalo.</p>
        <div class="container" style="display: flex; vertical-align: baseline;">
            <p class="gift-card-content">De:</p>
            <p class="gift-card-content-modal" style="margin-left: 15px;">${from}</p>
        </div>
        <div class="container" style="display: flex; vertical-align: baseline; ">
            <p class="gift-card-content">Para:</p>
            <p class="gift-card-content-modal" style="margin-left: 15px;">${to}</p>
        </div>
        <div class="container" style="display: flex; vertical-align: baseline; ">
            <p class="gift-card-content">Mensaje:</p>
            <p class="gift-card-content-modal" style="margin-left: 15px;">${message}</p>
        </div>
        <div class="container" style="display: flex; vertical-align: baseline; ">
            <p class="gift-card-content">Clave de licencia:</p>
            <p class="gift-card-content-modal" style="margin-left: 15px;"> ${window.licencia}.</p>
        </div>
        <div class="container" style="display: flex; vertical-align: baseline; ">
            <p class="gift-card-content">Fecha de expiración:</p>
            <p class="gift-card-content-modal" style="margin-left: 15px;"> ${window.expiresAt}.</p>
        </div>
        <p class="gift-card-content-modal" style="margin-top: 20px;">El viaje debe ser reservado antes de la fecha de expiración indicada, debes crear una cuenta en Certiviaje.com para poder redimir tu clave de licencia y reservar tus viajes.</p>
        <h3 class="gift-card-content" style="margin-top: 20px; color: #f76b6a;">Es fácil redimir</h3>
    
          <h4 class="textContainer" style="font-weight: bold;">1. Ve a nuestro sitio web <a style="color: #f76b6a;" href="https://certiviaje.com"> www.certiviaje.com</a>. </h4>
            <div style="display: flex; vertical-align: baseline;">
                <h4 class="textContainer" style="font-weight: bold;">2. Accede a tu cuenta -</h4>
                <h4 class="textContainer" style="margin-left: 5px; margin-bottom: 0px; margin-top: 0px; font-weight: normal;">Haz click en "Crear cuenta", completa tus datos y elige una contraseña.</h>
            </div>
            <div style="display:flex;">
                <h4 class="textContainer" style="font-weight: bold;">3. Registra tu clave de licencia -</h4>
                <h4 class="textContainer" style="margin-left: 5px; margin-bottom: 0px;">Ingresa la clave de licencia y registrala en tu nueva cuenta.</h4>
            </div>
            <div style="display:flex;">
                <h4 class="textContainer" style="font-weight: bold;">4. Encuentra tu vacacion -</h4>
                <h4 class="textContainer" style="margin-left: 5px; margin-bottom: 0px; ">Elige 'Explorar Hoteles', selecciona el certifcado que deseas utilizar</h4>
            </div>
            <h4 class="textContainer" style="margin-left: 40px; margin-bottom: 0px;">y da click en 'explorar o redimir certificado' en el botón acción.</h4>
            <div style="display:flex;">
                <h4 class="textContainer" style="font-weight: bold;">5. Listo para viajar! -</h4>
                <h4 class="textContainer" style="margin-left: 5px; margin-bottom: 0px;">Elige el destino, fechas, tipo de habitación y completa el proceso de </h4>
            </div>
            <h4 class="textContainer" style="margin-left: 40px; margin-bottom: 0px;">reservación.</h4>
            <h4 class="textContainer" style="font-weight: bold;">6. Disfruta de tu regalo!</h4>
            
        <a href="https://certiviaje.com" style="color:#FFFFFF; margin-top: 10px;"; class="gift-card-button">Canjear</a>
      </div>
    </body>
    </html>';
        `;
    } else if(languageSelect.value === 'english' || languageSelect.value === '') {
        var emailHTML = `
        <html>
    <head>
      <meta charset="utf-8">
      <style>
        .gift-card {
          background-color: #f9f9f9;
          border: 1px solid #ddd;
          border-radius: 4px;
          padding: 0px;
          text-align: center;
          font-family: Arial, sans-serif;
          font-size: 16px;
        }
        
        .gift-card-header {
          margin-bottom: 10px;
          width:100%;
          text-align: center;
        }
        
        .gift-card-content {
          margin-bottom:0;
          margin-left: 10px;
          margin-top: 0px;
          text-align: left;
          font-weight: bold;
        }
        .gift-card-content-modal {
          margin-bottom:0;
          margin-left: 10px;
          margin-top: 0px;
          text-align: left;
        }
        
        .gift-card-button {
          background-color: #f76b6a;
          border: none;
          color: #FFFFFF;
          padding: 10px 20px;
          text-align: center;
          text-decoration: none;
          display: inline-block;
          font-size: 16px;
          border-radius: 4px;
        }
        .gift-card-button.custom-color {
          color: #FFFFFF; /* Cambiar el color del texto */
        }
        .textContainer {
            text-align: justify;
            margin-left: 20px;
            margin-bottom: 0px;
            margin-top: 0px;
            font-weight: normal;
            
        }
        
       .gift-card li {
        text-align: left;
       }

      </style>
    </head>
    <body>
      <div class="gift-card" style="text-align:center; margin :auto;"> 
        <img style="width: 100%; height: 100%;" src="${url}" alt="Gift Card"/>
        <h2 class="gift-card-header">Congratulations!</h2>
        <p class="gift-card-content-body">You have received a gift card.</p>
        <div class="container" style="display: flex; vertical-align: baseline;">
            <p class="gift-card-content">From:</p>
            <p class="gift-card-content-modal" style="margin-left: 15px;">${from}</p>
        </div>
        <div class="container" style="display: flex; vertical-align: baseline; ">
            <p class="gift-card-content">To:</p>
            <p class="gift-card-content-modal" style="margin-left: 15px;">${to}</p>
        </div>
        <div class="container" style="display: flex; vertical-align: baseline; ">
            <p class="gift-card-content">Message:</p>
            <p class="gift-card-content-modal" style="margin-left: 15px;">${message}</p>
        </div>
        <div class="container" style="display: flex; vertical-align: baseline; ">
            <p class="gift-card-content">License key:</p>
            <p class="gift-card-content-modal" style="margin-left: 15px;"> ${window.licencia}.</p>
        </div>
        <div class="container" style="display: flex; vertical-align: baseline; ">
            <p class="gift-card-content">Expiration Date:</p>
            <p class="gift-card-content-modal" style="margin-left: 15px;"> ${window.expiresAt}.</p>
        </div>
        <p class="gift-card-content-modal" style="margin-top: 20px;">The trip must be reserved before the indicated expiration date, you must create an account at Certiviaje.com in order to redeem your license key and book your trips.</p>
        <h3 class="gift-card-content" style="margin-top: 20px; color: #f76b6a;">It's easy to redeem.</h3>
    
          <h4 class="textContainer" style="font-weight: bold;">1. See our website <a style="color: #f76b6a;" href="https://certiviaje.com"> www.certiviaje.com</a>. </h4>
            <div style="display: flex; vertical-align: baseline;">
                <h4 class="textContainer" style="font-weight: bold;">2. Access to your account -</h4>
                <h4 class="textContainer" style="margin-left: 5px; margin-bottom: 0px; margin-top: 0px; font-weight: normal;">Click ok "Create Account", fill in your details and choose a password.</h>
            </div>
            <div style="display:flex;">
                <h4 class="textContainer" style="font-weight: bold;">3. Register your license key -</h4>
                <h4 class="textContainer" style="margin-left: 5px; margin-bottom: 0px;">Enter the license key and register it in your new account.</h4>
            </div>
            <div style="display:flex;">
                <h4 class="textContainer" style="font-weight: bold;">4. Find your vacation -</h4>
                <h4 class="textContainer" style="margin-left: 5px; margin-bottom: 0px; ">Choose 'Explore Hotels', select the certificate you want to use</h4>
            </div>
            <h4 class="textContainer" style="margin-left: 40px; margin-bottom: 0px;">and click on 'browse or redeem certificate'.</h4>
            <div style="display:flex;">
                <h4 class="textContainer" style="font-weight: bold;">5. Ready to travel! -</h4>
                <h4 class="textContainer" style="margin-left: 5px; margin-bottom: 0px;">CHoose the destination, dates, type of room and complete the process of </h4>
            </div>
            <h4 class="textContainer" style="margin-left: 40px; margin-bottom: 0px;">reservation.</h4>
            <h4 class="textContainer" style="font-weight: bold;">6. Enjoy your gift!</h4>
            
        <a href="https://certiviaje.com" style="color:#FFFFFF; margin-top: 10px;"; class="gift-card-button">Redeem</a>
      </div>
    </body>
    </html>';
        `;
    }

        // Mostrar el HTML del correo generado en una ventana emergente o en un div dentro de la página.
        // Aquí puedes implementar tu propio código para mostrar la vista previa según tus necesidades.

        // Ejemplo de mostrar la vista previa en una ventana emergente:
        var newWindow = window.open("", "Email Preview");
        newWindow.document.write(emailHTML);
        newWindow.document.close();
    }
    function sendMail(event) {
        event.preventDefault();
        console.log(event.target);
        
        let destinatario = document.getElementById('InputEmail').value;
        let from = document.getElementById('InputFrom').value;
        let to = document.getElementById('InputTo').value;
        let url = encodeURI(event.target.getAttribute('data-url'));
        console.log(url);
        let message = document.getElementById('InputMessage').value;
        let urlToPost = changeFormAction();

        enviarCorreoElectronico(destinatario, from, to, message, url, urlToPost,window.licencia,window.expiresAt);
    }
    document.getElementById("InputEmail").oninput = validateForm;
    document.getElementById("InputFrom").oninput = validateForm;
    document.getElementById("InputTo").oninput = validateForm;

    // Ejecutar validateForm() al cargar la página
    validateForm();
</script>
