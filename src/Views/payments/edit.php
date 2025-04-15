<h1>Edit User</h1>

<form method="POST" action="/users/<?= $payment->getId() ?>">
    <input type="hidden" name="_method" value="PUT">
    
    <div>
        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user->getName()) ?>" required>
    </div>
    <div>
        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" required>
    </div>
    <button type="submit">Update User</button>
</form>

<form method="POST" action="/users/<?= $payment->getId() ?>" onsubmit="return confirm('Are you sure?')">
    <input type="hidden" name="_method" value="DELETE">
    <button type="submit" class="danger">Delete User</button>
</form>