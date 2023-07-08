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
#messageError{
    color: red;
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
        <h5 class="modal-title">Gracias!</h5>
        <button type="button" class="close" id="successCloseButton" onclick="closeSuccessModal()" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><label><?php _e('Tu mensaje ha sido enviado satisfactoriamente', 'license-manager-for-woocommerce');?></label></p>
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
                <h3><label><?php _e('Enviar tarjeta de regalo', 'license-manager-for-woocommerce');?></label></h3>  
                <button type="button" class="close" id="btnCloseMod" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label>Por favor, completa la información para enviar la tarjeta de regalo.</label> 
            </div>
            <div class="container">
            <div class="form-inline" style="display:flex;">
                <div class="inputGroup" style="margin-right:10px;">
                    <label for="InputEmail" class="form-label">Email del destinatario.</label>
                    <input type="name" name="email" class="form-control" id="InputEmail" aria-describedby="emailHelp" onkeyup="validateEmail()" required>
                    <div id="emailHelp" class="form-text">
                        Enviaremos la tarjeta de regalo a esta dirección email.
                    </div>
                    <span id="emailError"></span> 
                </div>
                <div class="inputGroup" style="margin-right:10px;">
                    <label for="InputFrom" class="form-label">De</label>
                    <input type="name" name="from" class="form-control" id="InputFrom" aria-details="fromHelp" onkeyup="validateInputs()" required>
                    <div id="fromHelp" class="form-text">
                        Este será el nombre del que envia la tarjeta.
                    </div>
                    <span id="fromError"></span> 
                </div>
                <div class="inputGroup" style="margin-right:10px;">
                    <label for="InputTo" class="form-label">Para</label>
                    <input type="name" name="to" class="form-control" id="InputTo" aria-details="toHelp" onkeyup="validateInputs()" required>
                    <div id="toHelp" class="form-text">
                        Este será el nombre del que recibe la tarjeta.
                    </div>
                    <span id="toError"></span> 
                </div>
            </div>

                <div class="mb-3" id="MessageInputDiv" style="display: none;">
                    <label for="InputMessage" class="form-label">Mensaje personalizado.</label>
                    <input type="name" name="message" class="form-control" id="InputMessage" onkeyup="validateMessageInput()">
                    <div id="messageHelp" class="form-text">
                        Este mensaje aparecerá en la tarjeta de regalo.
                    </div>
                    <span id="messageError"></span>
                </div>
                <div class="mb-3" id="includeMessageDiv">
                    <div class="form-check">
                        <label for="IncludeMessage" class="form-check-label" id="IncludeMessage">Deseas incluir un mensaje personalizado? </label>
                        <input class="form-check-input" type="checkbox" id="IncludeMessageCheckbox" onclick="toggleMessageInput()">
                        <label class="form-check-label" for="IncludeMessageCheckbox" id="checkboxText">Sí</label>
                    </div>
                </div>
                <div class="mb-3" id="includeLanguageDiv">
                    <label for="InputLanguage" class="form-label" id="textInputLanguage">En qué lenguaje te gustaría enviar tu tarjeta de regalo?</label>
                    <select name="language" class="form-control" id="InputLanguage" onchange="changeFormAction()">
                        <option value="" selected disabled hidden>Selecciona lenguaje</option>
                        <option value="english">English</option>
                        <option value="spanish">Español</option>
                    </select>
                </div>
                <div class="mb-3" id="categoryDiv">
                    <label for="selectCategory" class="form-label" id="labelSelectCategory">Por favor, selecciona la categoría de tu diseño.</label>
                    <select name="category" class="form-control" id="selectCategory" onchange="showImages(event)">
                        <option value="" selected disabled hidden>Seleccionar categoría.</option>
                        <?php foreach ($categoriesList as $category): ?>
                            <option value="<?php echo $category['id']; ?>"><?php echo $category['category_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="container" style="border-top: none; margin-top: 0;" id="imageContainer">
                    <?php foreach ($categorias as $category): ?>
                        <div class="imagesToHide" id="<?php echo $category['nombre']; ?>" style="display:none;">
                            <label style="margin-bottom:10px;">Da click en la imagen para previsualizar o enviar el diseño con la imagen seleccionada.</label>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    
                                        <div class="image-container">
                                            <?php foreach ($category["imagenes"] as $imagen): ?>
                                            <div class="image-wrapper">
                                                <img src="<?php echo $imagen['url']; ?>" alt="Card image cap">
                                                <div class="button-container">
                                                    <button style="display: block;" data-url="<?php echo $imagen['url']; ?>" id="enviarCorreo" onclick="sendMail(event)" name="send" class="btn btn-primary">Enviar email.</button>
                                                    <button data-url="<?php echo $imagen['url']; ?>" id="mailPreview" name="preview" class="btn btn-primary" onclick="previewEmail(event)">Previsualizar.</button>
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
            emailError.innerHTML = "Email inválido, por favor ingresa uno válido."
            emailInput.style.borderColor="red";
            document.getElementById('includeMessageDiv').style.display = "none";            return false;
        } else {
            emailError.innerHTML="";
            emailInput.style.borderColor="green";
            document.getElementById('includeMessageDiv').style.display = "block";
            return true;    
        }
    }
    function validateMessageInput(){
        var messageInputDiv = document.getElementById("InputMessage");
        var messageError = document.getElementById("messageError");
        if(messageInputDiv.value ===""){
            messageError.innerHTML="Por favor, llena el campo de mensaje.";
            messageInputDiv.style.borderColor="red";
            return false;
        }
        else{
            messageError.innerHTML="";
            messageInputDiv.style.borderColor="green";
            return true;
        }
    }
    function validateInputs(){
        var fromInput =document.getElementById('InputFrom');
        var toInput=document.getElementById('InputTo');
        var fromError=document.getElementById('fromError');
        var toError=document.getElementById('toError');
        var categoryDiv=document.getElementById('categoryDiv');
        
        if (fromInput.value===""){
            fromError.innerHTML="Por favor, rellena el campo de remitente.";
            fromInput.style.borderColor="red";
            categoryDiv.style.display="none";
            return false;
        }
        if (toInput.value===""){
            toError.innerHTML="Por favor, llena el campo de destinatario.";
            toInput.style.borderColor="red";
            categoryDiv.style.display="none";
            return false;
        }else {
            fromError.innerHTML="";
            toError.innerHTML="";
            fromInput.style.borderColor="green";
            toInput.style.borderColor="green";
            document.getElementById("includeLanguageDiv").style.display="block";
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
    var inputEmail = document.getElementById('InputEmail');

    btnCloseMod.addEventListener('click', function() {
        modal.style.display = 'none';
        resetValues();
    });
    function resetValues(){
        document.getElementById("InputEmail").value = "";
        document.getElementById("InputEmail").style.borderColor = "";
        document.getElementById("InputFrom").value = "";
        document.getElementById("InputFrom").style.borderColor = "";
        document.getElementById("InputTo").value = "";
        document.getElementById("InputTo").style.borderColor = "";
        document.getElementById("InputMessage").value= "";
        document.getElementById("InputMessage").style.display= "none";
        document.getElementById("IncludeMessageCheckbox").checked=false;
        document.getElementById("InputLanguage").value="";
        document.getElementById("selectCategory").value="";
    }
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
      <html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">

<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"><!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]--><!--[if !mso]><!-->
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css"><!--<![endif]-->
	<style>
		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			padding: 0;
		}

		a[x-apple-data-detectors] {
			color: inherit !important;
			text-decoration: inherit !important;
		}

		#MessageViewBody a {
			color: inherit;
			text-decoration: none;
		}

		p {
			line-height: inherit
		}

		.desktop_hide,
		.desktop_hide table {
			mso-hide: all;
			display: none;
			max-height: 0px;
			overflow: hidden;
		}

		.image_block img+div {
			display: none;
		}

		@media (max-width:700px) {
			.desktop_hide table.icons-inner {
				display: inline-block !important;
			}

			.icons-inner {
				text-align: center;
			}

			.icons-inner td {
				margin: 0 auto;
			}

			.row-content {
				width: 100% !important;
			}

			.stack .column {
				width: 100%;
				display: block;
			}

			.mobile_hide {
				max-width: 0;
				min-height: 0;
				max-height: 0;
				font-size: 0;
				display: none;
				overflow: hidden;
			}

			.desktop_hide,
			.desktop_hide table {
				max-height: none !important;
				display: table !important;
			}
		}
	</style>
