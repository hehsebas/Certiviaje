<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['formDesign']) && $_FILES['formDesign']['error'] === UPLOAD_ERR_OK) {
        $type = $_FILES['formDesign']['type'];
        $name = $_FILES['formDesign']['name'];
        $tmp_name = $_FILES['formDesign']['tmp_name'];
        $error = $_FILES['formDesign']['error'];
        $size = $_FILES['formDesign']['size'];

        $image_data = array(
            'name'     => $name,
            'type'     => $type,
            'tmp_name' => $tmp_name,
            'error'    => $error,
            'size'     => $size
        );

        $resultado = subirImagen($image_data);
    } else {
        echo "Error: No se ha seleccionado ning¨²n archivo o ha ocurrido un error en la carga.";
    }
}

function subirImagen($image_data) {
    $filename = $_FILES['formDesign']['name'];
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    // Generate a unique filename for the image with the correct extension
    $filename_with_extension = wp_unique_filename(wp_upload_dir()['path'], 'image.' . $extension);

    // Create the image file in the uploads directory
    $file_path = wp_upload_dir()['path'] . '/' . $filename_with_extension;

    // Read the file contents
    $file_contents = file_get_contents($image_data['tmp_name']);

    // Save the file
    file_put_contents($file_path, $file_contents);

    // Prepare the image data for insertion
    $image_data = array(
        'name'     => $filename_with_extension,
        'type'     => $image_data['type'],
        'tmp_name' => $file_path,
        'error'    => 0,
        'size'     => filesize($file_path)
    );

    // Upload the image to WordPress media library
    $attachment_id = media_handle_sideload($image_data, 0);

    if (is_wp_error($attachment_id)) {
        // Handle the error case
        echo "Error: No se subio la imagen " . $attachment_id->get_error_message();
        return false;
    } else {
        echo "Subida satisfactoria: " . $attachment_id;
        return $attachment_id;
    }
}

global $wpdb;

$table_name = $wpdb->prefix . 'consecutivo';
$query = "CREATE TABLE IF NOT EXISTS $table_name (
    id INT(11) NOT NULL AUTO_INCREMENT,
    consecutivo INT(11) NOT NULL,
    PRIMARY KEY (id)
) $charset_collate;";
