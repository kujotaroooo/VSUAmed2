    <?php include 'inc/header.php' ?>
    <!-------------------------------------------------------------------------------------------------->
<style>
  @media print {
    body * { visibility: hidden; }
    #print-section, #print-section * { visibility: visible; }
    #print-section {
      position: absolute;
      top: 0; left: 0;
      width: 100%;
      padding: 40px;
    }
  }
</style>

<script>
function printRecord(id) {
  const content = document.getElementById('print-area-' + id).innerHTML;
  const win = window.open('', '', 'width=800,height=600');
  win.document.write(`
    <html>
      <head>
        <title>Medical Record</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <style>
          body { padding: 40px; font-family: sans-serif; }
          hr { margin: 16px 0; }
          .border-start { border-left: 1px solid #dee2e6 !important; padding-left: 16px; }
          .print-header { display: flex; align-items: center; gap: 16px; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 16px; }
          .print-header img { width: 80px; height: 80px; object-fit: contain; }
          .print-header-text { line-height: 1.3; }
          .print-header-text h5 { margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase; }
          .print-header-text p { margin: 0; font-size: 12px; color: #444; }
          .print-header-text small { font-size: 11px; color: #666; }
        </style>
      </head>
      <body onload="window.print(); window.close();">

        <!-- VSU Header -->
        <div class="print-header">
          <img src="https://www.vsu.edu.ph/images/VSU_Seal_2022.png" alt="VSU Seal">
          <div class="print-header-text">
            <h5>Visayas State University</h5>
            <p>Alang-alang, Leyte</p>
            <small>Clinic Medical Record</small>
          </div>
        </div>

        ${content}
      </body>
    </html>
  `);
  win.document.close();
}
</script>
    <div class="wrapper">
      <!-- Sidebar -->
        <?php include 'inc/sidebar.php'; ?>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          
          <!-- LOGO -->
            <?php include 'inc/logo.php' ?>
          <!-- End LOGO -->

          <!-- Navbar Header -->
            <?php include 'inc/navbar.php' ?>
          <!-- End Navbar -->
        
        </div>

        <!---------------------------------Content------------------------------------->

        <div class="container">
          <div class="page-inner">

            <div class="card p-5">
              <table
                id="basic-datatables"
                class="display table table-striped table-hover"
                >
                <thead>
                  <tr class="text-center">
                    <th>#</th>
                    <th>Student Number</th>
                    <th>Diagnosis</th>
                    <th>Date Time</th>
                  </tr>
                </thead>

                <tbody>
                  <?php
                    $history = getVisitHistory();
                    if($history):
                      $n = 1;
                      foreach($history as $h):
                    ?>
                    <tr data-bs-toggle="modal" data-bs-target="#hello<?= $h['visit_id'] ?>" style="cursor:pointer;">
                      <td class="text-end"><?= $n++ . ". " ?></td>
                      <td class="text-center"><?= $h['student_number'] ?></td>
                      <td><?= $h['diagnosis'] ?></td>
                      <td class="text-center"><?= date("F d, Y - g:i A", strtotime($h['created_at']))?></td>
                    </tr>
<div class="modal fade" id="hello<?= $h['visit_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body p-5" id="print-area-<?= $h['visit_id'] ?>">
        
        <!-- Header -->
        <div class="text-center mb-3">
          <h5 class="fw-bold mb-1"><?= ucwords($h['last_name'] . ", " . $h['first_name'] . " " . $h['middle_name'][0] . ".") ?></h5>
          <p class="mb-0 text-muted small">
            <?= strtoupper(htmlspecialchars($h['program_name'] ?? 'N/A')) ?> &mdash;
            <?= strtoupper(htmlspecialchars($h['year_level_name'] ?? 'N/A')) ?> YEAR &nbsp;|&nbsp;
            SECTION: <?= strtoupper(htmlspecialchars($h['section_name'] ?? 'N/A')) ?>
          </p>
          <small class="text-muted"><?= date("F d, Y - g:i A", strtotime($h['created_at'])); ?></small>
        </div>

        <hr>

        <!-- Medical Info -->
        <div class="row mb-3">
          <div class="col-md-6">
            <div class="mb-3">
              <p class="text-uppercase fw-semibold mb-1" style="font-size:11px; color:#888;">Complaint</p>
              <p class="mb-0"><?= htmlspecialchars($h['complaint']) ?></p>
            </div>
            <div class="mb-3">
              <p class="text-uppercase fw-semibold mb-1" style="font-size:11px; color:#888;">Diagnosis</p>
              <p class="mb-0"><?= htmlspecialchars($h['diagnosis']) ?></p>
            </div>
          </div>
          <div class="col-md-6 border-start">
            <div class="mb-3">
              <p class="text-uppercase fw-semibold mb-1" style="font-size:11px; color:#888;">Medicine</p>
              <p class="mb-0"><?= htmlspecialchars($h['medicine_name'] . " " . $h['dosage']) ?></p>
            </div>
            <div class="mb-3">
              <p class="text-uppercase fw-semibold mb-1" style="font-size:11px; color:#888;">Duration</p>
              <p class="mb-0"><?= htmlspecialchars($h['duration']) ?></p>
            </div>
          </div>
        </div>

        <hr>

        <!-- Instructions & Notes -->
        <div class="mb-3">
          <p class="text-uppercase fw-semibold mb-1" style="font-size:11px; color:#888;">Instructions</p>
          <p class="mb-0 ms-3"><?= htmlspecialchars($h['instructions']) ?></p>
        </div>
        <div class="mb-3">
          <p class="text-uppercase fw-semibold mb-1" style="font-size:11px; color:#888;">Notes</p>
          <p class="mb-0 ms-3"><?= htmlspecialchars($h['notes']) ?></p>
        </div>

        <hr>

        <!-- Nurse -->
        <div class="text-end">
          <p class="text-uppercase fw-semibold mb-1" style="font-size:11px; color:#888;">Attended by</p>
          <p class="mb-0 fw-bold"><?= htmlspecialchars($h['fulll_name']) ?></p>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="printRecord(<?= $h['visit_id'] ?>)">
          <i class="fas fa-print me-1"></i> Print / Save as PDF
        </button>
      </div>
    </div>
  </div>
</div>
                  <?php endforeach; endif; ?>
                </tbody>
              </table>
            </div>
            
          </div>
        </div>

        <!---------------------------------End Content------------------------------------->

      </div>
    <!-------------------------------------------------------------------------------------------------->
    <?php include 'inc/footer.php' ?>