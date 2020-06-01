<div>
    <h4>hi <?php echo e($user->name); ?></h4>
    <p>Thanks for creating your account at Audit. your account details are as follows:</p>
    <p><strong>Email Address:</strong>&nbsp; <?php echo e($user->email); ?></p>
    <p><strong>Password:</strong>&nbsp; <?php echo e($password); ?></p>
<a href="<?php echo e($url); ?>">Click here for login</a>
</div><?php /**PATH /var/www/html/audit/resources/views/emails/createUser.blade.php ENDPATH**/ ?>