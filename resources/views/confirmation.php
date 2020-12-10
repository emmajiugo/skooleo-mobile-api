<html>

<head>
    <!-- CSS only -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800,900" rel="stylesheet">
    <!-- <link href="/css/bootstrap.min.css" rel="stylesheet"> -->

    <style>
        html {
            font-family: sans-serif;
            line-height: 1.15;
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        }

        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-size: 0.8rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: left;
            background-color: #fff;
        }

        .container {
            position: relative;
            top: 35%;
            transform: translateY(-50%);
        }

        h1 {
            font-size: 20px;
            font-weight: 800;
            color: #5f55aa;
        }

        .row {
            width: 100%;
        }

        .col {
            padding: 45px;
        }

        .text-center {
            text-align: center;
        }

        .image {
            margin-top: 20px;
            width: 100%;
        }

        .failed {
            color: red;
        }

        .payment-issue {
            color: green;
            /* margin-top: 70px; */
        }

        .copy {
            position: fixed;
            left: 50%;
            bottom: 0;
            transform: translate(-50%, -50%);
            margin: 0 auto;
            color: #5f55aa;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="row">
            <div class="col text-center">
                <img src="/images/confirmation.gif" class="image" alt="confirmation">
            </div>
        </div>
        <div class="row">
            <?php
            if ($cancelled) {
            ?>

                <div class="col text-center">
                    <h1 class="failed">Payment Cancelled</h1>
                    <p>We hate to see you go, but reach out to us if you have a way we can improve our services to accomodate you.</p>
                </div>

            <?php
            } else {
            ?>

                <div class="col text-center">
                    <h1>Payment Confirmation</h1>
                    <p>Payment processing in progress. Please, navigate back to invoice page and refresh your app after few seconds to reflect the final status.</p>
                </div>

            <?php
            }
            ?>
        </div>
        <div class="row">
            <div class="col text-center payment-issue">
                <p>
                    For any payment related issues, <br>
                    send an email to <b><?= $email ?></b> <br>
                    or call <b><?= $phone ?></b>
                </p>
            </div>
        </div>


        <div class="row">
            <div class="col copy text-center">&copy Skooleo.com</div>
        </div>
    </div>
</body>

</html>
