<?php include 'inc/header.php' ?>

<div class="wrapper">
  <?php include 'inc/sidebar.php'; ?>

  <div class="main-panel">
    <div class="main-header">
      <?php include 'inc/logo.php' ?>
      <?php include 'inc/navbar.php' ?>
    </div>

    <!---------------------------------Content------------------------------------->

    <div class="container">
      <div class="page-inner p-5">

        <div class="row">
          <div class="col-md-8 <?= (isset($_GET['pagess']) && !empty($_GET['pagess'])) ? 'd-none' : '' ?>">
            <div class="card p-5">
              <div class="card-header">
                <?php if(isset($_GET['program']) && isset($_GET['yr'])): ?>
                  <?php include '../model/students.php'; ?>

                  <table id="basic-datatables" class="display table table-striped table-hover">
                    <thead>
                      <tr>
                        <th>Section</th>
                        <th>Name</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $pr = $_GET['program'];
                        $yr = $_GET['yr'];
                        $students = getStudentsByYear($pr, $yr);
                        if(!empty($students)):
                          foreach($students as $stud):
                      ?>
                        <tr>
                          <td><?= strtoupper($stud['section_name']) ?></td>
                          <td>
                            <a href="?program=<?= $pr ?>&&yr=<?= $yr ?>&&student=<?= $stud['student_id'] ?>" style="color:inherit;text-decoration:none;">
                              <?= ucwords($stud['last_name'] . ", " . $stud['first_name'] . " " . $stud['middle_name']) ?>
                            </a>
                          </td>
                          <td>
<button class="btn btn-sm btn-outline-primary edit-btn"
  data-id="<?= $stud['student_id'] ?>"
  data-lname="<?= htmlspecialchars($stud['last_name']) ?>"
  data-fname="<?= htmlspecialchars($stud['first_name']) ?>"
  data-mname="<?= htmlspecialchars($stud['middle_name']) ?>"
  data-sex="<?= $stud['sex'] ?>"
  data-bday="<?= $stud['birth_date'] ?>"
  data-cont="<?= $stud['contact_number'] ?>"
  data-email="<?= $stud['stud_email'] ?>"
  data-year="<?= $stud['year_level_id'] ?>"
  data-prog="<?= $stud['program_id'] ?>"
  data-sec="<?= $stud['section_id'] ?>"
  data-status="<?= $stud['status'] ?>">
  <i class="fa fa-edit"></i>
