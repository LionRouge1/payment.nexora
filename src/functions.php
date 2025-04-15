<?php

function receipt_mail_template($website, $payment, $author) {
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Payment Receipt</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 20px;
            }
            .container {
                max-width: 600px;
                margin: auto;
                background: #fff;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }
            h1 {
                color: #333;
            }
            p {
                line-height: 1.6;
            }
            .footer {
                text-align: center;
                margin-top: 20px;
                font-size: 12px;
                color: #777;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Payment Receipt</h1>
            <p>Hi <?= htmlspecialchars($author->fullname) ?>,</p>
            <p>Thank you for renewing your Nexora WebStarter Plan.</p>
            <p>We’ve received your payment of <?= htmlspecialchars($payment->amount) ?> for the domain <?= htmlspecialchars($website->domain) ?>, and your subscription has been successfully extended for another month.</p>
            <h2>🧾 Receipt Details</h2>
            <p><strong>Plan:</strong> WebStarter</p>
            <p><strong>Domain:</strong> <?= htmlspecialchars($website->domain) ?></p>
            <p><strong>Amount Paid:</strong> <?= htmlspecialchars($payment->amount) ?></p>
            <p><strong>Payment Date:</strong> <?= htmlspecialchars($payment->date) ?></p>
            <p><strong>Next Renewal:</strong> <?= htmlspecialchars($payment->next_renewal_date) ?></p>
            <p>Your website, hosting, domain, and maintenance services remain active and in good standing.</p>
            <p>If you have any questions or need assistance, feel free to reach out at <a href="mailto:support@nexoragh.com">support@nexoragh.com</a> or WhatsApp us at 0559326875.</p>
            <p>Thanks for choosing Nexora!</p>
            <p>Best regards,</p>
            <p>The Nexora Team</p>
            <p>🌐 <a href="https://nexoragh.com">nexoragh.com</a></p>
        </div>
    </body>
    </html>
    <?php
    return ob_get_clean();
}