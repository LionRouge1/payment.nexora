// Confirm before delete
document.addEventListener('DOMContentLoaded', function() {
  const deleteForms = document.querySelectorAll('form[action*="/payment/"]');
  
  deleteForms.forEach(form => {
      form.addEventListener('submit', function(e) {
          if (!confirm('Are you sure you want to delete this user?')) {
              e.preventDefault();
          }
      });
  });
});