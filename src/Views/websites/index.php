<!-- Web Banner Section -->
<div class="container-fluid web py-3">
  <div class="row w-100">
    <!-- Left Column -->
    <div class="col-12 col-md-6 d-flex justify-content-center align-items-center text-center mb-3 mb-md-0">
      <h3><b>WebStarter Plan Payment</b></h3>
    </div>

    <!-- Right Column (Flip Box) -->
    <div class="col-12 col-md-6 d-flex justify-content-center align-items-center">
      <div class="flip-container" id="flipBox">
        <div class="flipper">
          <div class="front">
            <p>Easily Renew Your WebStarter Plan</p>
          </div>
          <div class="back">
            <p>Keep your website live and running with a simple, hassle-free payment.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


  
  <section class="py-4" style="margin-top: 0px;">
    <div class="container">
      <div class="row align-items-center">
        
        <!-- Left Column -->
        <div class="col-md-6 text-center text-md-start ">
          <h3 class="text-center"><b>Enter Your Website Address to Proceed</b></h3>
          <!-- Centered Form Container -->
          <div class="d-flex justify-content-center mt-4">
            <form action="" method="post" class="d-flex" role="search" style="padding: 10px; border-radius: 8px;">
              <input
                class="form-control me-2"
                type="search"
                placeholder="(www.yourbusiness.com)"
                aria-label="Search"
                style="width: 300px;"
                name="domain"
              />
              <button style="   background: linear-gradient(90deg, #59014e 38%, #b00333 100%); color: white; border-radius: 25px; padding: 12px; border: 10px;" type="submit">Search</button>
            </form>
            </div>
            <?php if ($data): ?>
              <?php if ($data['error']): ?>
                <div class="alert alert-danger mt-3"><?= htmlspecialchars($data['error']) ?></div>
              <?php else: ?>
                <div style="margin-top: 30px; text-align: center;">
                  <p>Domain Name: <b><?= htmlspecialchars($data['domain']) ?></b></p>
                  <p>Owned By: <b><?= htmlspecialchars($data['fullname']) ?></b></p>
                  <p>💳 Amount: <b>Ghc220 (Approx. $12.99)</b> per month</p>
                  <input type="hidden" name="website_id" id="website_id" value="<?= htmlspecialchars($data['website_id']) ?>">
                  <input type="hidden" name="email" id="email" value="<?= htmlspecialchars($data['email']) ?>">
                  <button
                  id="payment-button"
                  style="background: linear-gradient(90deg, #59014e 38%, #b00333 100%); color: white; border-radius: 25px; padding: 12px; border: 10px; margin-bottom: 20px;" 
                  type="submit">
                  Pay Now
                  </button>
                </div>
              <?php endif; ?>
            <?php endif; ?>
          <!-- <div style="margin-top: 30px; text-align: center;">
            <p>Domain Name: <b> www.yourbusiness.com </b></p>
            <p>Owned By: <b> your name </b></p>
            <p>💳 Amount: <b> Ghc220 (Approx.$12.99) </b> per month</p>
            <button style="   background: linear-gradient(90deg, #59014e 38%, #b00333 100%); color: white; border-radius: 25px; padding: 12px; border: 10px;margin-bottom: 20px;" type="submit">Pay Now</button>
          </div> -->
        </div>
  
        <div class="col-12 col-md-6 text-center" style="max-height: 500px;">
          <div class="container">
            <div class="row g-0">
              <div class="col-6 col-sm-6 p-0">
                <img src="./assets/images/mtn.jpg" alt="Image 1" class="img-fluid responsive-img" style="border-top-left-radius: 10px; height: 200px;">
              </div>
              <div class="col-6 col-sm-6 p-0">
                <img src="./assets/images/airtiltigo.jpg" alt="Image 3" class="img-fluid responsive-img" style="border-top-right-radius: 10px; height: 200px;">
              </div>
            </div>
            <div class="row g-0">
              <div class="col-6 col-sm-6 p-0">
                <img src="./assets/images/telecel.jpg" alt="Image 2" class="img-fluid responsive-img" style="height: 200px; border-bottom-left-radius: 10px;">
              </div>
              <div class="col-6 col-sm-6 p-0">
                <img src="./assets/images/visa.jpg" alt="Image 4" class="img-fluid responsive-img" style="border-bottom-right-radius: 10px; height: 200px;">
              </div>
            </div>
          </div>
        </div>
        
        
    </div>
  </section>

  
  <div class="container text-center" style="margin-top: 70px;" >
    <div class="row p-4 text-white" style="background: linear-gradient(135deg, rgba(89,1,78,1) 18%, rgba(176,3,51,1) 51%); border-radius: 20px; ">
      <h2 style="margin-top: 30px;"><b>Important Reminder</b></h2>
      <p style="margin-top: 20px;margin-bottom: 20px;">
        <b>⚠ Non-renewal may result in your website going offline.</b>
        To avoid disruptions, make sure to renew on time.
        A reactivation fee of <b>$25</b> applies if your website is suspended due to late payment.
      </p>
    </div>
  </div>


  <div class="container-fluid" style="margin-top: 70px;">
    <h3 class="text-center" style="padding-top: 50px;padding-bottom: 30px;"><b>Secure & Instant Confirmation</b></h3>
    <div class="row justify-content-center g-4">
      
      <!-- Card 1 -->
      <div class="col-md-4">
        <div class="card text-center p-3 shadow-sm" style="border-radius: 20px;background-color: #1AACE8;">
          <div class="card-body">
            <i class="bi bi-file-earmark-text fs-1 mb-3" ></i>
            <p class="card-text" >✔ No manual invoicing – <br> just pay and go!</p>
          </div>
        </div>
      </div>
  
      <!-- Card 2 -->
      <div class="col-md-4">
        <div class="card text-center p-3 shadow-sm" style="border-radius: 20px;background-color: #59014e">
          <div class="card-body">
            <i class="bi bi-shield-lock fs-1  mb-3" style="color: white;"></i>
            <p class="card-text" style="color: white;">✔ Secure payment processing</p>
          </div>
        </div>
      </div>
  
      <!-- Card 3 -->
      <div class="col-md-4">
        <div class="card text-center p-3 shadow-sm" style="border-radius: 20px; background-color: #FFCD29; ">
          <div class="card-body">
            <i class="bi bi-envelope-check fs-1  mb-3" style="color: rgb(0, 0, 0);"></i>
            <p class="card-text" style="color: rgb(0, 0, 0);">✔ Instant email confirmation after successful payment</p>
          </div>
        </div>
      </div>
  
    </div>
  
    <!-- Help Section -->
    <div class="text-center" style="background-color: black;color: white;padding-top: 30px;padding-bottom: 30px;margin-top: 120px;">
      <p>🔹 Need Help? Contact our support team at 
        <a href="mailto:support@nexoragh.com">support@nexoragh.com</a>
      </p>
    </div>
  </div>