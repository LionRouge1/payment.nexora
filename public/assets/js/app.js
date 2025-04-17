document.addEventListener('DOMContentLoaded', () => {
  const paymentBtn = document.getElementById('payment-button');
  const flipBox = document.getElementById('flipBox');

  if (flipBox) {
    setInterval(() => {
      flipBox.classList.toggle('flipped')
    }, 5000);
  }

  if (paymentBtn) {
    paymentBtn.addEventListener('click', async () => {
      const response = await fetch('/initialize', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        }
      });

      const data = await response.json();
      const popup = new PaystackPop();
      popup.newTransaction({
        email: data.email,
        amount: data.amount,
        metadata: { website_id: data.website_id },
        key: window.config.paystackKey,
        onSuccess: async transaction => {
          const request = await fetch('/verify', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
              reference: transaction.reference,
              _method: 'POST'
            }).toString()
          })

          const response = await request.json()
          if (response.status === 'success') {
            // Handle successful payment
            window.location.href = '/payment/thanks'
          } else {
            console.log('Payment failed', response)
          }
        },
        onLoad: response => {
          console.log('Loading...')
        },
        onCancel: async () => {
          // User closed the popup without completing the transaction
          const request = await fetch('/verify', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
              error: 'Transaction not completed. Kindly try again.',
              _method: 'POST'
            }).toString()
          })

          if (request.status === 500) {
            // Handle successful error

            window.location.reload(true)
          }
        },
        onError: async error => {
          const request = await fetch('/verify', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
              error: error.message,
              _method: 'POST'
            }).toString()
          })
          if (request.status === 500) {
            // Handle successful error
            window.location.reload(true)
          }
        }
      });
    });
  }
})
