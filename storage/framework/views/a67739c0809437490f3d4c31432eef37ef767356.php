<?php $__env->startSection('content'); ?>
    <style>
        .required::after {
            content: '*';
            color: red;
            margin-left: 2px;

        }

        .container {
            max-width: 100%;
        }
    </style>
    <div class="container mt-5">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Download Artifacts</h5>
            </div>

            <div class="card-body">
                <form action="<?php echo e(route('downloadArtifact')); ?>" method="post"><?php echo csrf_field(); ?>
                    <div class="row mb-3">
                        <!-- First Select -->
                        <div class="col-md-3">
                            <label for="type_select" class="form-label d-block fw-bold required">Select Type</label>
                            <select id="type_select" class="form-select w-100 p-2 " required name="type">
                                <option value="">-- Select Type --</option>
                                <option value="agency">Agency</option>
                                <option value="branch">Branch</option>
                                <option value="yard">Yard</option>
                                <option value="agency_repo">Agency Repo</option>
                                <option value="branch_repo">Branch Repo</option>
                                <option value="yard_repo">Yard Repo</option>
                            </select>
                        </div>

                        <!-- Second Select -->
                        <div class="col-md-3">
                            <label for="item_select" class="form-label d-block  fw-bold required">Select Item</label>
                            <select id="item_select" class="form-select w-100 p-2" required name="item_id">
                                <option value="">-- Select Item --</option>
                            </select>
                        </div>

                        <!-- Third Select -->
                        <div class="col-md-3">
                            <label for="artifact_select" class="form-label d-block fw-bold">Select Artifact</label>
                            <select id="artifact_select" class="form-select w-100 p-2" name="artifact_name">
                                <option value="">-- Select Artifact --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="cycle_select" class="form-label d-block fw-bold">Select Cycle</label>
                            <select id="cycle_select" class="form-select w-100 p-2" name="cycle_name">
                                <option value="">-- Select Cycle --</option>

                                <?php $__currentLoopData = $audit_cycle; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cycle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cycle->name); ?>">
                                        <?php echo e($cycle->name ?? ($cycle->cycle_name ?? 'Cycle ' . $cycle->id)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-upload me-1"></i> Download Artifacts
                    </button>
                </form>
            </div>

            
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var selectedType = '';

            // Load items when type changes
            $('#type_select').on('change', function() {
                selectedType = $(this).val();
                $('#item_select').html('<option value="">Loading...</option>');
                $('#artifact_select').html('<option value="">-- Select Artifact --</option>');

                if (selectedType) {
                    $.get('<?php echo e(route('getItems')); ?>', {
                        type: selectedType
                    }, function(data) {
                        var options = '<option value="">-- Select Item --</option>';
                        console.log(data)
                        $.each(data, function(_, value) {
                            options += '<option value="' + value.id + '">' + value.name +
                                '</option>';
                        });
                        $('#item_select').html(options);
                    });
                } else {
                    $('#item_select').html('<option value="">-- Select Item --</option>');
                }
            });

            // Load artifacts when item changes
            $('#item_select').on('change', function() {
                var item_id = $(this).val();
                $('#artifact_select').html('<option value="">Loading...</option>');

                if (item_id) {
                    $.get('<?php echo e(route('getArtifacts')); ?>', {
                        item_id: item_id,
                        type: selectedType
                    }, function(data) {

                        var options = '<option value="">-- Select Artifact --</option>';
                        $.each(data, function(key, value) {
                            options += '<option value="' + value.id + '">' + value
                                .artifact_name + '</option>';
                        });
                        $('#artifact_select').html(options);
                    });
                } else {
                    $('#artifact_select').html('<option value="">-- Select Artifact --</option>');
                }
            });

            // Download button
            // $('#download_btn').on('click', function() {
            //     var type = $('#type_select').val();
            //     var item = $('#item_select').val();
            //     var artifact = $('#artifact_select').val();

            //     if (!type || !item || !artifact) {
            //         alert('Please select Type, Item, and Artifact before downloading.');
            //         return;
            //     }

            //     $.ajax({
            //         url: '<?php echo e(route('downloadArtifact')); ?>',
            //         type: 'POST',
            //         data: { type: type, item_id: item, artifact_id: artifact },
            //         xhrFields: { responseType: 'blob' },
            //         success: function(blob, status, xhr) {
            //             var filename = "artifact_download";
            //             var disposition = xhr.getResponseHeader('Content-Disposition');
            //             if (disposition && disposition.indexOf('attachment') !== -1) {
            //                 var matches = /filename="(.+)"/.exec(disposition);
            //                 if (matches != null && matches[1]) filename = matches[1];
            //             }

            //             var link = document.createElement('a');
            //             var url = window.URL.createObjectURL(blob);
            //             link.href = url;
            //             link.download = filename;
            //             document.body.appendChild(link);
            //             link.click();
            //             setTimeout(function() {
            //                 document.body.removeChild(link);
            //                 window.URL.revokeObjectURL(url);  
            //             }, 100);
            //         },
            //         error: function() {
            //             alert('Download failed. Please try again.');
            //         }
            //     });
            // });
            $('#download_btn').on('click', function() {
                var type = $('#type_select').val();
                var item = $('#item_select').val();
                var artifactId = $('#artifact_select').val(); // gets the ID
                var artifactName = $('#artifact_select option:selected').text(); // gets the name
                console.log(artifactName);

                if (!type || !item || !artifactId) {
                    alert('Please select Type, Item, and Artifact before downloading.');
                    return;
                }

                $.ajax({
                    url: '<?php echo e(route('downloadArtifact')); ?>',
                    type: 'POST',
                    data: {
                        type: type,
                        item_id: item,
                        artifact_id: artifactId,
                        artifact_name: artifactName
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(blob, status, xhr) {
                        var filename = artifactName || "artifact_download";
                        var link = document.createElement('a');
                        var url = window.URL.createObjectURL(blob);
                        link.href = url;
                        link.download = filename;
                        document.body.appendChild(link);
                        link.click();
                        setTimeout(function() {
                            document.body.removeChild(link);
                            window.URL.revokeObjectURL(url);
                        }, 100);
                    },
                    error: function() {
                        alert('Download failed. Please try again.');
                    }
                });
            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\idfc_qdegrees\resources\views/artifacts_additional/download.blade.php ENDPATH**/ ?>