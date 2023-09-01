<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Add meta tags for mobile and IE -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title> PayPal Checkout Integration</title>
</head>

<body>
    <!-- Set up a container element for the button -->
    <div id="paypal-button-container"></div>
   

    <!-- Include the PayPal JavaScript SDK -->
    <script
        src="https://www.paypal.com/sdk/js?client-id=ASP6yjHF-9l3J4odM3sVAw288Ewu0ojqlRTWZfagv-6g0WHxx2pT_zZYC48o_6xykE8UcL5GHawSocI2&currency=USD">
    </script>

    <script>
        // Render the PayPal button into #paypal-button-container
        paypal.Buttons({


            // Call your server to set up the transaction
            createOrder: function(data, actions) {
                return fetch('api/paypal/order/create/', {
                    method: 'post',
                    body: JSON.stringify({
                        "value": '20',
                    })
                }).then(function(res) {
                    return res.json();
                }).then(function(orderData) {
                    return orderData.id;
                });
            },

            // Call your server to finalize the transaction
            onApprove: function(data, actions) {
              
                return fetch('/api/paypal/order/capture/', {
                    method: 'post',
                    body: JSON.stringify({
                        orderID: data.orderID,
                       
                    })
                }).then(function(res) {
                    return res.json();
                }).then(function(orderData) {
                    if (orderData.status === 'success') {
                        alert('Payment captured successfully!');

                    } else {
                        alert('Payment capture failed.');
                    }
                });
            }

        }).render('#paypal-button-container');
    </script>
</body>

</html>
