<html>

<head>
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
            padding: 20px 45px;
        }

        .text-center {
            text-align: center;
        }

        .image {
            /* margin-top: 20px; */
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
                    <p>Payment processing in progress. Please, close the page using the <code style="color:red; font-size: 20px; font-weight:600">x</code> at the top left and navigate back to invoice page to reflect the final status.</p>
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
