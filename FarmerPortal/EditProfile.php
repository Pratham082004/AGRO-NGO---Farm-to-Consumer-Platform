<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../Includes/db.php");
include("../Includes/components/navbar.php");
include("../Includes/components/footer.php");

$sessphonenumber = $_SESSION['phonenumber'] ?? null;
$name = ''; $pan = ''; $phone = ''; $address = ''; $account = ''; $state = ''; $district = '';

if ($sessphonenumber) {
    $sql = "select * from farmerregistration where farmer_phone = '$sessphonenumber'";
    $run_query = mysqli_query($con, $sql);
    if ($run_query && $row = mysqli_fetch_array($run_query)) {
        $name = $row['farmer_name'];
        $pan = $row['farmer_pan'];
        $phone = $row['farmer_phone'];
        $address = $row['farmer_address'];
        $account = $row['farmer_bank'];
        $state = $row['farmer_state'];
        $district = $row['farmer_district'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile — Farmer Portal</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        function state() {
            var a = document.getElementById('states').value;
            var array = [];
            if (a === 'ANDAMAN & NICOBAR ISLANDS') {
                array = ['Andamans', 'Nicobars'];
            } else if (a === 'ANDHRA PRADESH') {
                array = ['Adilabad', 'Nizamabad', 'Karimnagar', 'Medak', 'Hyderabad', 'Rangareddi', 'Mahbubnagar', 'Nalgonda', 'Warangal', 'Khammam', 'Srikakulam', 'Vizianagaram', 'Visakhapatnam', 'East Godavari', 'West Godavari', 'Krishna', 'Guntur', 'Prakasam', 'Nellore', 'Cuddapah', 'Kurnool', 'Anantapur', 'Chittoor'];
            } else if (a === 'ASSAM') {
                array = ['Kokrajhar', 'Dhubri', 'Goalpara', 'Bongaigaon', 'Barpeta', 'Kamrup', 'Nalbari', 'Darrang', 'Marigaon', 'Nagaon', 'Sonitpur', 'Lakhimpur', 'Dhemaji', 'Tinsukia', 'Dibrugarh', 'Sibsagar', 'Jorhat', 'Golaghat', 'Karbi Anglong', 'North Cachar Hills', 'Cachar', 'Karimganj', 'Hailakandi'];
            } else if (a === 'BIHAR') {
                array = ['Pashchim Champaran', 'Purba Champaran', 'Sitamarhi', 'Madhubani', 'Supaul', 'Araria', 'Kishanganj', 'Purnia', 'Katihar', 'Madhepura', 'Saharsa', 'Darbhanga', 'Muzaffarpur', 'Gopalganj', 'Siwan', 'Saran', 'Vaishali', 'Samastipur', 'Begusarai', 'Khagaria', 'Bhagalpur', 'Munger', 'Nalanda', 'Patna', 'Bhojpur', 'Buxar', 'Rohtas', 'Gaya', 'Nawada'];
            } else if (a === 'GUJARAT') {
                array = ['Kachchh', 'Banas Kantha', 'Patan', 'Mahesana', 'Sabar Kantha', 'Gandhinagar', 'Ahmadabad', 'Surendranagar', 'Rajkot', 'Jamnagar', 'Porbandar', 'Junagadh', 'Amreli', 'Bhavnagar', 'Anand', 'Kheda', 'Panch Mahals', 'Vadodara', 'Bharuch', 'Surat', 'Valsad'];
            } else if (a === 'HARYANA') {
                array = ['Panchkula', 'Ambala', 'Yamunanagar', 'Kurukshetra', 'Kaithal', 'Karnal', 'Panipat', 'Sonipat', 'Jind', 'Fatehabad', 'Sirsa', 'Hisar', 'Bhiwani', 'Rohtak', 'Jhajjar', 'Mahendragarh', 'Rewari', 'Gurgaon', 'Faridabad'];
            } else if (a === 'HIMACHAL PRADESH') {
                array = ['Chamba', 'Kangra', 'Lahul & Spiti', 'Kullu', 'Mandi', 'Hamirpur', 'Una', 'Bilaspur', 'Solan', 'Sirmaur', 'Shimla', 'Kinnaur'];
            } else if (a === 'JAMMU AND KASHMIR') {
                array = ['Kupwara', 'Baramula', 'Srinagar', 'Badgam', 'Pulwama', 'Anantnag', 'Leh (Ladakh)', 'Kargil', 'Doda', 'Udhampur', 'Jammu', 'Kathua'];
            } else if (a === 'KARNATAKA') {
                array = ['Belgaum', 'Bagalkot', 'Bijapur', 'Gulbarga', 'Bidar', 'Raichur', 'Koppal', 'Gadag', 'Dharwad', 'Uttara Kannada', 'Haveri', 'Bellary', 'Chitradurga', 'Davangere', 'Shimoga', 'Udupi', 'Chikmagalur', 'Tumkur', 'Kolar', 'Bangalore', 'Bangalore Rural', 'Mandya', 'Hassan', 'Dakshina Kannada', 'Kodagu', 'Mysore'];
            } else if (a === 'KERALA') {
                array = ['Kasaragod', 'Kannur', 'Wayanad', 'Kozhikode', 'Malappuram', 'Palakkad', 'Thrissur', 'Ernakulam', 'Idukki', 'Kottayam', 'Alappuzha', 'Pathanamthitta', 'Kollam', 'Thiruvananthapuram'];
            } else if (a === 'MADHYA PRADESH') {
                array = ['Gwalior', 'Datia', 'Shivpuri', 'Guna', 'Sagar', 'Damoh', 'Satna', 'Rewa', 'Ujjain', 'Dewas', 'Dhar', 'Indore', 'Bhopal', 'Sehore', 'Raisen', 'Jabalpur', 'Balaghat'];
            } else if (a === 'MAHARASHTRA') {
                array = ['Nandurbar', 'Dhule', 'Jalgaon', 'Buldana', 'Akola', 'Washim', 'Amravati', 'Wardha', 'Nagpur', 'Bhandara', 'Gondiya', 'Gadchiroli', 'Chandrapur', 'Yavatmal', 'Nanded', 'Hingoli', 'Parbhani', 'Jalna', 'Aurangabad', 'Nashik', 'Thane', 'Mumbai', 'Raigarh', 'Pune', 'Ahmadnagar', 'Bid', 'Latur', 'Osmanabad', 'Solapur', 'Satara', 'Ratnagiri', 'Sindhudurg', 'Kolhapur', 'Sangli'];
            } else if (a === 'TAMIL NADU') {
                array = ['Chennai', 'Kancheepuram', 'Vellore', 'Dharmapuri', 'Salem', 'Erode', 'Coimbatore', 'Dindigul', 'Tiruchirappalli', 'Thanjavur', 'Madurai', 'Tirunelveli', 'Kanniyakumari'];
            } else if (a === 'UTTAR PRADESH') {
                array = ['Saharanpur', 'Muzaffarnagar', 'Bijnor', 'Moradabad', 'Rampur', 'Meerut', 'Ghaziabad', 'Bulandshahr', 'Aligarh', 'Mathura', 'Agra', 'Bareilly', 'Lucknow', 'Kanpur Nagar', 'Jhansi', 'Allahabad', 'Varanasi', 'Gorakhpur'];
            } else if (a === 'WEST BENGAL') {
                array = ['Darjiling', 'Jalpaiguri', 'Koch Bihar', 'Maldah', 'Murshidabad', 'Birbhum', 'Barddhaman', 'Nadia', 'North Twenty Four Parganas', 'Hugli', 'Bankura', 'Puruliya', 'Medinipur', 'Haora', 'Kolkata', 'South Twenty Four Parganas'];
            }

            var selectElem = document.getElementById('district');
            selectElem.innerHTML = "<option value=''>Select District</option>";
            for (var i = 0; i < array.length; i++) {
                var opt = document.createElement('option');
                opt.value = array[i];
                opt.innerHTML = array[i];
                selectElem.appendChild(opt);
            }
        }
    </script>
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('farmer', 'profile'); ?>

    <!-- Page Header -->
    <div class="agro-page-header" style="background: var(--surface-primary); border-bottom: 1px solid var(--gray-200);">
        <div class="agro-container">
            <div class="agro-page-header__breadcrumb">
                <a href="farmerHomepage.php">Home</a> / <a href="FarmerProfile.php">Profile</a> / <span>Edit Profile</span>
            </div>
            <h1 class="agro-page-header__title">Edit Account Details</h1>
            <p class="agro-page-header__desc">Keep your contact details, location, and banking information up to date.</p>
        </div>
    </div>

    <!-- Main Section -->
    <div class="agro-container agro-section">
        <div style="max-width: 760px; margin: 0 auto;">
            
            <?php if ($sessphonenumber): ?>
                <div class="agro-card agro-p-8">
                    <form action="EditProfile.php" method="post">
                        
                        <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                            <div class="agro-form-group">
                                <label class="agro-label"><i class="fas fa-user" style="margin-right:6px; color:var(--color-primary);"></i>Farmer Name (Read-only)</label>
                                <input type="text" class="agro-input" value="<?php echo htmlspecialchars($name); ?>" disabled />
                            </div>

                            <div class="agro-form-group">
                                <label class="agro-label"><i class="fas fa-id-card" style="margin-right:6px; color:var(--color-primary);"></i>PAN Number (Read-only)</label>
                                <input type="text" class="agro-input" value="<?php echo htmlspecialchars($pan); ?>" disabled />
                            </div>
                        </div>

                        <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                            <div class="agro-form-group">
                                <label class="agro-label" for="phonenumber"><i class="fas fa-phone-alt" style="margin-right:6px; color:var(--color-primary);"></i>Phone Number</label>
                                <input type="text" id="phonenumber" name="phonenumber" class="agro-input" value="<?php echo htmlspecialchars($phone); ?>" required />
                            </div>

                            <div class="agro-form-group">
                                <label class="agro-label" for="bank"><i class="fas fa-university" style="margin-right:6px; color:var(--color-primary);"></i>Bank Account Number</label>
                                <input type="text" id="bank" name="bank" class="agro-input" value="<?php echo htmlspecialchars($account); ?>" required />
                            </div>
                        </div>

                        <div class="agro-grid agro-grid-2" style="gap: var(--space-4);">
                            <div class="agro-form-group">
                                <label class="agro-label" for="states"><i class="fas fa-map-location-dot" style="margin-right:6px; color:var(--color-primary);"></i>State</label>
                                <select name="statevalue" id="states" onchange="state()" class="agro-select" required>
                                    <option value="">--Select State--</option>
                                    <option value="ANDAMAN & NICOBAR ISLANDS" <?php echo ($state === 'ANDAMAN & NICOBAR ISLANDS') ? 'selected' : ''; ?>>ANDAMAN & NICOBAR ISLANDS</option>
                                    <option value="ANDHRA PRADESH" <?php echo ($state === 'ANDHRA PRADESH') ? 'selected' : ''; ?>>ANDHRA PRADESH</option>
                                    <option value="ASSAM" <?php echo ($state === 'ASSAM') ? 'selected' : ''; ?>>ASSAM</option>
                                    <option value="BIHAR" <?php echo ($state === 'BIHAR') ? 'selected' : ''; ?>>BIHAR</option>
                                    <option value="GUJARAT" <?php echo ($state === 'GUJARAT') ? 'selected' : ''; ?>>GUJARAT</option>
                                    <option value="HARYANA" <?php echo ($state === 'HARYANA') ? 'selected' : ''; ?>>HARYANA</option>
                                    <option value="HIMACHAL PRADESH" <?php echo ($state === 'HIMACHAL PRADESH') ? 'selected' : ''; ?>>HIMACHAL PRADESH</option>
                                    <option value="JAMMU AND KASHMIR" <?php echo ($state === 'JAMMU AND KASHMIR') ? 'selected' : ''; ?>>JAMMU AND KASHMIR</option>
                                    <option value="KARNATAKA" <?php echo ($state === 'KARNATAKA') ? 'selected' : ''; ?>>KARNATAKA</option>
                                    <option value="KERALA" <?php echo ($state === 'KERALA') ? 'selected' : ''; ?>>KERALA</option>
                                    <option value="MADHYA PRADESH" <?php echo ($state === 'MADHYA PRADESH') ? 'selected' : ''; ?>>MADHYA PRADESH</option>
                                    <option value="MAHARASHTRA" <?php echo ($state === 'MAHARASHTRA') ? 'selected' : ''; ?>>MAHARASHTRA</option>
                                    <option value="TAMIL NADU" <?php echo ($state === 'TAMIL NADU') ? 'selected' : ''; ?>>TAMIL NADU</option>
                                    <option value="UTTAR PRADESH" <?php echo ($state === 'UTTAR PRADESH') ? 'selected' : ''; ?>>UTTAR PRADESH</option>
                                    <option value="WEST BENGAL" <?php echo ($state === 'WEST BENGAL') ? 'selected' : ''; ?>>WEST BENGAL</option>
                                </select>
                            </div>

                            <div class="agro-form-group">
                                <label class="agro-label" for="district"><i class="fas fa-location-dot" style="margin-right:6px; color:var(--color-primary);"></i>District</label>
                                <select name="district" id="district" class="agro-select" required>
                                    <option value="<?php echo htmlspecialchars($district); ?>" selected><?php echo htmlspecialchars($district ? $district : 'Select District'); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="agro-form-group">
                            <label class="agro-label" for="address"><i class="fas fa-home" style="margin-right:6px; color:var(--color-primary);"></i>Present Address</label>
                            <input type="text" id="address" name="address" class="agro-input" value="<?php echo htmlspecialchars($address); ?>" required />
                        </div>

                        <div class="agro-flex-between agro-mt-6" style="flex-wrap: wrap; gap: var(--space-4);">
                            <button type="submit" name="confirm" class="agro-btn agro-btn--primary agro-btn--lg">
                                <i class="fas fa-check-circle"></i> Save Profile Changes
                            </button>
                            <a href="ChangePassword.php" class="agro-btn agro-btn--secondary">
                                <i class="fas fa-key"></i> Change Password
                            </a>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="agro-empty">
                    <div class="agro-empty__icon">🔒</div>
                    <h3 class="agro-empty__title">Authentication Required</h3>
                    <p class="agro-empty__desc">Please sign in to edit your profile details.</p>
                    <a href="../auth/FarmerLogin.php" class="agro-btn agro-btn--primary agro-btn--lg">Farmer Login</a>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Shared Footer -->
    <?php agro_footer('farmer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
</body>
</html>

<?php
if (isset($_POST['confirm'])) {
    $phone = mysqli_real_escape_string($con, $_POST['phonenumber']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $district = mysqli_real_escape_string($con, $_POST['district']);
    $state = mysqli_real_escape_string($con, $_POST['statevalue']);
    $account = mysqli_real_escape_string($con, $_POST['bank']);

    $query = "update farmerregistration 
              set farmer_phone = '$phone', farmer_address = '$address',
              farmer_bank = '$account', farmer_state = '$state',
              farmer_district = '$district'
              where farmer_id 
              in (select farmer_id from farmerregistration 
              where farmer_phone='$sessphonenumber')";
    $run = mysqli_query($con, $query);
    
    $_SESSION['phonenumber'] = $phone;
    echo "<script>window.open('FarmerProfile.php','_self')</script>";
}
?>
