<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="/CSS/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/CSS/reg.css">
    <link rel="stylesheet" type="text/css" href="/CSS/main.css">
    <link href="/CSS/styles.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $this->renderSection('css') ?>
</head>

<body>
    <header>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container px-5">
                <div id="logo" class="logo" >
                    <img src="/images/logo.png" href="#!">
                </div>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="/user/home">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#!">About</a></li>
                        <li class="nav-item"><a class="nav-link" onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Login</a></li>
                        <!--<li class="nav-item"><a class="nav-link" href="/customer/register">Register</a></li>-->
                        <li class="nav-item"><a class="nav-link" href="/user/logOut">Log Out</a></li>
                        <li class="dropdown nav-item">
                            <div class="imgcontainer">
                            <img src="/images/avatar.png" alt="Avatar" class="avatar">
                            <a class="dropbtn nav-link">
                                <?php
                                if (isset($_SESSION['user_firstname']))
                                    { echo $_SESSION['user_firstname'];}
                                ?><i class="bi bi-caret-down-square-fill"></i>
                            </a>
                            <div class="dropdown-content">
                            <a class="nav-link" href="/user/redirectEditProfile">Edit Profile</a>
                            <a class="nav-link" href="/user/redirectViewOrderHistory">View History</a>
                            <a class="nav-link" href="/customer/trackOrder">Track Order</a>
                            <a class="nav-link" href="/deliveryperson/fulfillOrder">Fulfill Order</a>
                            
                            </div>
                            </div>
                            
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <p></p>
    </header>
    
    <main>
        <?= $this->renderSection('main_content') ?>
    </main>
    
    <footer>
        <p></p>
    </footer>

    <?= $this->renderSection('JS') ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>