</head>

<body style="text-size-adjust: none; background-color: #f2f2f2; margin: 0; padding: 0;">
	<table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f2f2f2;">
		<tbody>
			<tr>
				<td>
					<table class="row row-1" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; width: 680px; margin: 0 auto;" width="680">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<div class="spacer_block block-1 mobile_hide" style="height:50px;line-height:50px;font-size:1px;">&#8202;</div>
													<table class="image_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="width:100%;">
																<div class="alignment" align="center" style="line-height:10px"><img src="https://marketing.tonsofleads.us/frontend/assets/files/images/Certificados-05.png" style="height: auto; display: block; border: 0; max-width: 219px; width: 100%;" width="219"></div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-2" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #73c7cf; background-image: url(https://d1oco4z2z1fhwp.cloudfront.net/templates/default/2936/Earth_Month_Hero_bg.jpg); background-position: top; background-repeat: no-repeat; width: 680px; margin: 0 auto;" width="680">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 60px; padding-top: 60px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<div class="spacer_block block-1" style="height:60px;line-height:60px;font-size:1px;">&#8202;</div>
													<table class="heading_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="text-align:center;width:100%;">
																<h1 style="margin: 0; color: #ffffff; direction: ltr; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; font-size: 33px; font-weight: normal; letter-spacing: 5px; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;"><span class="tinyMce-placeholder">HAS RECIBIDO</span></h1>
															</td>
														</tr>
													</table>
													<table class="heading_block block-3" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="text-align:center;width:100%;">
																<h1 style="margin: 0; color: #ffffff; direction: ltr; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; font-size: 33px; font-weight: normal; letter-spacing: 5px; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;"><span class="tinyMce-placeholder">UN VIAJE DE REGALO</span></h1>
															</td>
														</tr>
													</table>
													<div class="spacer_block block-4" style="height:55px;line-height:55px;font-size:1px;">&#8202;</div>
													<div class="spacer_block block-5" style="height:60px;line-height:60px;font-size:1px;">&#8202;</div>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-3" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #fff; width: 680px; margin: 0 auto;" width="680">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="heading_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="padding-bottom:15px;padding-left:30px;padding-right:30px;padding-top:40px;text-align:center;width:100%;">
																<h1 style="margin: 0; color: #00292c; direction: ltr; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; font-size: 26px; font-weight: normal; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;"><strong><span class="tinyMce-placeholder">¡Felicidades ${to}!</span></strong></h1>
															</td>
														</tr>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad" style="padding-bottom:15px;padding-left:30px;padding-right:30px;padding-top:15px;">
																<div style="font-family: sans-serif">
																	<div class style="font-size: 12px; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 18px; color: #000000; line-height: 1.5;">
																		<p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 27px;"><span style="font-size:18px;">${from} te ha regalado un viaje.</span></p>
																	</div>
																</div>
															</td>
														</tr>
													</table>
													<table class="paragraph_block block-3" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="color:#101112;direction:ltr;font-family:Roboto, Tahoma, Verdana, Segoe, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:center;mso-line-height-alt:19.2px;">
																	<p style="margin: 0;"><strong>${message}</strong></p>
																</div>
															</td>
														</tr>
													</table>
													<table class="paragraph_block block-4" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="color:#101112;direction:ltr;font-family:Roboto, Tahoma, Verdana, Segoe, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:center;mso-line-height-alt:19.2px;">
																	<p style="margin: 0;">El viaje debe ser reservado antes de la fecha de expiración indicada.</p>
																</div>
															</td>
														</tr>
													</table>
													<table class="paragraph_block block-5" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="color:#101112;direction:ltr;font-family:Roboto, Tahoma, Verdana, Segoe, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:center;mso-line-height-alt:19.2px;">
																	<p style="margin: 0; margin-bottom: 16px;"><strong>DATOS DE TU VIAJE:</strong></p>
																	<p style="margin: 0; margin-bottom: 16px;"><strong>Clave de licencia:</strong> ${window.licencia}.</p>
																	<p style="margin: 0; margin-bottom: 16px;"><strong>Fecha de expiración:</strong> ${window.expiresAt}.</p>
																	<p style="margin: 0;">&nbsp;</p>
																</div>
															</td>
														</tr>
													</table>
													<table class="heading_block block-6" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad">
																<h1 style="margin: 0; color: #bb571b; direction: ltr; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; font-size: 38px; font-weight: 700; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;"><span class="tinyMce-placeholder">¿Cómo validar tu viaje?</span></h1>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-4" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #fff; background-image: url(https://d1oco4z2z1fhwp.cloudfront.net/templates/default/2936/waves-bgd.png); background-position: top; background-repeat: no-repeat; width: 680px; margin: 0 auto;" width="680">
										<tbody>
											<tr>
												<td class="column column-1" width="33.333333333333336%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 30px; padding-left: 30px; padding-right: 30px; padding-top: 30px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="text_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="font-family: sans-serif">
																	<div class style="font-size: 12px; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #bb571b; line-height: 1.2;">
																		<p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:50px;">1</span></p>
																	</div>
																</div>
															</td>
														</tr>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad" style="padding-bottom:20px;">
																<div style="font-family: sans-serif">
																	<div class style="font-size: 12px; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 18px; color: #202020; line-height: 1.5;">
																		<p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 24px;"><span style="font-size:16px;">Accede a<a href="https://certificadodeviaje.com/" target="_blank" style="text-decoration: underline; color: #bb571b;" rel="noopener"> esta dirección</a> para validar tu viaje.</span></p>
																	</div>
																</div>
															</td>
														</tr>
													</table>
												</td>
												<td class="column column-2" width="33.333333333333336%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 30px; padding-left: 30px; padding-right: 30px; padding-top: 30px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="text_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="font-family: sans-serif">
																	<div class style="font-size: 12px; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #bb571b; line-height: 1.2;">
																		<p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:50px;">2</span></p>
																	</div>
																</div>
															</td>
														</tr>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad" style="padding-bottom:20px;">
																<div style="font-family: sans-serif">
																	<div class style="font-size: 12px; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 18px; color: #202020; line-height: 1.5;">
																		<p style="margin: 0; text-align: center; mso-line-height-alt: 24px;"><span style="font-size:16px;">Valida tu código para asegurar que éste aún esté vigente.</span></p>
																	</div>
																</div>
															</td>
														</tr>
													</table>
												</td>
												<td class="column column-3" width="33.333333333333336%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 30px; padding-left: 30px; padding-right: 30px; padding-top: 30px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="text_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="font-family: sans-serif">
																	<div class style="font-size: 12px; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #bb571b; line-height: 1.2;">
																		<p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:50px;">3</span></p>
																	</div>
																</div>
															</td>
														</tr>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad" style="padding-bottom:20px;">
																<div style="font-family: sans-serif">
																	<div class style="font-size: 12px; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 18px; color: #110f0f; line-height: 1.5;">
																		<p style="margin: 0; text-align: center; mso-line-height-alt: 24px;"><span style="font-size:16px;">Completa tus datos personales para canjear tu certificado por una reserva.<br></span></p>
																	</div>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-5" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #fbfbfb; width: 680px; margin: 0 auto;" width="680">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="width:100%;">
																<div class="alignment" align="center" style="line-height:10px"><img src="https://marketing.tonsofleads.us/frontend/assets/files/images/Certificados-05.png" style="height: auto; display: block; border: 0; max-width: 219px; width: 100%;" width="219"></div>
															</td>
														</tr>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellpadding="30" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="font-family: sans-serif">
																	<div class style="font-size: 12px; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 18px; color: #393d47; line-height: 1.5;">
																		<p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 18px;"><span style="font-size:12px;color:#999999;">Recibes esta notificación directamente desde <a href="https://certiviaje.com/" target="_blank" style="text-decoration: underline; color: #7d7d7d;" rel="noopener">Certiviaje.com</a>, empresa autorizada para la emisión de licencias y certificados de viaje con número de autorización ST39568 en el estado de FLORIDA, USA. Si ha recibido un certificado a través de un código de licencia de viaje, solo es posible su canje mediante <a href="https://certificadodeviaje.com" target="_blank" style="text-decoration: underline; color: #7d7d7d;" rel="noopener">https://certificadodeviaje.com</a>.</span></p>
																		<p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 18px;"><span style="font-size:12px;"><span style="color:#999999;">Nuestras notificaciones, correos y boletines, se envían teniendo en cuenta nuestra <a href="https://certiviaje.com/privacy-policy/" target="_blank" style="text-decoration: underline; color: #7d7d7d;" rel="noopener">Política de Privacidad</a> y <a href="https://certiviaje.com/terminos-y-condiciones-de-uso/" target="_blank" style="text-decoration: underline; color: #7d7d7d;" rel="noopener">Términos y Condiciones de Uso</a>.</span></span></p>
																		<p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 18px;"><span style="font-size:12px;color:#999999;">© 2023 Certiviaje.com. Derechos Reservados.<br></span></p>
																	</div>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				  <table class="row row-6" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; width: 680px; margin: 0 auto;" width="680">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<div class="spacer_block block-1" style="height:60px;line-height:60px;font-size:1px;">&#8202;</div>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
				</table></td>
			</tr>
		</tbody>
	</table><!-- End -->
