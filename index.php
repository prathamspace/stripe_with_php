<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>

</head>

<body>
    <form id="payment-form">
        <!-- Your payment form fields, including card details -->

        <div id="card-element">
            <!-- A Stripe Element will be inserted here. -->
        </div>
        <button id="payment-button" type="submit">Pay Now</button>
    </form>
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
                        name: 'Customer Name'
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
</body>

</html>