<?php

?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .form-select {
            -moz-appearance: none;
            -webkit-appearance: none;
            appearance: none;
            background-color: #fff;
            margin-right: auto;
            margin-top: 10px;
        }

        .form-label {
            margin-right: 10px;
            font-weight: bold;
        }

        .form-control {
            margin-top: 10px;
            width: -webkit-fill-available;
        }

        .input-group {
            align-items: baseline;
        }

        .input-group-label {
            margin-right: 10px;
        }

        .card {
            width: 100%;
            height: 100%;
        }
        .btn-primary {
            width: -webkit-fill-available;
        }
    </style>

</head>

<body>
    
    <div class="container">
        <form method="POST" enctype="multipart/form-data" action="admin.php?page=Subirdesign">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Upload new design</h5>
                    <div class="input-group">
                        <label class="input-group-label" for="input-group">Select the category of your design</label>
                        <select class="form-select" id="category" name="dropdown">
                            <option selected disabled>Select an option</option>
                            <option value="standar">Standard</option>
                            <option value="birthday">Birthday</option>
                            <option value="congratulations">Congratulations</option>
                            <option value="fathersday">Father's Day</option>
                            <option value="graduation">Graduation</option>
                            <option value="military">Military</option>
                            <option value="mothersday">Mother's Day</option>
                        </select>
                    </div>
                    <input class="form-control" type="file" name="formDesign" id="formDesign">
                    <button class="btn btn-primary mt-3" type="submit" value="Upload">Upload</button>
                </div>
            </div>
        </form>
    </div>

    <div class="container">
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Create New Category</h5>
                <form id="newCategoryForm">
                    <div class="input-group">
                        <label class="input-group-label" for="newCategoryInput">New Category Name:</label>
                        <input class="form-control" type="text" id="newCategoryInput">
                    </div>
                    <button class="btn btn-primary mt-3" type="button" onclick="addNewCategory()">Add Category</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openModal(modalId) {
            var modal = document.getElementById(modalId);
            var bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();
        }

        function addNewCategory() {
            var newCategoryInput = document.getElementById('newCategoryInput');
            var categorySelect = document.getElementById('category');

            if (newCategoryInput.value.trim() !== '') {
                var option = document.createElement('option');
                option.value = newCategoryInput.value.trim();
                option.textContent = newCategoryInput.value.trim();
                categorySelect.appendChild(option);
                newCategoryInput.value = '';
            }
        }
    </script>
</body>

</html>
