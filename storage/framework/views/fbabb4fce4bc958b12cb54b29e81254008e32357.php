<?php $__env->startSection('content'); ?>
    <style>
        .artifact-preview-box {
            width: 95px;
            height: 95px;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 3px;
            background: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: 0.2s ease-in-out;
        }

        .artifact-preview-box:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .artifact-delete-btn {
            position: absolute;
            top: -1px;
                                        right: -1px;
            background: #dc3545;
            border: none;
            border-radius: 50%;
            color: #fff;
            font-size: 12px;
            padding: 2px 6px;
            cursor: pointer;
            z-index: 5;
        }

        .artifact-delete-btn:hover {
            background: #b52a34;
        }

        .container {
            max-width: 100% ;
        }
    </style>

    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Upload Artifacts</h5>
            </div>

            <div class="card-body">

                <form action="<?php echo e(route('artifacts.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <div class="col-md-4 mb-3">
                        <label for="validate_at" class="form-label fw-bold">Validate At Date</label>
                        <input type="date" name="validate_at" id="validate_at" class="form-control form-control-sm"
                            required>
                    </div>

                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th>Artifact Name</th>
                                <th style="width: 45%;">Upload / Uploaded Files</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php $__currentLoopData = $artifacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $artifact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="fw-bold"><?php echo e($index + 1); ?></td>
                                    <td class="fw-bold text-primary"><?php echo e($artifact); ?></td>

                                    <td>
                                        <input type="file" name="artifacts[<?php echo e($artifact); ?>][]"
                                            class="form-control form-control-sm mb-2" multiple
                                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx">

                                        
                                        <?php if(isset($uploadedGrouped[$artifact])): ?>
                                            <div class="mt-2 d-flex flex-wrap gap-3">
                                                <?php $__currentLoopData = $uploadedGrouped[$artifact]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $ext = strtolower(
                                                            pathinfo($file->artifact, PATHINFO_EXTENSION),
                                                        );
                                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                                                        $fileUrl = asset('storage/app/public/' . $file->artifact);
                                                    ?>

                                                    <div class="artifact-preview-box">
                                                        <button type="button"
                                                            class="artifact-delete-btn delete-artifact-btn"
                                                            value="<?php echo e($file->id); ?>">
                                                            <i class="fa fa-trash"></i>
                                                        </button>

                                                        <?php if($isImage): ?>
                                                            <a href="<?php echo e($fileUrl); ?>" target="_blank">
                                                                <img src="<?php echo e($fileUrl); ?>"
                                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="<?php echo e($fileUrl); ?>" target="_blank"
                                                                class="d-flex flex-column align-items-center justify-content-center text-center"
                                                                style="height: 100%; font-size: 12px; padding: 5px;">
                                                                <i class="fa fa-file fs-3 text-secondary"></i>
                                                                <?php echo e(strtoupper($ext)); ?>

                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                    </table>

                    <input type="hidden" name="audit_id" value="<?php echo e($audit_id); ?>">
                    <input type="hidden" name="type" value="<?php echo e($originalType); ?>">

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-upload me-1"></i> Submit Artifacts
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // Select all delete buttons
        document.querySelectorAll('.delete-artifact-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                if (!confirm("Delete this artifact?")) return;

                // Get artifact ID from button value
                let fileId = this.value;

                // Use Laravel route name and replace placeholder with actual ID
                let url = `<?php echo e(route('artifacts.delete', ['id' => ':id'])); ?>`.replace(':id',
                    fileId);

                fetch(url, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector(
                                'meta[name="csrf-token"]').getAttribute('content'),
                            "Accept": "application/json",
                            "Content-Type": "application/json"
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the preview box of this artifact
                            this.closest('.artifact-preview-box').remove();
                        } else {
                            alert(data.message || 'Failed to delete the artifact.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('An error occurred while deleting the artifact.');
                    });
            });
        });

    });
</script>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\idfc_qdegrees\resources\views/artifacts_additional/create.blade.php ENDPATH**/ ?>