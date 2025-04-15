// Confirm before delete
document.addEventListener('DOMContentLoaded', () => {
  const paymentBtn = document.getElementById('paymentBtn')
  const email = document.getElementById('email').value
  const websiteId = document.getElementById('website_id').value
  console.log('payment btn', paymentBtn)
  if (paymentBtn) {
    // const popup = new Paystack();

    paymentBtn.addEventListener('click', () => {
      console.log('click')
      const popup = new Paystack()
      popup.newTransaction({
        email: email,
        amount: 220000,
        key: 'pk_test_d9b121d9c7e8280494f210cda9558515b08827be',
        onSuccess: transaction => {
          console.log(transaction)
        },
        onLoad: response => {
          console.log('Loading...')
        },
        onCancel: function () {
          // User closed the popup without completing the transaction
          alert('Transaction was not completed.')
        },
        onError: error => {
          // Handle error
          alert('An error occurred. Please try again.')
        }
      })
    })
  }
})
