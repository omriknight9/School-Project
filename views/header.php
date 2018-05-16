<?php $_SESSION['email'] = $_POST['login_email']; ?>

<!doctype html>

<html>
    <head>
        <title>Omri</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../css/project.css">

    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <img src="http://www.freepngimg.com/download/book/6-books-png-image-with-transparency-background.png" width="30" height="30" class="d-inline-block books align-top" alt="">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#">School</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Administrators</a>
                    </li>
                </ul>
                 <div>
                    <span class="name_span">John Doe, Manager <?php echo 'asd' . $_SESSION['email']; ?></span>
                    <button class="btn btn-info" type="submit" id="logout">Logout</button>
                    <img src="https://vignette.wikia.nocookie.net/nation/images/6/61/Emblem_person_blue.png/revision/latest?cb=20120218131529" alt="User Image" width="100px">
                </div>
            </div>
        </nav>
        
        
        <script src="../js/header.js"></script>
    </body>
</html>
