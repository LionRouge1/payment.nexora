<?php
use App\Helpers\AssetHelper;
?>
<section class="thanks-container">
    <main class="container mt-4">
      <div class="row justify-content-center mb-3">
        <!-- SVG (scaled down to fit as navbar logo) -->
        <svg class="movable-svg" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" style="max-width: 520px;">
          <defs>
            <linearGradient id="myGradient" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%"   style="stop-color:#59014e;stop-opacity:1" />
              <stop offset="100%" style="stop-color:#b00333;stop-opacity:1" />
            </linearGradient>
          </defs>
          <path fill="url(#myGradient)" d="M61.5,-36.9C69.3,-22,58,2.5,44.6,23.8C31.1,45.1,15.6,63.3,-4.1,65.7C-23.8,68.1,-47.6,54.7,-59,34.5C-70.4,14.4,-69.3,-12.5,-57.4,-29.8C-45.5,-47.1,-22.7,-54.8,2.1,-56C26.9,-57.2,53.7,-51.9,61.5,-36.9Z" transform="translate(100 100)" />
        </svg>
      </div>

     
        <div class="row align-items-start text-center text-md-start" style="margin-top: 100px;text-align: center;">
          <div class="col-12 col-md-6 mb-4">
            <div>
              <h1 style="margin-top: 30px;"><b>Payment Successful</b></h1>
          
              <p style="font-size: 0.95rem;">We have receieved payment</p>
              <p>Thank you for choosing Nexora</p>
              <i class="bi bi-check-circle" style="font-size: 100px;color: green;"></i>
              <svg class="mine" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" style="position: absolute; top: 150px;left:-250px; width: 500px; height: 750px; z-index: -1;">
                <defs>
                  <linearGradient id="myGradientLeft" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%"   style="stop-color:#ffcd29;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#b00333;stop-opacity:1" />
                  </linearGradient>
                </defs>
                <path fill="url(#myGradientLeft)" d="M34.4,-31.2C43.4,-16.3,48.5,-2,44.9,9C41.4,20,29.3,27.8,15.2,36.6C1,45.4,-15.1,55.1,-27.7,51.4C-40.4,47.7,-49.5,30.5,-52.9,12.9C-56.3,-4.8,-54,-23,-44.2,-38.1C-34.5,-53.3,-17.2,-65.3,-2.2,-63.5C12.7,-61.7,25.5,-46.1,34.4,-31.2Z" transform="translate(100 100)" />
              </svg>
            </div>
          </div>

          <div class="col-12 col-md-6 ">
            <div style="position: relative;">
              <svg class="back-svg" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" style="position: absolute; top: -180px; right:-200; width: 900px; height: 750px; z-index: -1;">
                <defs>
                  <linearGradient id="myGradient2" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%"   style="stop-color:#59014e;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#b00333;stop-opacity:1" />
                  </linearGradient>
                </defs>
                <path fill="url(#myGradient2)" d="M44.5,-65.2C51.9,-55.9,48.2,-35.2,49.6,-18.9C51,-2.7,57.7,9.1,57.9,22.1C58.2,35.1,52.1,49.2,41.4,62.3C30.7,75.4,15.4,87.4,1.4,85.4C-12.5,83.5,-25,67.6,-40.9,56.2C-56.7,44.7,-75.8,37.8,-83.4,24.7C-90.9,11.6,-86.9,-7.7,-76.1,-19.8C-65.3,-31.9,-47.9,-36.9,-34.1,-44.1C-20.3,-51.4,-10.1,-60.9,4.2,-66.7C18.6,-72.5,37.1,-74.6,44.5,-65.2Z" transform="translate(100 100)" />
              </svg>
              <?= AssetHelper::img('8652580-removebg-preview.png', ['alt' => 'Thank You Image', 'class' => 'img-fluid', 'style' => 'max-height: 550px; border-radius: 10px;']) ?>
            </div>
          </div>
        </div>
      </main>
</section>