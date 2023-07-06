<?php
use LicenseManagerForWooCommerce\Models\Resources\License as LicenseResourceModel;
use LicenseManagerForWooCommerce\Settings;

defined('ABSPATH') || exit; 
?>

<h2><?php _e('Your license keys', 'license-manager-for-woocommerce'); ?></h2>
<style>.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-dialog {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    border-radius: 5px;
    padding: 20px;
    width: 400px;
    max-width: 90%;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    margin: 0;
}

.close {
    border: none;
    cursor: pointer;
}
.container {
                background-color: #f1f1f1; /* Color de fondo de la caja */
                padding: 20px; /* Espacio interno alrededor del contenido */
                margin-top: 20px; /* Margen superior de 10 píxeles */
                border-top: 1px solid gray; /* Borde superior de 1 píxel de grosor y color negro */
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
                    <div class="button-group">
                        <?php if (Settings::get('lmfwc_allow_users_to_activate')): ?>
                            <form method="post" style="display: inline-block; margin: 0;">
                                <input type="hidden" name="license" value="<?php echo $license->getDecryptedLicenseKey();?>"/>
                                <input type="hidden" name="action" value="activate">
                                <?php wp_nonce_field('lmfwc_myaccount_activate_license'); ?>
                                <button class="button" type="submit"><?php _e('Activate', 'license-manager-for-woocommerce');?></button>
                            </form>
                        <?php endif; ?>

                            <button class="button" name="send" id="btnOpenMod" onclick="openModal(event)" data-license="<?php echo $license->getDecryptedLicenseKey();?>"> <?php _e('Send', 'license-manager-for-woocommerce');?></button>
                        <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="button view"><?php _e('Order', 'license-manager-for-woocommerce');?></a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
<?php endforeach; ?>


<div class="modal" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal centrado en la mitad</h5>
                <button type="button" class="close" id="btnCloseMod" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Contenido del modal aquí.</p>
            </div>
                    <div class="container">
            <h3>Send certificates</h3>
            <label>Please, complete the following information to start giving the gift card</label> 
                <div class="mb-3, mt-3">
                    <label for="InputEmail" class="form-label">Email address of the recipient</label>
                    <input type="email" name="email" class="form-control" id="InputEmail" aria-describedby="emailHelp">
                    <div id="emailHelp" class="form-text">We'll send the gift card to this email address.</div>
                </div>
                <div class="mb-3">
                    <label for="InputFrom" class="form-label">From</label>
                    <input type="name" name="from" class="form-control" id="InputFrom" aria-details="nameHelp">
                    <div id="from" class="form-text"></div>
                </div>
                <div class="mb-3">
                    <label for="InputTo" class="form-label">To</label>
                    <input type="name" name="to" class="form-control" id="InputTo">
                </div>
                <div class="mb-3" id="MessageInputDiv" style="display: none;">
                    <label for="InputMessage" class="form-label">Personalized Message</label>
                    <input type="name" name="message" class="form-control" id="InputMessage">
                </div>
                <div class="mb-3">
                    <label for="IncludeMessage" class="form-check-label">Include a personalized message?</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="IncludeMessageCheckbox" onclick="toggleMessageInput()">
                        <label class="form-check-label" for="IncludeMessageCheckbox">
                            Yes
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="InputLanguage" class="form-label">Which language would you like to send this certificate?</label>
                    <select name="language" class="form-control" id="InputLanguage" onchange="changeFormAction()">
                        <option value="english">English</option>
                        <option value="spanish">Spanish</option>
                    </select>
                </div>
                <!-- <div class="mb-3">
                    <<label for="InputImage" class="form-label">Select an image</label>
<input type="file" class="form-control" id="InputImage">
</div> -->
<button id="enviarCorreo" name="send" class="btn btn-primary">Submit</button>



</div>
        </div>
    </div>
</div>

<script>
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
    function openModal(event){
        var modal = document.getElementById('myModal');
        modal.style.display = 'block';
        let licencia = event.target.getAttribute('data-license');
        window.licencia = licencia;
    }
    function enviarCorreoElectronico(destinatario, from, to, message, url, urlToPost, licencia) {
        const data = {
            destinatario,
            from,
            to,
            message,
            url,
            licencia
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
            alert('correo enviado');
        })
        .catch(error => {
            console.error(error); // Manejar error de la solicitud
        });
    }
    var modal = document.getElementById('myModal');
    var btnOpenMod = document.getElementById('btnOpenMod');
    var btnCloseMod = document.getElementById('btnCloseMod');
    

    btnOpenMod.addEventListener('click', function() {

    });

    btnCloseMod.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    var sendMail = document.getElementById('enviarCorreo');

    sendMail.addEventListener('click', function(e) {
        e.preventDefault();
        console.log(sendMail.getAttribute('data-license'));
        let destinatario = document.getElementById('InputEmail').value;
        let from = document.getElementById('InputFrom').value;
        let to = document.getElementById('InputTo').value;
        let url = "https://as2.ftcdn.net/v2/jpg/02/67/11/55/1000_F_267115523_nhJWtLVlhtYtqGkfVOIzhOCAjQRrejVI.jpg";
        let message = document.getElementById('InputMessage').value;
        let urlToPost = changeFormAction();
        enviarCorreoElectronico(destinatario, from, to, message, url, urlToPost,window.licencia);
    });

</script>
