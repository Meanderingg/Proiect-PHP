<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File handler</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <form action="files-handler.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="fileUpload" class="form-label">Upload File</label>
            <input type="file" class="form-control" id="fileUpload" name="fileUpload" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</body>
</html>

<?php
if (isset($_FILES['fileUpload'])) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    var_dump($_FILES['fileUpload']);
    $uploadFile = $uploadDir . basename($_FILES['fileUpload']['name']);

    if (move_uploaded_file($_FILES['fileUpload']['tmp_name'], $uploadFile)) {
        echo " File is valid, and was successfully uploaded.\n";
        echo "File path: " . htmlspecialchars($uploadFile);
    } else {
        echo " Possible file upload attack!\n";
    }
} else {
    echo " No file uploaded or invalid request method.";
}
?>