</body>

</html>
        `;
    } else if(languageSelect.value === 'english' || languageSelect.value === '') {
        var emailHTML = `
        <html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">

<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"><!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]--><!--[if !mso]><!-->
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css"><!--<![endif]-->
	<style>
		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			padding: 0;
		}

		a[x-apple-data-detectors] {
			color: inherit !important;
			text-decoration: inherit !important;
		}

		#MessageViewBody a {
			color: inherit;
			text-decoration: none;
		}

		p {
			line-height: inherit
		}

		.desktop_hide,
		.desktop_hide table {
			mso-hide: all;
			display: none;
			max-height: 0px;
			overflow: hidden;
		}

		.image_block img+div {
			display: none;
		}

		@media (max-width:700px) {
			.desktop_hide table.icons-inner {
				display: inline-block !important;
			}

			.icons-inner {
				text-align: center;
			}

			.icons-inner td {
				margin: 0 auto;
			}

			.row-content {
				width: 100% !important;
			}

			.stack .column {
				width: 100%;
				display: block;
			}

			.mobile_hide {
				max-width: 0;
				min-height: 0;
				max-height: 0;
				font-size: 0;
				display: none;
				overflow: hidden;
			}

			.desktop_hide,
			.desktop_hide table {
				max-height: none !important;
				display: table !important;
			}
		}
	</style>
