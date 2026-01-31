<table class="table table-bordered">
    <thead>
        <tr>
            <th>First Name</th>
            <th>Second Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($allUsers as $user): ?>
            <tr>
                <td><?= h($user->first_name) ?></td>
                <td><?= h($user->second_name) ?></td>
                <td><?= h($user->user_email) ?></td>
                <!-- Use is_admin column directly -->
                <td><?= $user->is_admin ? 'Admin' : 'User' ?></td>
                <td>
                    <?php if ($user->is_admin): ?>
                        <!-- Admins cannot be banned -->
                        <span class="badge bg-success">Admin</span>
                    <?php else: ?>
                        <?php if ($user->is_banned): ?>
                            <span class="badge bg-secondary">Banned</span>
                        <?php else: ?>
                            <a href="<?= $this->Url->build([
                                'controller' => 'Users',
                                'action' => 'banUser',
                                $user->id
                            ]) ?>" class="btn btn-sm btn-danger">
                                Ban
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
