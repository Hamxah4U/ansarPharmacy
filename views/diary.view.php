
<?php	
		require 'partials/security.php';
    require 'partials/header.php';
		require 'model/Database.php';
?>

    <!-- Page Wrapper -->
<div id="wrapper">
    <!-- Sidebar -->
    <?php require 'partials/sidebar.php' ?>

    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <?php
                require 'partials/nav.php';
            ?>
            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800"></h1>
										<button type="button" data-target="#modelUnit" data-toggle="modal" class="btn btn-primary"><strong>Add Diary</strong></button>
                </div>
                <!-- Content Row -->
								 
                <div class="table-responsive">
                    <table class="table table-bordered" id="diaryTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <th>Notes</th>
                                <th>Date write</th>
                                <th>Time write</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $db = new Database();
                                $stmt = $db->conn->prepare("SELECT * FROM diary_tbl WHERE user_id = :user_id ORDER BY  `datercorded`, `timerecorded` DESC");
                                $stmt->bindParam(':user_id', $_SESSION['userID'], PDO::PARAM_INT);
                                $stmt->execute();
                                $diaryNotes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($diaryNotes as $key => $note): ?>
                                  <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= htmlspecialchars($note['subject'])  ?></td>
                                    <td class="diary-content"><?= $note['message'] ?></td>
                                    <td><?= htmlspecialchars($note['datercorded'])  ?></td>
                                    <td><?= htmlspecialchars($note['timerecorded']) ?></td>
                                    <td>
                                        <button 
                                            type="button" 
                                            class="btn btn-info btn-sm editBtn"
                                            data-id="<?= $note['id'] ?>">
                                            Edit
                                        </button>
                                    </td>
                              <?php endforeach ?>
                        </tbody>
                    </table>
				</div>
                <!-- Content Row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

<!-- Modal -->
<div class="modal fade" id="modelUnit" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary"><strong>My Diary Note</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-danger">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formUnit">
                    <input type="hidden" id="unitId" name="unitId">
                    <div class="form-group">
                        <label for="Subject">Subject/Topic</label>
                        <input class="form-control" id="SubjectName" type="text" name="Subject" placeholder="Enter Subject/Topic">
                        <small class="text-danger" id="errorSubject"></small>
                    </div>
                    <div class="form-group">
                        <label for="Message">Notes</label>
                        <textarea name="message" id="message_" class="form-control" rows="10"></textarea>
                        <small class="text-danger" id="errorMessage"></small>
                    </div>
                    <button type="submit" class="btn btn-primary" id="action-btn">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php
    require 'partials/footer.php';
?>


<script>
    let diaryTable;

$(document).ready(function () {

    diaryTable = $('#diaryTable').DataTable({
        pageLength: 20,
        ordering: true,
        order: [[3, 'desc'], [4, 'desc']], // date + time
        responsive: true
    });

});
</script>


<script>
function resetForm() {
    $('#formUnit')[0].reset();
    $('#unitId').val('');
    $('#errorSubject').text('');
    $('#errorMessage').text('');
    $('#action-btn')
        .removeClass('btn-info')
        .addClass('btn-primary')
        .text('Save')
        .data('mode', 'add');

    if (CKEDITOR.instances.message) {
        CKEDITOR.instances.message.setData('');
    }
}

$(document).ready(function () {

    $('#formUnit').on('submit', function (e) {
        e.preventDefault();

        $('#errorSubject').text('');
        $('#errorMessage').text('');

        $.ajax({
            url: 'model/diary.form.php',
            type: 'POST',
            dataType: 'JSON',
            data: $(this).serialize(),

           success: function (response) {

                if (response.status) {

                    let isUpdate = $('#unitId').val() !== '';

                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });

                    Toast.fire({
                        icon: isUpdate ? "info" : "success",
                        title: response.success.message
                    });

                    setTimeout(function () {
                        location.reload();
                    }, 2500);

                    $('#modelUnit').modal('hide');
                    resetForm();
                } else {

                    if (response.errors.Subject) {
                        $('#errorSubject').text(response.errors.Subject);
                    }

                    if (response.errors.message) {
                        $('#errorMessage').text(response.errors.message);
                    }
                }
            },

            error: function () {
                alert('Something went wrong!');
            }
        });
    });

});

$(document).on('click', '.editBtn', function () {

    let id = $(this).data('id');

    $.ajax({
        url: 'model/diary.fetch.php',
        type: 'POST',
        dataType: 'JSON',
        data: { id: id },

        success: function (response) {
            if (response.status) {

                $('#unitId').val(response.data.id);
                $('#SubjectName').val(response.data.subject);
                $('#message_').val(response.data.message);

                // ✅ Change button text & color
                $('#action-btn')
                    .text('Update')
                    .removeClass('btn-primary')
                    .addClass('btn-info');

                $('#modelUnit').modal('show');
            }
        }
    });
});


</script>