</head>

<body style="text-size-adjust: none; background-color: #f2f2f2; margin: 0; padding: 0;">
	<table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f2f2f2;">
		<tbody>
			<tr>
				<td>
					<table class="row row-1" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; width: 680px; margin: 0 auto;" width="680">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<div class="spacer_block block-1 mobile_hide" style="height:50px;line-height:50px;font-size:1px;">&#8202;</div>
													<table class="image_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="width:100%;">
																<div class="alignment" align="center" style="line-height:10px"><img src="https://marketing.tonsofleads.us/frontend/assets/files/images/Certificados-05.png" style="height: auto; display: block; border: 0; max-width: 219px; width: 100%;" width="219"></div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-2" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #73c7cf; background-image: url(https://d1oco4z2z1fhwp.cloudfront.net/templates/default/2936/Earth_Month_Hero_bg.jpg); background-position: top; background-repeat: no-repeat; width: 680px; margin: 0 auto;" width="680">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 60px; padding-top: 60px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<div class="spacer_block block-1" style="height:60px;line-height:60px;font-size:1px;">&#8202;</div>
													<table class="heading_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="text-align:center;width:100%;">
																<h1 style="margin: 0; color: #ffffff; direction: ltr; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; font-size: 33px; font-weight: normal; letter-spacing: 5px; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;"><span class="tinyMce-placeholder">You have received</span></h1>
															</td>
														</tr>
													</table>
													<table class="heading_block block-3" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="text-align:center;width:100%;">
																<h1 style="margin: 0; color: #ffffff; direction: ltr; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; font-size: 33px; font-weight: normal; letter-spacing: 5px; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;"><span class="tinyMce-placeholder">A GIFT TRIP&nbsp;</span></h1>
															</td>
														</tr>
													</table>
													<div class="spacer_block block-4" style="height:55px;line-height:55px;font-size:1px;">&#8202;</div>
													<div class="spacer_block block-5" style="height:60px;line-height:60px;font-size:1px;">&#8202;</div>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-3" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #fff; width: 680px; margin: 0 auto;" width="680">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="heading_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="padding-bottom:15px;padding-left:30px;padding-right:30px;padding-top:40px;text-align:center;width:100%;">
																<h1 style="margin: 0; color: #00292c; direction: ltr; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; font-size: 26px; font-weight: normal; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;"><strong><span class="tinyMce-placeholder">¡Congratulations ${to}</span></strong></h1>
															</td>
														</tr>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad" style="padding-bottom:15px;padding-left:30px;padding-right:30px;padding-top:15px;">
																<div style="font-family: sans-serif">
																	<div class style="font-size: 12px; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 18px; color: #000000; line-height: 1.5;">
																		<p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 27px;"><span style="font-size:18px;">${from} has given you a trip.</span></p>
																	</div>
																</div>
															</td>
														</tr>
													</table>
													<table class="paragraph_block block-3" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="color:#101112;direction:ltr;font-family:Roboto, Tahoma, Verdana, Segoe, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:center;mso-line-height-alt:19.2px;">
																	<p style="margin: 0;"><strong>${message}</strong></p>
																</div>
															</td>
														</tr>
													</table>
													<table class="paragraph_block block-4" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="color:#101112;direction:ltr;font-family:Roboto, Tahoma, Verdana, Segoe, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:center;mso-line-height-alt:19.2px;">
																	<p style="margin: 0;">Travel must be booked before the indicated expiration date.</p>
																</div>
															</td>
														</tr>
													</table>
													<table class="paragraph_block block-5" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="color:#101112;direction:ltr;font-family:Roboto, Tahoma, Verdana, Segoe, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:center;mso-line-height-alt:19.2px;">
																	<p style="margin: 0; margin-bottom: 16px;"><strong>DETAILS OF YOUR TRIP</strong></p>
																	<p style="margin: 0; margin-bottom: 16px;"><strong>LICENSE KEY:</strong> ${window.licencia}</p>
																	<p style="margin: 0; margin-bottom: 16px;"><strong>EXPIRATION DATE:</strong> ${window.expiresAt}</p>
																	<p style="margin: 0;">&nbsp;</p>
																</div>
															</td>
														</tr>
													</table>
													<table class="heading_block block-6" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad">
																<h1 style="margin: 0; color: #bb571b; direction: ltr; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; font-size: 38px; font-weight: 700; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;"><span class="tinyMce-placeholder">How to validate your trip?</span></h1>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-4" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #fff; background-image: url(https://d1oco4z2z1fhwp.cloudfront.net/templates/default/2936/waves-bgd.png); background-position: top; background-repeat: no-repeat; width: 680px; margin: 0 auto;" width="680">
										<tbody>
											<tr>
												<td class="column column-1" width="33.333333333333336%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 30px; padding-left: 30px; padding-right: 30px; padding-top: 30px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="text_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="font-family: sans-serif">
																	<div class style="font-size: 12px; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #bb571b; line-height: 1.2;">
																		<p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:50px;">1</span></p>
																	</div>
																</div>
															</td>
														</tr>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad" style="padding-bottom:20px;">
																<div style="font-family: sans-serif">
																	<div class style="font-size: 12px; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 18px; color: #202020; line-height: 1.5;">
																		<p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 24px;"><span style="font-size:16px;">Go to <a href="https://certificadodeviaje.com/" target="_blank" style="text-decoration: underline; color: #bb571b;" rel="noopener">this link</a> for validate your travel license.</span></p>
																	</div>
																</div>
															</td>
														</tr>
													</table>
												</td>
												<td class="column column-2" width="33.333333333333336%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 30px; padding-left: 30px; padding-right: 30px; padding-top: 30px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="text_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="font-family: sans-serif">
																	<div class style="font-size: 12px; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #bb571b; line-height: 1.2;">
																		<p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:50px;">2</span></p>
																	</div>
																</div>
															</td>
														</tr>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad" style="padding-bottom:20px;">
																<div style="font-family: sans-serif">
																	<div class style="font-size: 12px; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 18px; color: #202020; line-height: 1.5;">
																		<p style="margin: 0; text-align: center; mso-line-height-alt: 24px;"><span style="font-size:16px;">Validate your code to ensure that it is still valid.</span></p>
																	</div>
																</div>
															</td>
														</tr>
													</table>
												</td>
												<td class="column column-3" width="33.333333333333336%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 30px; padding-left: 30px; padding-right: 30px; padding-top: 30px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="text_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="font-family: sans-serif">
																	<div class style="font-size: 12px; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #bb571b; line-height: 1.2;">
																		<p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:50px;">3</span></p>
																	</div>
																</div>
															</td>
														</tr>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad" style="padding-bottom:20px;">
																<div style="font-family: sans-serif">
																	<div class style="font-size: 12px; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 18px; color: #110f0f; line-height: 1.5;">
																		<p style="margin: 0; text-align: center; mso-line-height-alt: 24px;"><span style="font-size:16px;">Complete your personal information to exchange your certificate for a reservation<br></span></p>
																	</div>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-5" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #fbfbfb; width: 680px; margin: 0 auto;" width="680">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="width:100%;">
																<div class="alignment" align="center" style="line-height:10px"><img src="https://marketing.tonsofleads.us/frontend/assets/files/images/Certificados-05.png" style="height: auto; display: block; border: 0; max-width: 219px; width: 100%;" width="219"></div>
															</td>
														</tr>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellpadding="30" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="font-family: sans-serif">
																	<div class style="font-size: 12px; font-family: Roboto, Tahoma, Verdana, Segoe, sans-serif; mso-line-height-alt: 18px; color: #393d47; line-height: 1.5;">
																		<p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 18px;"><span style="font-size:12px;color:#999999;">You receive this notification directly from <a href="https://certiviaje.com/" target="_blank" style="text-decoration: underline; color: #7d7d7d;" rel="noopener">Certiviaje.com</a>, company authorized to issue travel licenses and certificates with authorization number ST39568 in the state of FLORIDA, USA. If you have received a certificate via a travel license code, it can only be redeemed via <a href="https://certificadodeviaje.com" target="_blank" style="text-decoration: underline; color: #7d7d7d;" rel="noopener">https://certificadodeviaje.com</a>.</span></p>
																		<p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 18px;"><span style="font-size:12px;"><span style="color:#999999;">Our notifications, emails and newsletters are sent taking into account our <a href="https://certiviaje.com/privacy-policy/" target="_blank" style="text-decoration: underline; color: #7d7d7d;" rel="noopener">Privacy Policy</a> and <a href="https://certiviaje.com/terminos-y-condiciones-de-uso/" target="_blank" style="text-decoration: underline; color: #7d7d7d;" rel="noopener">Terms and conditions of use</a>.</span></span></p>
																		<p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 18px;"><span style="font-size:12px;color:#999999;">© 2023 Certiviaje.com. All rights reserved.<br></span></p>
																	</div>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				  <table class="row row-6" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; width: 680px; margin: 0 auto;" width="680">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<div class="spacer_block block-1" style="height:60px;line-height:60px;font-size:1px;">&#8202;</div>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
				</table></td>
			</tr>
		</tbody>
	</table><!-- End -->
</body>

</html>
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
