 <?php

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    include("../Includes/db.php");

    function getUsername()
    {
        if (isset($_SESSION['phonenumber'])) {
            $phonenumber = $_SESSION['phonenumber'];
            global $con;

            $query = "select * from buyerregistration where buyer_phone = '$phonenumber'";
            $run_query = mysqli_query($con, $query);
            if ($run_query) {
                while ($row_cat = mysqli_fetch_array($run_query)) {
                    $buyer_name = $row_cat['buyer_name'];
                    $buyer_name = 'Hello ,' . $buyer_name;
                }

                // echo @"<label>$buyer_name</label>";
                echo @"<div class='text-success  logins mx-1 ml-5  '>$buyer_name</div>";
            }
        } else {
            echo "<a href = '../auth/BuyerLogin.php'><div class='text-success logins mx-5'>Login</div></a>";
            // echo "<label><a href = '../auth/BuyerLogin.php' style = 'color:white' >Login/Sign up</a></label>";
        }
    }


    function getFarmerUsername()
    {
        if (isset($_SESSION['phonenumber'])) {
            $phonenumber = $_SESSION['phonenumber'];
            global $con;

            $query = "select * from farmerregistration where farmer_phone = '$phonenumber'";
            $run_query = mysqli_query($con, $query);
            if ($run_query) {
                while ($row_cat = mysqli_fetch_array($run_query)) {
                    $buyer_name = $row_cat['farmer_name'];
                    $buyer_name = "Hello ," . $buyer_name;
                    echo "<label style = 'color:white; padding-top:7px;'>$buyer_name</label>";
                }
            }
        } else {
            echo "<label><a href = '../auth/FarmerLogin.php' style = 'color:white; padding-top:20px;' >Login/Sign up</a></label>";
        }
    }

    function CheckoutIdentify()
    {
        if (isset($_SESSION['phonenumber'])) {
            echo "<script>window.open('CartPage.php','_self')</script>";
        } else {
            echo "<script>window.open('../auth/BuyerLogin.php','_self')</script>";
        }
    }


    function getCrops()
    {

        global $con;

        $query = "select * from products where product_cat = 1 order by RAND() LIMIT 0,10";

        $run_query = mysqli_query($con, $query);

        while ($row_cat = mysqli_fetch_array($run_query)) {
            $product_type = $row_cat['product_type'];
            echo "<a class='dropdown-item' href='../BuyerPortal2/Categories.php?type=$product_type'>$product_type</a>";
        }
    }

    function getFruits()
    {

        global $con;

        $query = "select * from products where product_cat = 3 order by RAND() LIMIT 0,10";

        $run_query = mysqli_query($con, $query);

        while ($row_cat = mysqli_fetch_array($run_query)) {
            $product_type = $row_cat['product_type'];
            // echo "<li class='options' role='presentation'><a role='menuitem' tabindex='-1' href='../BuyerPortal/Categories.php?type=$product_type'> 
            //         <label class='crop_items'>$product_type</label></a></li>";

            echo "<a class='dropdown-item' href='../BuyerPortal2/Categories.php?type=$product_type'>$product_type</a>";
        }
    }

    function getVegetables()
    {

        global $con;

        $query = "select * from products where product_cat = 2 order by RAND() LIMIT 0,10";

        $run_query = mysqli_query($con, $query);

        while ($row_cat = mysqli_fetch_array($run_query)) {
            $product_type = $row_cat['product_type'];
            echo "<a class='dropdown-item' href='../BuyerPortal2/Categories.php?type=$product_type'>$product_type</a>";
        }
    }


    function getProducts()
    {
        global $con;
        $query = "select * from products order by RAND() LIMIT 0,6";
        $run_query = mysqli_query($con, $query);
        while ($rows = mysqli_fetch_array($run_query)) {
            $product_id = $rows['product_id'];
            $product_title = $rows['product_title'];
            $product_image = $rows['product_image'];
            $product_price = $rows['product_price'];
            $product_delivery = $rows['product_delivery'];
            $farmer_fk = $rows['farmer_fk'];

            $name = "Local Farmer";
            $farmer_name_query = "select farmer_name from farmerregistration where farmer_id = '$farmer_fk'";
            $running_query_name = mysqli_query($con, $farmer_name_query);
            if ($running_query_name && $farmer_row = mysqli_fetch_array($running_query_name)) {
                $name = $farmer_row['farmer_name'];
            }

            $delivery_badge = ($product_delivery == "yes") ? "Delivery Available" : "Pickup Only";
            $badge_class = ($product_delivery == "yes") ? "agro-badge--green" : "agro-badge--amber";
            $image_src = !empty($product_image) ? "../Admin/product_images/$product_image" : "../Images/Website/noimage.jpg";

            echo "
            <div class='agro-product-card'>
                <div class='agro-product-card__image-wrap'>
                    <span class='agro-badge $badge_class agro-product-card__badge'>$delivery_badge</span>
                    <a href='../BuyerPortal2/ProductDetails.php?id=$product_id'>
                        <img class='agro-product-card__image' src='$image_src' alt='" . htmlspecialchars($product_title) . "' onerror=\"this.src='../Images/Website/noimage.jpg'\">
                    </a>
                </div>
                <div class='agro-product-card__body'>
                    <div class='agro-product-card__category'>👨‍🌾 " . htmlspecialchars($name) . "</div>
                    <h3 class='agro-product-card__name'>" . htmlspecialchars($product_title) . "</h3>
                    <div class='agro-product-card__footer agro-mt-4'>
                        <div class='agro-product-card__price'>
                            ₹" . htmlspecialchars($product_price) . " <span class='agro-product-card__price-unit'>/ kg</span>
                        </div>
                        <a href='../BuyerPortal2/bhome.php?add_cart=$product_id' class='agro-btn agro-btn--secondary agro-btn--sm'>
                            <i class='fas fa-cart-plus'></i> Add to Cart
                        </a>
                    </div>
                </div>
            </div>";
        }
    }

    function getVegetablesHomepage()
    {
        global $con;
        $query = "select * from products where product_cat = 2 and not (product_image = '') order by RAND() LIMIT 0,4";
        $run_query = mysqli_query($con, $query);
        while ($rows = mysqli_fetch_array($run_query)) {
            $product_id = $rows['product_id'];
            $product_title = $rows['product_title'];
            $product_image = $rows['product_image'];
            $product_price = $rows['product_price'];
            $product_type = $rows['product_type'];
            $image_src = "../Admin/product_images/$product_image";

            echo "
            <div class='agro-product-card'>
                <div class='agro-product-card__image-wrap'>
                    <span class='agro-badge agro-badge--green agro-product-card__badge'>🥦 Vegetable</span>
                    <a href='../BuyerPortal2/Categories.php?type=$product_type'>
                        <img class='agro-product-card__image' src='$image_src' alt='" . htmlspecialchars($product_title) . "' onerror=\"this.src='../Images/Website/noimage.jpg'\">
                    </a>
                </div>
                <div class='agro-product-card__body'>
                    <div class='agro-product-card__category'>" . htmlspecialchars($product_type) . "</div>
                    <h3 class='agro-product-card__name'>" . htmlspecialchars($product_title) . "</h3>
                    <div class='agro-product-card__footer agro-mt-4'>
                        <div class='agro-product-card__price'>₹" . htmlspecialchars($product_price) . " <span class='agro-product-card__price-unit'>/ kg</span></div>
                        <a href='../BuyerPortal2/Categories.php?type=$product_type' class='agro-btn agro-btn--outline agro-btn--sm'>
                            View All <i class='fas fa-arrow-right'></i>
                        </a>
                    </div>
                </div>
            </div>";
        }
    }

    function getFruitsHomepage()
    {
        global $con;
        $query = "select * from products where product_cat = 3 and not (product_image = '') order by RAND() LIMIT 0,4";
        $run_query = mysqli_query($con, $query);
        while ($rows = mysqli_fetch_array($run_query)) {
            $product_id = $rows['product_id'];
            $product_title = $rows['product_title'];
            $product_image = $rows['product_image'];
            $product_price = $rows['product_price'];
            $product_type = $rows['product_type'];
            $image_src = "../Admin/product_images/$product_image";

            echo "
            <div class='agro-product-card'>
                <div class='agro-product-card__image-wrap'>
                    <span class='agro-badge agro-badge--amber agro-product-card__badge'>🍎 Fruit</span>
                    <a href='../BuyerPortal2/Categories.php?type=$product_type'>
                        <img class='agro-product-card__image' src='$image_src' alt='" . htmlspecialchars($product_title) . "' onerror=\"this.src='../Images/Website/noimage.jpg'\">
                    </a>
                </div>
                <div class='agro-product-card__body'>
                    <div class='agro-product-card__category'>" . htmlspecialchars($product_type) . "</div>
                    <h3 class='agro-product-card__name'>" . htmlspecialchars($product_title) . "</h3>
                    <div class='agro-product-card__footer agro-mt-4'>
                        <div class='agro-product-card__price'>₹" . htmlspecialchars($product_price) . " <span class='agro-product-card__price-unit'>/ kg</span></div>
                        <a href='../BuyerPortal2/Categories.php?type=$product_type' class='agro-btn agro-btn--outline agro-btn--sm'>
                            View All <i class='fas fa-arrow-right'></i>
                        </a>
                    </div>
                </div>
            </div>";
        }
    }


    // Checkout System Functions
    function cart()
    {
        if (isset($_SESSION['phonenumber'])) {
            if (isset($_GET['add_cart'])) {

                global $con;
                if (isset($_POST['quantity'])) {
                    $qty = $_POST['quantity'];
                } else {
                    $qty = 1;
                }
                $sess_phone_number = $_SESSION['phonenumber'];
                $product_id = $_GET['add_cart'];

                $check_pro = "select * from cart where phonenumber = $sess_phone_number and product_id='$product_id' ";

                $run_check = mysqli_query($con, $check_pro);

                if (mysqli_num_rows($run_check) > 0) {
                    echo "";
                } else {
                    $insert_pro = "insert into cart (product_id,phonenumber) values ('$product_id','$sess_phone_number')";
                    $run_insert_pro = mysqli_query($con, $insert_pro);
                }

                echo "<script>window.open('bhome.php','_self')</script>";
            }
        } else {
            // echo "<script>alert('Please Login First! ');</script>";
        }
    }

    //function which is link with FarmerHomePage
    function getFarmerProducts()
    {
        include("../Includes/db.php");
        global $con;
        if (!isset($_SESSION['phonenumber'])) return;
        $sess_phone_number = mysqli_real_escape_string($con, $_SESSION['phonenumber']);
        $query = "select * from products where farmer_fk in (select farmer_id from farmerregistration where farmer_phone='$sess_phone_number') order by product_id desc";
        $run_query = mysqli_query($con, $query);
        $count = $run_query ? mysqli_num_rows($run_query) : 0;
        if ($count > 0) {
            while ($row = mysqli_fetch_assoc($run_query)) {
                $product_title = $row['product_title'];
                $image = $row['product_image'];
                $price = $row['product_price'];
                $id = $row['product_id'];
                $stock = $row['product_stock'] ?? 0;
                $type = $row['product_type'] ?? 'Produce';
                $image_src = !empty($image) ? "../Admin/product_images/$image" : "../Images/Website/noimage.jpg";

                echo "
                <div class='agro-product-card'>
                    <div class='agro-product-card__image-wrap'>
                        <span class='agro-badge agro-badge--green agro-product-card__badge'>$stock kg stock</span>
                        <a href='../FarmerPortal/FarmerProductDetails.php?id=$id'>
                            <img class='agro-product-card__image' src='$image_src' alt='" . htmlspecialchars($product_title) . "' onerror=\"this.src='../Images/Website/noimage.jpg'\">
                        </a>
                    </div>
                    <div class='agro-product-card__body'>
                        <div class='agro-product-card__category'>" . htmlspecialchars($type) . "</div>
                        <h3 class='agro-product-card__name'>" . htmlspecialchars($product_title) . "</h3>
                        <div class='agro-product-card__footer agro-mt-4'>
                            <div class='agro-product-card__price'>₹" . htmlspecialchars($price) . " <span class='agro-product-card__price-unit'>/ kg</span></div>
                            <div class='agro-flex' style='gap: var(--space-2);'>
                                <a href='../FarmerPortal/EditProduct.php?id=$id' class='agro-btn agro-btn--outline agro-btn--sm' title='Edit Product'>
                                    <i class='fas fa-edit'></i> Edit
                                </a>
                                <a href='../FarmerPortal/FarmerProductDetails.php?id=$id' class='agro-btn agro-btn--primary agro-btn--sm'>
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>";
            }
        } else {
            echo "
            <div class='agro-empty' style='grid-column: 1 / -1;'>
                <div class='agro-empty__icon'>🌾</div>
                <h3 class='agro-empty__title'>No Products Uploaded Yet</h3>
                <p class='agro-empty__desc'>You haven't listed any farm produce on the marketplace. Add your first crop listing now!</p>
                <a href='InsertProduct.php' class='agro-btn agro-btn--primary agro-btn--lg'><i class='fas fa-plus-circle'></i> Add New Product</a>
            </div>";
        }
    }
    //function which is linked with BuyerProductDetails
    function getBuyerProductDetails()
    {
        include("../Includes/db.php");
        global $con;
        // $sess_phone_number = $_SESSION['phonenumber'];
        if (isset($_GET['id'])) {
            $prod_id = $_GET['id'];
            $query = "select * from products where product_id=" . $prod_id;
            $run_query = mysqli_query($con, $query);
            $resultCheck = mysqli_num_rows($run_query);
            if ($resultCheck > 0) {
                while ($rows = mysqli_fetch_array($run_query)) {
                    $product_title = $rows['product_title'];
                    $product_image = $rows['product_image'];
                    $product_type = $rows['product_type'];
                    $product_stock = $rows['product_stock'];
                    $product_description = $rows['product_desc'];
                    $product_price = $rows['product_price'];
                    $product_delivery = $rows['product_delivery'];
                    $product_cat = $rows['product_cat'];
                    echo "<div>
                        <img src='../Admin/product_images/$product_image' height='250px' width='300px' ><br>"
                        . " product title  :  " . $product_title . "<br>"
                        . " product type  :  " . $product_type . "<br>"
                        . " product stock  :  " . $product_stock . "<br>"
                        . " product Description  :  " . $product_description . "<br>"
                        . " product price  :  " . $product_price . "<br>"
                        . " product Delivery  :  " . $product_delivery . "<br>"
                        . " product category  :  " . $product_cat . "<br>"
                        . "<button href=''>ADD TO CART</button>"
                        . "</div>";

                    if (isset($_SESSION['phonenumber'])) {
                        $query = "select * from products where product_id=" . $prod_id;
                        $run = mysqli_query($con, $query);
                        while ($row = mysqli_fetch_array($run)) {
                            $farmerid = $row['farmer_fk'];
                        }

                        $query = "select * from farmerregistration where farmer_id = $farmerid";
                        $run = mysqli_query($con, $query);
                        while ($row = mysqli_fetch_array($run)) {
                            $farmer_name = $row['farmer_name'];
                            $farmer_phone = $row['farmer_phone'];
                            $farmer_address = $row['farmer_address'];
                        }
                        echo "farmer Name : " . $farmer_name . "<br>farmer Phone Number : " . $farmer_phone . "<br> Farmer Address" . $farmer_address;
                    }
                }
            }
        }
    }


    function totalItems()
    {
        global $con;
        if (isset($_SESSION['phonenumber'])) {
            $sess_phone_number = $_SESSION['phonenumber'];

            $get_items = "select * from cart where phonenumber = '$sess_phone_number'";
            $run_items =  mysqli_query($con, $get_items);
            $count_items =  mysqli_num_rows($run_items);
            return $count_items;
        } else {
            echo 0;
        }
    }


    function emptyCart()
    {
        global $con;
        $sess_phone_number = $_SESSION['phonenumber'];

        $get_items = "Delete from cart where phonenumber = '$sess_phone_number'";
        $run_items =  mysqli_query($con, $get_items);
        $count_items =  mysqli_num_rows($run_items);
    }



    ?>

