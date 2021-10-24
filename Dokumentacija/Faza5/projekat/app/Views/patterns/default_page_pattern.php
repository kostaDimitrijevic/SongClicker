<!--
    Mladen Mirčić 2018/0413
    Kosta Dimitrijević 2018/0467
    Teodora Mijatović 2018/0314
-->


<html>
    <head>
        <title>SongClicker</title>
        <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
        <link rel="stylesheet" href="<?= base_url('css/buttonToggles.css') ?>">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src= "https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.1/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    </head>

    <body>
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-10 offset-sm-1 col-lg-6 offset-lg-3 header">
                <div class="header-content">
                    <img src="<?= base_url('images/SongClickerLogo.png') ?>" class="logo">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-10 offset-sm-1 col-lg-6 offset-lg-3 center">
                <?php echo $middlePart ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 offset-lg-3  col-sm-3 offset-sm-1 col-4 footer teamLogo">
                <img  style="width:100%"  src="<?= base_url('images/MangoGamesLogo.png') ?>">
            </div>
            <div class="col-lg-2 col-sm-4 col-4 footer optional">
                <?php if(isset($footerPart)) echo $footerPart ?>
            </div>
            <div class="col-lg-2 col-sm-3 col-4 footer userWelcome">
                <?php
                if (isset($welcomeMessage))
                    echo $welcomeMessage
                ?>
            </div>
        </div>
    </div>
    </body>


</html>