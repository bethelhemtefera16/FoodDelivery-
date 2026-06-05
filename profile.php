<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - QuickBite</title>
    <link rel="stylesheet" href="stylec.css">
	<style>
body{
    background:
    linear-gradient(rgba(0,0,0,.45), rgba(0,0,0,.45)),
    url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=1974&auto=format&fit=crop');

    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    min-height: 100vh;
}
</style>
</head>

<body>

<header class="ue-header">

    <div class="ue-top">

        <span id="hamburger">☰</span>

        <h1>QuickBite</h1>

    </div>

</header>

<!-- SIDE MENU -->
<nav id="sideMenu">

    <a href="home.php">🏠 Home</a>

    <a href="menu.php">📋 Menu</a>

    <a href="cart.php">🛒 Cart</a>

    <a href="profile.php">👤 Profile</a>

    <a href="payment.php">💳 Payment</a>
	
    <a href="orders.php">💳 orders</a>

</nav>

<!-- OVERLAY -->
<div id="menuOverlay"></div>


    
    


<section class="profile-section">
    <div class="profile-info">
        <h2>Profile</h2>
        
        <div class="avatar-container">
            <img id="profilePhoto" src="https://via.placeholder.com/100" alt="Profile Photo" class="avatar">
            <input type="file" id="photoInput" accept="image/*" style="display:none">
            <button id="changePhotoBtn" class="btn">Change Photo</button>
        </div>

        <div class="edit-name">
            <input type="text" id="profileName" placeholder="Your Name">
            <button id="saveProfileBtn" class="btn">Save</button>
        </div>
        
        <p id="welcomeMsg"></p>
    </div>
</section>
<script>
/* ===== PROFILE PHOTO & NAME ===== */
const profilePhoto = document.getElementById("profilePhoto");
const photoInput = document.getElementById("photoInput");
const changePhotoBtn = document.getElementById("changePhotoBtn");
const profileName = document.getElementById("profileName");
const saveProfileBtn = document.getElementById("saveProfileBtn");

if (profilePhoto && photoInput && changePhotoBtn && profileName && saveProfileBtn) {
    
    // Load existing profile info
    const savedPhoto = localStorage.getItem("profilePhoto");
    const savedName = localStorage.getItem("quickBiteUser");
    if (savedPhoto) profilePhoto.src = savedPhoto;
    if (savedName) profileName.value = savedName;

    // Open file picker
    changePhotoBtn.addEventListener("click", () => photoInput.click());

    // Save selected photo
    photoInput.addEventListener("change", (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = () => {
                profilePhoto.src = reader.result; // show photo
                localStorage.setItem("profilePhoto", reader.result); // save photo
            };
            reader.readAsDataURL(file);
        }
    });

    // Save edited name
    saveProfileBtn.addEventListener("click", () => {
        const name = profileName.value.trim();
        if (name) {
            localStorage.setItem("quickBiteUser", name);
            alert("✅ Profile updated!");
            document.getElementById("welcomeMsg").textContent = `👋 Welcome, ${name}!`;
        }
    });
}




if (profileName) {
    const savedUser = JSON.parse(localStorage.getItem("quickBiteUser"));
    if (savedUser) {
        profileName.value = savedUser.firstName || "";
        document.getElementById("welcomeMsg").textContent = `👋 Welcome, ${savedUser.firstName}!`;

        // Create additional fields
        document.getElementById("profileName").value = savedUser.firstName;
        if (!document.getElementById("lastName")) {
            const lastNameInput = document.createElement("input");
            lastNameInput.id = "lastName";
            lastNameInput.placeholder = "Last Name";
            lastNameInput.value = savedUser.lastName || "";
            lastNameInput.style.marginTop = "10px";
            profileName.parentNode.appendChild(lastNameInput);

            const ageInput = document.createElement("input");
            ageInput.id = "age";
            ageInput.type = "number";
            ageInput.placeholder = "Age";
            ageInput.value = savedUser.age || "";
            ageInput.style.marginTop = "10px";
            profileName.parentNode.appendChild(ageInput);

            const phoneInput = document.createElement("input");
            phoneInput.id = "phone";
            phoneInput.type = "tel";
            phoneInput.placeholder = "Phone";
            phoneInput.value = savedUser.phone || "";
            phoneInput.style.marginTop = "10px";
            profileName.parentNode.appendChild(phoneInput);

            const genderInput = document.createElement("select");
            genderInput.id = "gender";
            genderInput.style.marginTop = "10px";
            genderInput.innerHTML = `<option value="">Select Gender</option>
                                     <option value="Male">Male</option>
                                     <option value="Female">Female</option>
                                     <option value="Other">Other</option>`;
            genderInput.value = savedUser.gender || "";
            profileName.parentNode.appendChild(genderInput);
        }
    }
}

// Save updated profile
saveProfileBtn.addEventListener("click", () => {
    const updatedUser = {
        ...JSON.parse(localStorage.getItem("quickBiteUser")),
        firstName: document.getElementById("profileName").value.trim(),
        lastName: document.getElementById("lastName").value.trim(),
        age: document.getElementById("age").value.trim(),
        phone: document.getElementById("phone").value.trim(),
        gender: document.getElementById("gender").value
    };
    localStorage.setItem("quickBiteUser", JSON.stringify(updatedUser));
    alert("✅ Profile updated!");
    document.getElementById("welcomeMsg").textContent = `👋 Welcome, ${updatedUser.firstName}!`;
});
</script>
<script>

/* =========================
   HAMBURGER MENU
========================= */

const hamburger = document.getElementById("hamburger");
const sideMenu = document.getElementById("sideMenu");
const overlay = document.getElementById("menuOverlay");

hamburger.addEventListener("click", function(){

    sideMenu.style.left = "0";
    overlay.style.display = "block";

});

overlay.addEventListener("click", function(){

    sideMenu.style.left = "-300px";
    overlay.style.display = "none";

});

/* =========================
   AUTO HIDE MESSAGE
========================= */

const message = document.querySelector(".cart-message");

if(message){

    setTimeout(function(){

        message.style.display = "none";

    }, 2000);

}

</script>
<script src="script.js"></script>


</body>
</html>
