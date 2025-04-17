<?php
  use App\Helpers\AssetHelper;
  use App\Helpers\RouteHelper;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($title ?? 'WebStarter Plan Payment') ?></title>
    <meta name="description" content="<?= htmlspecialchars($description ?? 'WebStarter Plan Payment') ?>" />
    <meta name="keywords" content="<?= htmlspecialchars($keywords ?? 'WebStarter Plan Payment') ?>" />
    <meta name="author" content="<?= htmlspecialchars($author ?? 'Nexora') ?>" />
    <meta name="robots" content="index, follow" />
    <meta name="theme-color" content="#59014e" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-title" content="Nexora" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-title" content="Nexora" />
    <meta name="msapplication-TileColor" content="#59014e" />
    <meta name="msapplication-TileImage" content="/assets/images/logo.jpg" />
    <meta name="theme-color" content="#59014e" />
  
    <link rel="icon" href="/assets/images/logo.jpg">
    <!-- Linking Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

    <!-- Linking Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />

    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    <?= AssetHelper::css(['style', 'thanks'], true) ?>
    <script src="https://js.paystack.co/v2/inline.js"></script>
    <script>
      window.config = {
        paystackKey: "<?= $_ENV['PAYSTACK_PUBLIC_KEY'] ?>",
      };
    </script>
</head>
<body>

    <main>
        <nav class="navbar" style="height: auto;">
          <div class="container-fluid d-flex justify-content-between align-items-center position-relative" style="min-height: 50px;">
            
            <!-- Logo (Left) -->
            <a class="navbar-brand d-flex align-items-center mb-0" href="<?= RouteHelper::url('') ?>" style="padding: 0;">
              <?= AssetHelper::img('logo.jpg', ['alt' => 'Logo', 'style' => 'height: 35px; width: auto;']) ?>
            </a>

            <!-- Centered NEXORA text (Always visible and centered) -->
            <div class="position-absolute top-50 start-50 translate-middle text-center">
              <a class="navbar-brand m-0" href="#" style="font-weight: bold; font-size: 1.2rem;">NEXORA</a>
            </div>

            <!-- Home Icon (Right) -->
            <div class="d-flex align-items-center mb-0">
              <a href="https://nexoragh.com/" class="nav-link p-0">
                <span style="display: inline-block; padding: 5px 8px; border-radius: 7px;
                            background: linear-gradient(90deg, #59014e 38%, #b00333 100%);
                            color: white;">
                  <i class="bi bi-house-fill" style="font-size: 1.3rem;"></i>
                </span>
              </a>
            </div>

          </div>
        </nav>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger mt-3"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <?= $content ?>
    </main>
    <?= AssetHelper::js(['app'], true, true) ?>
</body>
</html>