<?php

$data = $_POST;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        #payment-form {
            background-color: #eaebf0;
            padding: 27px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            width: 500px;
        }

        button#payment-button {
            border: 1px solid #e1d7d7;
            border-radius: 6px;
            margin-top: 18px;
            font-weight: 700;
            background: #1b1b4e;
            color: white;
            padding: 10px 0px;
            width: 130px;
            line-height: 1.2;
            margin: 0 auto
        }

        .payment-box {
            height: 600px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        h2 {
            font-size: 21px;
            text-align: center;
            margin-bottom: 35px;
            font-weight: 500;
            text-decoration: underline;

        }
    </style>
</head>

<body>
    <div class="container">
        <div class="payment-box">
            <form id="payment-form">
                <!-- Your payment form fields, including card details -->
                <h2>Card Details</h2>
                <div id="card-element">
                    <!-- A Stripe Element will be inserted here. -->
                </div>
                <button id="payment-button" type="submit">Pay Now</button>
            </form>
        </div>
    </div>
    <script src="cred.js"> </script>
    <script>

        const public_key = getPublicKey();
        const stripe = Stripe(public_key);

        const elements = stripe.elements();

        // Create a card Element
        const cardElement = elements.create('card');

        // Mount the card Element to the DOM
        cardElement.mount('#card-element');

        // Handle form submission
        document.getElementById('payment-form').addEventListener('submit', async function (event) {
            event.preventDefault();

            // Call your backend to create a PaymentIntent and obtain the client secret
            const response = await fetch('stripe.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    items: [{ name: 'Item 1', price: 500 }, { name: 'Item 2', price: 900 }]
                })
            });
            const data = await response.json();

            // Use the obtained client secret to confirm the payment
            const { error } = await stripe.confirmCardPayment(data.clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: "<?= htmlspecialchars($data['name']) ?>",
                        email: "<?= htmlspecialchars($data['email']) ?>",
                        phone: "<?= htmlspecialchars($data['tel']) ?>"

                    }
                }
            });

            if (error) {
                console.error('Payment failed:', error);
            } else {
                console.log('Payment succeeded!');
                // Redirect to a success page or show a success message
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>