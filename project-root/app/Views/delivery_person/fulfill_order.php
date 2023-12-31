<?= $this->extend('layouts/main.php') ?>

<?= $this->section('css') ?>
  <link rel = "stylesheet" href = "/CSS/track_order.css">
<?= $this->endSection() ?>

<?= $this->section('main_content') ?>
<body>
    <div class="container w-50 p-1">
        <div class = "row no-pad">
        <h3 class="heading justify-content-center text-center">Fulfill your delivery</h3>
        
        <div class="accept justify-content-center text-center">
        <h4>Make your way to the customer's pick up location, receive the package, then deliver it to their destination. Here are the order details:</h4>

        <h5 class="heading">Pick Up</h5>
        <p>Area: <?= $order['pickup_area']?></p>
        <p>Street Name: <?= $order['pickup_street_name']?></p>
        <p>Estate / Apartment Complex: <?= $order['pickup_estate']?></p>
        <p>House Number: <?= $order['pickup_house_no']?></p>
        <p>Additional Comment: <?= $order['pickup_comment']?></p>

        <h5 class="heading">Destination Location </h5>
        <p>Area: <?= $order['destination_area']?></p>
        <p>Street Name: <?= $order['destination_street_name']?></p>
        <p>Estate / Apartment Complex: <?= $order['destination_estate']?></p>
        <p>House Number: <?= $order['destination_house_no']?></p>
        <p>Additional Comment: <?= $order['destination_comment']?></p>
        <p>Receiver Phone Number: <?= $order['destination_phone_no']?></p>

        <form method = 'POST' action = "/Deliveryperson/fulfillOrder" enctype="multipart/form-data">
            <h2>Upload confirmation photo</h2>
            <label>Confirmation photo : </label>
            <input type = "file" name = "confirmation_photo">

            <button type = "submit" name = "confirmation_submit" class="btn1 mt-2 mb-3" value = "SUBMIT">SUBMIT</button>
        </form>
        </div>
        </div>

        
    </div>
</body>

<?= $this->endSection() ?>

