<style>
    .sidebar .nav-item a:hover p,
    .sidebar .nav-item a:hover i,
    .sidebar .nav-item a:hover span {
        color: #17a2b8 !important;
        transition: color 0.3s ease;
    }

    .sidebar .nav-item a:hover .sub-item {
        color: #17a2b8 !important;
        transition: color 0.3s ease;
    }
    p:hover {
      color: #17a2b8;
}
</style>
      <div class="sidebar" style="background-color: #000230">
          <div class="sidebar-logo">
            <!-- Logo Header -->
            <?php
              $page = basename($_SERVER['PHP_SELF']);
            ?>
            <div class="logo-header" style="background-color: #17a2b8">
              <a href="#" class="logo">
              
                <p class="text-white text-center h4" style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;">CLINIC RECORDS</p>
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <div class="sidebar-wrapper scrollbar scrollbar-inner">
            <div class="sidebar-content text-white">
              <ul class="nav nav-secondary" id="mainSidebarAccordion">

                <!---------------------- Sidebar ----------------------->
                
                <li class="nav-item <?= $page == 'dashboard.php' ? 'submenu' : '' ?>"> <!-- Dashboard Button -->
                  <a href="dashboard.php">
                    <i class="fas fa-layer-group"></i>
                    <p>Dashboard</p>
                  </a>
                </li>

                <hr class="m-3">

                <li class="nav-item <?= $page == 'addRecord.php' ? 'submenu' : '' ?>"> <!-- Dashboard Button -->
                  <a href="addRecord.php">
                    <i class="fas fa-plus"></i>
                    <p>Add Record</p>
                  </a>
                </li>

                <li class="nav-item">
                  <a data-bs-toggle="collapse" href="#medRec">
                    <i class="fas fa-book"></i>
                    <p>Medical Records</p>
                    <span class="caret"></span>
                  </a>

                  <div class="collapse <?= isset($_GET['program']) || isset($_GET['pagess']) ? 'show' : '' ?>"
                      id="medRec"
                      data-bs-parent="#mainSidebarAccordion">

                    <ul class="nav nav-collapse" id="programAccordion">

                      <?php
                      include_once '../model/fetch.php';
                      $programs = getPrograms();

                      if($programs):
                        foreach($programs as $prog):
                        ?>
                        <li>
                          <a data-bs-toggle="collapse" href="#programs<?= $prog['program_id'] ?>">
                            <span class="sub-item"><?= strtoupper($prog['program_code']) ?></span>
                            <span class="caret"></span>
                          </a>
                          <div class="collapse" id="programs<?= $prog['program_id'] ?>" data-bs-parent="#programAccordion">
                            <ul class="nav nav-collapse subnav">
                              <?php
                                $years = getYear();
                                foreach ($years as $yr):
                                ?>
                                <li>
                                  <a href="medicalrecord.php?program=<?= $prog['program_id'] ?>&&yr=<?= $yr['year_level_id'] ?>">
                                    <span class="sub-item"><?= $yr['year_level_name'] . " Year" ?></span>
                                  </a>
                                </li>
                              <?php endforeach; ?>
                            </ul>
                          </div>
                        </li>
                      <?php endforeach; endif; ?>

                      <li class="<?= isset($_GET['pagess']) ? 'active' : '' ?>">
                        <a href="medicalrecord.php?pagess=addProgram">
                          <span class="sub-item">Add Program</span>
                        </a>
                      </li>

                    </ul>
                  </div>
                </li>

                <li class="nav-item <?= $page == 'history.php' ? 'submenu' : '' ?>"> <!-- Dashboard Button -->
                  <a href="history.php">
                    <i class="fas fa-history"></i>
                    <p>History</p>
                  </a>
                </li>

                <hr class="m-3">

                <li class="nav-item"> <!-- Student Collapse Button -->
                  <a data-bs-toggle="collapse" href="#Students" class="collapsed">
                    <i class="fas fa-users"></i>
                    <p class="navs">Students</p>
                    <span class="caret"></span>
                  </a>
                  <div class="collapse <?= isset($_GET['page']) || isset($_GET['pages']) ? 'show' : '' ?>" id="Students" data-bs-parent="#mainSidebarAccordion">
                    <ul class="nav nav-collapse">

                      <li class="<?= isset($_GET['page'])== 'AddStudent' ? 'active' : '' ?>">
                        <a href="manage_students.php?page=AddStudent">
                          <span class="sub-item">Add Students</span>
                        </a>
                      </li>

                      <li class="<?= isset($_GET['pages']) == 'SearchStudent' ? 'active' : '' ?>">
                        <a href="manage_students.php?pages=SearchStudent">
                          <span class="sub-item">Search Students</span>
                        </a>
                      </li>



                    </ul>
                  </div>
                </li>
                
                <li class="nav-item mt-5"> <!-- Dashboard Button -->
                  <a href="../pages/logout.php">
                    <i class="fas fa-lock"></i>
                    <p>Logout</p>
                  </a>
                </li>

                <!-- <li class="nav-item">
                  <a data-bs-toggle="collapse" href="#submenu">
                    <i class="fas fa-bars"></i>
                    <p>Menu Levels</p>
                    <span class="caret"></span>
                  </a>
                  <div class="collapse" id="submenu">
                    <ul class="nav nav-collapse">
                      <li>
                        <a data-bs-toggle="collapse" href="#subnav1">
                          <span class="sub-item">Level 1</span>
                          <span class="caret"></span>
                        </a>
                        <div class="collapse" id="subnav1">
                          <ul class="nav nav-collapse subnav">
                            <li>
                              <a href="#">
                                <span class="sub-item">Level 2</span>
                              </a>
                            </li>
                            <li>
                              <a href="#">
                                <span class="sub-item">Level 2</span>
                              </a>
                            </li>
                          </ul>
                        </div>
                      </li>
                      <li>
                        <a data-bs-toggle="collapse" href="#subnav2">
                          <span class="sub-item">Level 1</span>
                          <span class="caret"></span>
                        </a>
                        <div class="collapse" id="subnav2">
                          <ul class="nav nav-collapse subnav">
                            <li>
                              <a href="#">
                                <span class="sub-item">Level 2</span>
                              </a>
                            </li>
                          </ul>
                        </div>
                      </li>
                      <li>
                        <a href="#">
                          <span class="sub-item">Level 1</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </li> --> <!--- if collapsible menu is needed -->


                <!---------------------- Sidebar ----------------------->


              </ul>
            </div>
          </div>
        </div>