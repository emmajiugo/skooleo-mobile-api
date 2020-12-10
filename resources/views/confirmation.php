<html>

<head>
    <!-- CSS only -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <style>
        body {
            letter-spacing: 1;
            line-height: 2;
            font-size: 24px;
        }

        .container {
            position: relative;
            top: 35%;
            transform: translateY(-50%);
        }

        h1 {
            font-size: 38px;
            font-weight: 800;
            color: #5f55aa;
        }

        .failed {
            color: red;
        }

        .payment-issue {
            color: green;
            margin-top: 150px;
        }

        .copy {
            position: fixed;
            left: 50%;
            bottom: 20px;
            transform: translate(-50%, -50%);
            margin: 0 auto;
            color: #5f55aa;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col text-center">
                <img src="/images/confirmation.gif" alt="confirmation">
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
    </div>
    <span class="copy">&copy Skooleo.com</span>
</body>

</html>