</button>


                            <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete(<?= $stud['student_id'] ?>, '<?= $pr ?>', '<?= $yr ?>')">
                              <i class="fa fa-trash"></i>
                            </button>
                          </td>
                        </tr>
                      <?php endforeach; endif; ?>
                    </tbody>
                  </table>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <div class="<?= (isset($_GET['pagess']) && $_GET['pagess'] == 'addProgram') ? 'col-md-8' : 'col-md-4' ?>">
            <div class="card p-5">
    <?php if(!empty($_GET['student'])): ?>
  <?php
    $id = $_GET['student'];
    $stud = getStudentById($id);
    $visitCount = getVisitCount($id);
  ?>
  <div class="row justify-content-center">
    <h4 class="text-center">Mini-Medical Record</h4>
    <hr>
    <h6>Student Information</h6>
    <p>
      <strong style="font-size: 13px;">Student No:</strong> <u><?= $stud['student_number'] ?></u><br>
      <strong style="font-size: 13px;">Name:</strong> <u><?= ucwords($stud['last_name'] . ", " . $stud['first_name'] . " " . $stud['middle_name']) ?></u><br>
      <strong style="font-size: 13px;">Birthday:</strong> <u><?= date("F d, Y", strtotime($stud['birth_date'])) ?></u><br>
      <strong style="font-size: 13px;">Sex:</strong> <u><?= strtoupper($stud['sex']) ?></u><br>
      <strong style="font-size: 13px;">Contact #:</strong> <u><?= $stud['contact_number'] ?></u><br>
      <strong style="font-size: 13px;">Email:</strong> <u><?= $stud['stud_email'] ?></u><br>
      <strong style="font-size: 13px;">Program:</strong> <u><?= strtoupper($stud['program_code']) ?></u><br>
      <strong style="font-size: 13px;">Year Level:</strong> <u><?= ucwords($stud['year_level_name']) ?> Year</u><br>
      <strong style="font-size: 13px;">Student Type:</strong> <u><?= ucwords($stud['status']) ?></u><br>
      <strong style="font-size: 13px;">Clinic Visits:</strong> <u><?= $visitCount ?> visit<?= $visitCount != 1 ? 's' : '' ?></u><br>
    </p>
    <hr>
    <h6>Recent Visits</h6>
    <?php
      $visit = getRecentVisits($id);
      if($visit):
        foreach($visit as $vs):
    ?>
      <small class="px-5">
        <?= ucwords($vs['diagnosis']) ?><br>
        <?= date("F d, Y - g:i A", strtotime($vs['created_at'])) ?>
      </small><br>
    <?php endforeach; else: ?>
      <small>No Data Found.</small>
    <?php endif; ?>
  </div>

              <?php elseif(isset($_GET['pagess']) && $_GET['pagess'] == "addProgram"): ?>
                <form action="../pages/program.php" method="POST">
                  <div class="card-header">
                    <div class="card-title">ADD PROGRAM</div>
                  </div>
                  <div class="card-body">
                    <div class="form-group">
                      <div class="input-group mb-3">
                        <span class="input-group-text">Program Name: </span>
                        <input type="text" name="prog_name" class="form-control" placeholder="e.g. Bachelor of Science in Information Technology" required />
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group mb-3">
                        <span class="input-group-text">Program Code: </span>
                        <input type="text" name="code" class="form-control" placeholder="e.g. BSIT" required />
                      </div>
                    </div>
                  </div>
                  <div class="card-footer p-3">
                    <input type="submit" name="addProgram" class="btn btn-success float-end mb-3">
                  </div>
                </form>

              <?php else: ?>
                <p class="text-center mt-3" style="font-family: 'Roboto', sans-serif;">
                  "Medicine is not only about treating illness but also about caring 
                  for people with compassion, accuracy, and responsibility."
                </p>
                <p class="text-end" style="font-family: 'Roboto', sans-serif;">— William Osler</p>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
              <div class="modal-header px-4 pt-4 pb-3">
                <div class="ms-3">
                  <h6 class="mb-0 fw-semibold">Edit Student</h6>
                  <small class="text-muted">Update student information.</small>
                </div>
                <button class="btn-close ms-auto" data-bs-dismiss="modal"></button>
              </div>
              <form action="../pages/student.php" method="POST">
                <div class="modal-body px-4 py-3 row">
                  <input type="hidden" name="student_id" id="edit_id">
                  <input type="hidden" name="program" value="<?= $_GET['program'] ?? '' ?>">
                  <input type="hidden" name="yr" value="<?= $_GET['yr'] ?? '' ?>">

                  <?php
                    include_once '../model/fetch.php';
                    $programs = getPrograms();
                    $years    = getYear();
                    $sections = getSection();
                  ?>

                  <div class="form-group col-md-6">
                    <label>Last Name</label>
                    <input type="text" name="lname" id="edit_lname" class="form-control" required>
                  </div>
                  <div class="form-group col-md-6">
                    <label>First Name</label>
                    <input type="text" name="fname" id="edit_fname" class="form-control" required>
                  </div>
                  <div class="form-group col-md-6">
                    <label>Middle Name</label>
                    <input type="text" name="mname" id="edit_mname" class="form-control">
                  </div>
                  <div class="form-group col-md-6">
                    <label>Sex</label>
                    <select name="sex" id="edit_sex" class="form-control">
                      <option value="Male">MALE</option>
                      <option value="Female">FEMALE</option>
                    </select>
                  </div>
                  <div class="form-group col-md-6">
                    <label>Birthday</label>
                    <input type="date" name="bday" id="edit_bday" class="form-control" required>
                  </div>
                  <div class="form-group col-md-6">
                    <label>Contact Number</label>
                    <input type="text" name="cont" id="edit_cont" class="form-control" required>
                  </div>
                  <div class="form-group col-md-12">
                    <label>Email</label>
                    <input type="email" name="email" id="edit_email" class="form-control" required>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Year Level</label>
                    <select name="year" id="edit_year" class="form-control">
                      <?php foreach($years as $yr): ?>
                        <option value="<?= $yr['year_level_id'] ?>"><?= strtoupper($yr['year_level_name']) ?> YEAR</option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Program</label>
                    <select name="prog" id="edit_prog" class="form-control">
                      <?php foreach($programs as $pr): ?>
                        <option value="<?= $pr['program_id'] ?>"><?= strtoupper($pr['program_code']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Section</label>
                    <select name="sec" id="edit_sec" class="form-control">
                      <?php foreach($sections as $sec): ?>
                        <option value="<?= $sec['section_id'] ?>"><?= strtoupper($sec['section_name']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Student Type</label>
                    <select name="student_type" id="edit_student_type" class="form-control">
                      <option value="Regular">REGULAR</option>
                      <option value="Irregular">IRREGULAR</option>
                    </select>
                  </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                  <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" name="editStudent" class="btn btn-sm btn-success">Save Changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
              <div class="modal-header px-4 pt-4 pb-3">
                <div class="rounded-circle d-flex justify-content-center align-items-center" style="width:40px;height:40px;background:#fee2e2;">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2">
                    <path d="M12 9v4m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                  </svg>
                </div>
                <div class="ms-3">
                  <h6 class="mb-0 fw-semibold">Delete Student</h6>
                  <small class="text-muted">This action cannot be undone.</small>
                </div>
                <button class="btn-close ms-auto" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body px-4 py-3">
                Are you sure you want to delete this student? All related records will also be removed.
              </div>
              <div class="modal-footer border-0 px-4 pb-4">
                <button class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <a id="confirmDeleteBtn" href="#" class="btn btn-sm btn-danger">Yes, Delete</a>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!---------------------------------End Content------------------------------------->

  </div>
</div>

<!-- Toast notification -->
<?php
  $msg = $_SESSION['msg'] ?? null;
  $isSuccess = $msg && $msg['type'] === 'success';
?>

<?php
  $msg = $_SESSION['msg'] ?? null;
  $isSuccess = $msg && $msg['type'] === 'success';
?>

<?php if($msg): ?>
<div class="modal fade" id="resultModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
      <div class="modal-header px-4 pt-4 pb-3 border-0">
        <div class="rounded-circle d-flex justify-content-center align-items-center me-3"
             style="width:42px;height:42px;background:<?= $isSuccess ? '#dcfce7' : '#fee2e2' ?>;flex-shrink:0;">
          <?php if($isSuccess): ?>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5">
              <path d="M20 6L9 17l-5-5"/>
            </svg>
          <?php else: ?>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2">
              <path d="M12 9v4m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
            </svg>
          <?php endif; ?>
        </div>
        <div>
          <h6 class="mb-0 fw-semibold"><?= $isSuccess ? 'Success!' : 'Something went wrong.' ?></h6>
          <small class="text-muted"><?= htmlspecialchars($msg['text'] ?? '') ?></small>
        </div>
        <button class="btn-close ms-auto" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-footer border-0 px-4 pb-4">
        <button class="btn btn-sm <?= $isSuccess ? 'btn-success' : 'btn-danger' ?>" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>
<?php unset($_SESSION['msg']); endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

  // Edit button
  document.querySelectorAll('.edit-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.getElementById('edit_id').value    = this.dataset.id;
      document.getElementById('edit_lname').value = this.dataset.lname;
      document.getElementById('edit_fname').value = this.dataset.fname;
      document.getElementById('edit_mname').value = this.dataset.mname;
      document.getElementById('edit_bday').value  = this.dataset.bday;
      document.getElementById('edit_cont').value  = this.dataset.cont;
      document.getElementById('edit_email').value = this.dataset.email;
      document.getElementById('edit_year').value  = this.dataset.year;
      document.getElementById('edit_prog').value  = this.dataset.prog;
      document.getElementById('edit_sec').value   = this.dataset.sec;

      const sexSelect = document.getElementById('edit_sex');
      Array.from(sexSelect.options).forEach(opt => {
        opt.selected = opt.value.toLowerCase() === this.dataset.sex.toLowerCase();
      });

      const typeSelect = document.getElementById('edit_student_type');
      Array.from(typeSelect.options).forEach(opt => {
        opt.selected = opt.value.toLowerCase() === this.dataset.status.toLowerCase();
      });

      new bootstrap.Modal(document.getElementById('editModal')).show();
    });
  });

  // Delete button
  window.confirmDelete = function(id, program, yr) {
    document.getElementById('confirmDeleteBtn').href =
      '../pages/student.php?deleteStudent=' + id + '&&program=' + program + '&&yr=' + yr;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
  };

  // Result modal — show once, clean up backdrop on hide
  <?php if(isset($msg)): ?>
  const resultModalEl = document.getElementById('resultModal');
  const resultModal = new bootstrap.Modal(resultModalEl, { backdrop: true });
  resultModal.show();
  resultModalEl.addEventListener('hidden.bs.modal', function () {
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('overflow');
    document.body.style.removeProperty('padding-right');
  });
  <?php endif; ?>

});
</script>
<?php include 'inc/footer.php' ?>