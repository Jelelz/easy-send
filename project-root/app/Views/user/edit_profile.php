<?= $this->extend('layouts/main.php') ?>

<?= $this->section('main_content') ?>
    <body>       
        <div class="container w-75 p-1">
            <div class = "row no-pad">
                
                <div class= "col-lg-3 ">
                    <img src="/images/dely.jpg" class="img-fluid" alt="">    
                </div>
                
                <div class="col-lg-7 px-1 pt-1 offset-1">
                    <h3 class="col-lg-11 text-center font-weight-bold py-0 ">Edit Your Profile</h3>

            <form method = 'POST' action = '/customer/editProfile'>
                <div class="form-row">
                <div class="offset-2 col-lg-7">
                <input type =  "text" placeholder="First Name" required class="form-control my-2 p-2" name="edit_firstname" value = <?=$user_data['user_firstname']?>>
                </div>
                </div>

                <div class="form-row">
                <div class="offset-2 col-lg-7">
                <input type =  "text" placeholder="Last Name" required class="form-control my-2 p-2" name="edit_lastname" value = <?=$user_data['user_lastname']?>>           </div>
                </div>

                <div class="form-row">
                <div class="offset-2 col-lg-7">             
                <input type =  "email" placeholder="Email Address" required class="form-control my-2 p-2" name="edit_email" value = <?=$user_data['user_email']?>>
                </div>
                </div>

                <!--

                <div class="form-row">
                <div class="offset-2 col-lg-7">
                <input type =  "password" placeholder="Password" required class="form-control my-3 p-2" name="register_password">
                </div>
                </div>

                -->

                <div class="form-row">
                <div class="offset-2 col-lg-7">
                <input type =  "number" placeholder="Phone Number" required class="form-control my-2 p-2" name="edit_number" value = <?=$user_data['user_number']?>>
                </div>

                <div class="form-row">
                <div class="offset-2 col-lg-7">
                <input type =  "text" placeholder="Your Address" required class="form-control my-2 p-2" name="edit_location" value = <?=$user_data['user_location']?>>
                </div>
                </div>

                <div class="form-row">
                <div class="offset-2 col-lg-7">
                <button type = "submit" name = "edit_submit" class="btn1 mt-2 mb-1" value = "SUBMIT">SUBMIT</button>
                </div>
                </div>

            </form>

                </div>

            </div>
        </div>
    </body>
        
        

<?= $this->endSection() ?>
