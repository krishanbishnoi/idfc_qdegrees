<!DOCTYPE html>
<html>

<head>
    <title>Allocation Dump Upload</title>
</head>

<body>

    <h2>Upload Allocation Dump Excel</h2>

    <?php if(session('success')): ?>
        <p style="color:green"><?php echo e(session('success')); ?></p>
    <?php endif; ?>

    <form action="<?php echo e(route('allocation.upload.file')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <label>Select Excel File:</label>
        <input type="file" name="file" accept=".csv,.xlsx,.xls" required>

        <?php if ($errors->has('file')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('file'); ?>
            <p style="color:red"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>

        <br><br>
        <button type="submit">Upload</button>
    </form>

</body>

</html>
<?php /**PATH C:\wamp64\www\idfc_qdegrees\resources\views/allocation_dump/allocation-upload.blade.php ENDPATH**/ ?>