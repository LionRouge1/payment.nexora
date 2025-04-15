<h1>payment Details</h1>

<div class="user-card">
    <h2><?= htmlspecialchars($payment->getName()) ?></h2>
    <p><strong>Email:</strong> <?= htmlspecialchars($payment->getDomain()) ?></p>
    <p><strong>Member since:</strong> <?= $payment->getCreatedAt()->format('M j, Y') ?></p>
    
    <div class="actions">
        <a href="/payments/<?= $payment->getId() ?>/edit" class="btn">Edit</a>
        <a href="/payments" class="btn">Back to List</a>
    </div>
</div>