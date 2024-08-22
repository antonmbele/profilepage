<?php
// Include database connection and user functions
include "db_conn.php";
include 'php/User.php';

// Initialize variables
$firstname = $lastname = $email = $phone = $profile  = $dob = $gender =$nationality =$location = $education = $experience = $skills = $spesializtion_name ='';

 
$user_id = 12; // Example user ID, replace with the actual user ID from session or other method

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $profile = $_FILES['profile']['name'] ?? '';
   
    // Check if a new profile picture is uploaded
    if (!empty($profile)) {
        $target_dir = "upload/";
        $target_file = $target_dir . basename($_FILES["profile"]["name"]);

        // Check if file was uploaded without errors
        if (move_uploaded_file($_FILES["profile"]["tmp_name"], $target_file)) {
            // File upload successful
        } else {
            // File upload failed
            echo "Sorry, there was an error uploading your file.";
            $profile = $_POST['profile']; // Revert to old profile picture
        }
    } else {
        $profile = $_POST['profile']; // Use the old profile picture if no new one is uploaded
    }

    // Update the user information in the database using PDO
    $sql = "UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, phone = :phone, profile = :profile WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':profile', $profile);
    $stmt->bindParam(':id', $user_id);

    if ($stmt->execute()) {
        // Reload the page to show updated information
        header("Location: profile.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->errorInfo()[2];
    }
} else {
    // Retrieve user information if not a POST request
    $user = getUserById($user_id, $conn);

    if ($user) {
        $firstname = $user['firstname'] ?? '';  // Use empty string as a fallback
        $lastname = $user['lastname'] ?? '';
        $email = $user['email'] ?? '';
        $phone = $user['phone'] ?? '';
        $profile = $user['profile'] ?? 'default.jpg'; // Set a default profile picture if none is available
        $dob = $user['dob'] ?? '';  // Retrieve dob
        $gender = $user['gender'] ?? '';  // Retrieve gender
        $nationality = $user['nationality'] ?? '';  // Retrieve nationality
        $location = $user['location'] ?? '';  // Retrieve location
        $education = $user['education'] ?? '';  // Retrieve education
        $experience = $user['experience'] ?? '';  // experience
        $skills = $user['skills'] ?? '';  // Retrieve skills
        $position = $user['position'] ?? '';  // Retrieve gender
        

    } else {
        echo "<p>User not found.</p>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="AdminLTE/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="AdminLTE/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <?php if ($user) { ?>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">


        <!-- Sidebar -->
        <div class="sidebar">
            <div class=" user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="text-center">
                    <img class="img-circle elevation-2" src="upload/<?= htmlspecialchars($profile) ?>"
                        alt="User profile picture">
                </div>
                <div class="info">
                    <h3 class="profile-username text-center text-light ">
                        <?= htmlspecialchars($firstname) . ' ' . htmlspecialchars($lastname) ?>
                    </h3>
                </div>
            </div>


            <li class="nav-item menu-open">
                <a href="profile.php" class="nav-link active">
                    <p>
                        Settings
                    </p>
                </a>
            </li>


            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Settings</h1>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link " href="#myprofile" data-toggle="tab">My
                                            Profile</a></li>
                                    <li class="nav-item"><a class="nav-link " href="#verification"
                                            data-toggle="tab">Verification</a></li>
                                    <li class="nav-item"><a class="nav-link " href="#sessions"
                                            data-toggle="tab">Sessions</a></li>
                                    <li class="nav-item"><a class="nav-link " href="#password"
                                            data-toggle="tab">Password/Authorization</a></li>
                                </ul>
                            </div><!-- /.card-header -->
                            
                            <div class="card-body">
                                <div class="tab-content">
                                    <!-- Profile Tab -->
                                    <div class="active tab-pane" id="myprofile">
                                        <div class="container-fluid">
                                            <div class="row ">
                                                <div class="col-md-4 ">
                                                    <div class="card card-primary card-outline">
                                                        <div class="card-body box-profile">
                                                            <div class="text-center">
                                                                <img class="profile-user-img img-fluid rounded-circle"
                                                                    src="upload/<?= htmlspecialchars($profile) ?>"
                                                                    alt="User profile picture">
                                                            </div>


                                                            <h3 class="profile-username text-center">
                                                                <?= htmlspecialchars($firstname) . ' ' . htmlspecialchars($lastname) ?>
                                                            </h3>

                                                            <p class="text-muted text-center">
                                                                <?= htmlspecialchars($email) ?>
                                                            </p>

                                                            <ul class="list-group list-group-unbordered mb-3">
                                                                <li class="list-group-item">
                                                                  <b>DOB</b> <a class="float-right">
                                                                    <?= htmlspecialchars($dob ) ?>
                                                                </a>
                                                                 </li>
                                                                <li class="list-group-item">
                                                                    <b>Gender</b> <a class="float-right">
                                                                        <?= htmlspecialchars($gender ) ?>
                                                                    </a>
                                                                </li>
                                                                <li class="list-group-item">
                                                                    <b>Nationality</b> <a class="float-right">
                                                                        <?= htmlspecialchars($nationality ) ?>
                                                                    </a>
                                                                </li>
                                                                <li class="list-group-item">
                                                                    <b>Phone</b> <a class="float-right">
                                                                        <?= htmlspecialchars($phone) ?>
                                                                    </a>
                                                                </li>
                                                            </ul>

                                                            <ul class="nav nav-pills">
                                                                <li class="nav-item">
                                                                    <a class="nav-link btn btn-primary text-light"
                                                                        href="#updateprofile" data-toggle="tab">Update
                                                                        Profile</a>
                                                                </li>
                                                            </ul>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- demographic Tab -->
                                                <div class="col-md-8 ">
                                                    <div class="card card-primary card-outline">
                                                        <div class="card-body box-profile">
                                                        <strong><i class="fas fa-map-marker-alt mr-1"></i>
                                                                Location</strong>

                                                            <p class="text-muted"> 
                                                            <?= htmlspecialchars($location ) ?>
                                                            </p>

                                                            <hr>
                                                            <strong><i class="fas fa-book mr-1"></i> Education</strong>

                                                            <p class="text-muted">
                                                             <?= htmlspecialchars($education ) ?>
                                                            </p>

                                                            <hr>

                                                            

                                                            <strong><i class="fas fa-pencil-alt mr-1"></i>
                                                                Skills</strong>

                                                            <p class="text-muted">
                                                            <?= htmlspecialchars($skills ) ?>
                                                            </p>

                                                            <hr>
                                                            <strong><i class="fas fa-briefcase mr-1"></i>
                                                                Experience</strong>

                                                            <p class="text-muted">
                                                            <?= htmlspecialchars($experience ) ?>
                                                            </p>

                                                            <hr>

                                                            <strong><i class="fas fa-briefcase mr-1"></i>
                                                                Specilization/Position</strong>

                                                            <p class="text-muted"> 
                                                            <?= htmlspecialchars($position) ?>
                                                            </p>





                                                        </div>
                                                    </div>
                                                </div>
                                             </div>

                                            </div>
                                            <div class="row">

                                                <!-- portifolio Tab -->
                                                <div class="col-md-12 ">
                                                    <div class="card card-primary card-outline">
                                                        <div class="card-header">
                                                            <h6>Your upload</h6>
                                                        </div>
                                                        <div class="card-body box-profile">
                                                              <!-- File 1 -->
                                                            <div class="card mb-3 border">
                                                                <div class="media p-3">
                                                                    <i class="fas fa-file-pdf text-danger mr-2 "
                                                                        style="font-size: 45px;"></i>
                                                                    <div class="media-body">
                                                                        <h6 class="mt-0">Itinerary-Trip.pdf</h6>
                                                                        <small>1.17MB | 1 tag | 09:52</small>
                                                                    </div>
                                                                    <div class="mt-2 gx-5">
                                                                        <button class="btn btn-secondary">
                                                                            <i class="fas fa-eye"></i>
                                                                        </button>
                                                                        <button class="btn btn-success">
                                                                            <i class="fas fa-download"></i>
                                                                        </button>
                                                                        <button class="btn btn-danger">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- File 2 -->
                                                            <div class="card mb-3 border">
                                                                <div class="media p-3">
                                                                    <i class="fas fa-file-image text-secondary mr-2 "
                                                                        style="font-size: 45px;"></i>
                                                                    <div class="media-body">
                                                                        <h6 class="mt-0">Itinerary-Trip.pdf</h6>
                                                                        <small>1.17MB | 1 tag | 09:52</small>
                                                                    </div>
                                                                    <div class="mt-2 gx-5">
                                                                        <button class="btn btn-secondary">
                                                                            <i class="fas fa-eye"></i>
                                                                        </button>
                                                                        <button class="btn btn-success">
                                                                            <i class="fas fa-download"></i>
                                                                        </button>
                                                                        <button class="btn btn-danger">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- File 3 -->
                                                            <div class="card mb-3 border">
                                                                <div class="media p-3">
                                                                    <i class="fas fa-file-word text-primary mr-2 mt-1"
                                                                        style="font-size: 45px;"></i>
                                                                    <div class="media-body">
                                                                        <h6 class="mt-0">Itinerary-Trip.pdf</h6>
                                                                        <small>1.17MB | 1 tag | 09:52</small>
                                                                    </div>
                                                                    <div class="mt-2">
                                                                        <button class="btn btn-secondary">
                                                                            <i class="fas fa-eye"></i>
                                                                        </button>
                                                                        <button class="btn btn-success">
                                                                            <i class="fas fa-download"></i>
                                                                        </button>
                                                                        <button class="btn btn-danger">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <hr>


                                                            <!-- Add New File Section -->
                                                            <h3 class="mt-3">Add a new file</h3>
                                                            <div class="">
                                                                <input type="file" class="mt-3 mb-3" id="inputFile">
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-primary"
                                                                        type="button">Upload</button>
                                                                </div>
                                                            </div>



                                                        </div>
                                                    </div>
                                                </div>
                                                     

                                        </div>
                                    </div>

                                    <!-- Update Profile Tab -->
                                    <div class="tab-pane" id="updateprofile">
                                        <form action="profile.php" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="profile"
                                                value="<?= htmlspecialchars($profile) ?>">

                                            <div class="form-group">
                                                <label for="firstname">First Name</label>
                                                <input type="text" name="firstname" id="firstname" class="form-control"
                                                    value="<?= htmlspecialchars($firstname) ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="lastname">Last Name</label>
                                                <input type="text" name="lastname" id="lastname" class="form-control"
                                                    value="<?= htmlspecialchars($lastname) ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" name="email" id="email" class="form-control"
                                                    value="<?= htmlspecialchars($email) ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <input type="text" name="phone" id="phone" class="form-control"
                                                    value="<?= htmlspecialchars($phone) ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="profile">Profile Picture</label>
                                                <input type="file" name="profile" id="profile" class="form-control">
                                            </div>

                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                    </div>

                                    <div class="tab-pane" id="verification">
                                    <div class="container mt-4">
                                            <div class="card card-primary card-outline">
                                                <div class="card-header">
                                                    <h3 class="card-title">Your Uploads</h3>
                                                </div>

                                                <div class="card-body">
                                                    <!-- File 1 -->
                                                    <div class="card mb-3 border">
                                                        <div class="media p-3">
                                                            <i class="fas fa-file-pdf text-danger mr-2 "
                                                                style="font-size: 45px;"></i>
                                                            <div class="media-body">
                                                                <h6 class="mt-0">Itinerary-Trip.pdf</h6>
                                                                <small>1.17MB | 1 tag | 09:52</small>
                                                            </div>
                                                            <div class="mt-2 gx-5">
                                                                <button class="btn btn-secondary">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <button class="btn btn-success">
                                                                    <i class="fas fa-download"></i>
                                                                </button>
                                                                <button class="btn btn-danger">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- File 2 -->
                                                    <div class="card mb-3 border">
                                                        <div class="media p-3">
                                                            <i class="fas fa-file-image text-secondary mr-2 "
                                                                style="font-size: 45px;"></i>
                                                            <div class="media-body">
                                                                <h6 class="mt-0">Itinerary-Trip.pdf</h6>
                                                                <small>1.17MB | 1 tag | 09:52</small>
                                                            </div>
                                                            <div class="mt-2 gx-5">
                                                                <button class="btn btn-secondary">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <button class="btn btn-success">
                                                                    <i class="fas fa-download"></i>
                                                                </button>
                                                                <button class="btn btn-danger">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- File 3 -->
                                                    <div class="card mb-3 border">
                                                        <div class="media p-3">
                                                            <i class="fas fa-file-word text-primary mr-2 mt-1"
                                                                style="font-size: 45px;"></i>
                                                            <div class="media-body">
                                                                <h6 class="mt-0">Itinerary-Trip.pdf</h6>
                                                                <small>1.17MB | 1 tag | 09:52</small>
                                                            </div>
                                                            <div class="mt-2">
                                                                <button class="btn btn-secondary">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <button class="btn btn-success">
                                                                    <i class="fas fa-download"></i>
                                                                </button>
                                                                <button class="btn btn-danger">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr>


                                                    <!-- Add New File Section -->
                                                    <h3 class="mt-3">Add a new file</h3>
                                                    <div class="">
                                                        <input type="file" class="mt-3 mb-3" id="inputFile">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-primary"
                                                                type="button">Upload</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <!-- /.tab-pane -->
                                    <div class="tab-pane" id="sessions">
                                    <main role="main" class="col-12 col-md-12">
                                            <div
                                                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                                                <h1 class="h2">Your Sessions</h1>
                                            </div>

                                            <!-- Session Management Table -->
                                            <div class="card mt-4">
                                                <div class="card-header">
                                                    Active Sessions
                                                </div>
                                                <div class="card-body">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Session ID</th>
                                                                <th scope="col">Device</th>
                                                                <th scope="col">Location</th>
                                                                <th scope="col">Login Time</th>
                                                                <th scope="col">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Example session rows -->
                                                            <tr>
                                                                <th scope="row">1</th>
                                                                <td>infinix</td>
                                                                <td>posta</td>
                                                                <td>2024-08-22 10:00:00</td>
                                                                <td>
                                                                    <button
                                                                        class="btn btn-danger btn-sm">Terminate</button>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">2</th>
                                                                <td>iPhone</td>
                                                                <td>kariakoo</td>
                                                                <td>2024-08-22 11:15:00</td>
                                                                <td>
                                                                    <button
                                                                        class="btn btn-danger btn-sm">Terminate</button>
                                                                </td>
                                                            </tr>
                                                            <!-- Add more session rows as needed -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </main>


                                         


                                    </div>

                                    <div class="tab-pane" id="password">
                                    
                                    <div class="d-flex justify-content-center align-items-center">
                                            <div class="card w-50">
                                                <div class="card-header bg-secondary text-white">
                                                    Change Password
                                                </div>
                                                <div class="card-body">
                                                    <form>
                                                        <div class="form-group">
                                                            <label for="currentPassword">Current Password</label>
                                                            <input type="password" class="form-control"
                                                                id="currentPassword"
                                                                placeholder="Enter current password">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="newPassword">New Password</label>
                                                            <input type="password" class="form-control" id="newPassword"
                                                                placeholder="Enter new password">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="confirmPassword">Confirm New Password</label>
                                                            <input type="password" class="form-control"
                                                                id="confirmPassword" placeholder="Confirm new password">
                                                        </div>
                                                        <button type="submit" class="btn btn-primary btn-block">Change
                                                            Password</button>
                                                    </form>
                                                    </div>

                                                </div>
                                                <!-- /.tab-content -->
                                            </div><!-- /.card-body -->
                                        </div>

                                       



                                    </div>
                                </div>

                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
    </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php } else { 
        // Handle the case where the user is not found
        echo "<p>User not found.</p>";
    } ?>

    <!-- jQuery -->
    <script src="AdminLTE/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="AdminLTE/dist/js/adminlte.min.js"></script>
    <script src="AdminLTE/dist/js/demo.js"></script>
</body>

</html>