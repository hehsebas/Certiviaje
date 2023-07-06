<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Test</title>
        <style>
            .container {
                background-color: #f1f1f1; /* Color de fondo de la caja */
                padding: 20px; /* Espacio interno alrededor del contenido */
                margin-top: 20px; /* Margen superior de 10 píxeles */
                border-top: 1px solid gray; /* Borde superior de 1 píxel de grosor y color negro */
            }
        </style>
            
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">  
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container">
            <h3>Send certificates</h3>
            <label>Please, complete the following information to start giving the gift card</label> 
            <form action="enviar_correo.php" method="post">
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
                <div class="mb-3">
                    <label for="InputMessage" class="form-label">Personalized Message</label>
                    <input type="name" name="message" class="form-control" id="InputMessage">
                </div>
                <!-- <div class="mb-3">
                    <label for="InputImage" class="form-label">Select an image</label>
                    <input type="file" class="form-control" id="InputImage">
                </div> -->
                <button type="submit" name="send" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </body>
</html>
