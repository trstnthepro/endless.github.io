<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Endless Art Gallery - Profile</title>
    <link rel="stylesheet" href="endless.css">
</head>
<body>
<header class="header">
    <button class="menu-button">
        <span class="hamburger-icon"></span>
    </button>

    <a href="endless.php" class="logo">

        <a href="profile.php" class="profile-icon">
        </a>
</header>
<h2 class="profile-header">Profile</h2>
<div class="profile-container">
    <div id="userProfile">
        <div class="profile-column center">
            <a>
                <img id="profileImage" src="ui_images/profile_image.png" alt="User Profile Picture">
            </a>
            <form class="profile-form" id="upload">
                <input type="file" id="imageUpload" accept="image/*" required>
            </form>
        </div>

        <div class="profile-column">
            <h2>Profile</h2>
            <span id="displayName"><h1 id="userFullName">firstname + lastname</h1></span>
            <p>Bio: <span id="userBio">This is a sample bio.</span></p>

            <button id="editProfileButton" class="profile-edit-button">Edit Profile</button>

            <form id="profileEditForm" class="profile-form" style="display: none;">
                <label>First Name</label><br>
                <input name="firstname" id="firstNameInput" type="text"><br>

                <label>Last Name</label><br>
                <input name="lastname" id="lastNameInput" type="text"><br>

                <label>About Me</label><br>
                <textarea name="bio" id="bioInput"></textarea><br>

                <input type="submit" value="Save Changes" class="profile-edit-button">
            </form>
        </div>
    </div>
</div>

<hr class="profile-divider">

<div id="favorites">
    <div id="favtitle"></div>
    <div class="favorites-title">Favorites</div>
    <br>
    Van Gogh [insert mini pic of favorited image that hyperlinks to image?]
</div>

<script>
    // Profile image upload handler
    const imageUploadInput = document.getElementById('imageUpload');
    const profileImage = document.getElementById('profileImage');

    imageUploadInput.addEventListener('change', function () {
        const file = imageUploadInput.files[0];
        if (file) {
            const confirmChange = confirm("Are you sure you want to change your profile picture?");
            if (confirmChange) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    profileImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                imageUploadInput.value = '';
            }
        }
    });

    // Profile edit form handler
    const editProfileButton = document.getElementById('editProfileButton');
    const profileEditForm = document.getElementById('profileEditForm');
    const userFullName = document.getElementById('userFullName');
    const userBio = document.getElementById('userBio');
    const favtitle = document.getElementById('favtitle');

    editProfileButton.addEventListener('click', function () {
        profileEditForm.style.display = profileEditForm.style.display === 'none' ? 'block' : 'none';
    });

    profileEditForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const firstName = document.getElementById('firstNameInput').value;
        const lastName = document.getElementById('lastNameInput').value;
        const bio = document.getElementById('bioInput').value;

        userFullName.textContent = `${firstName} ${lastName}`;
        userBio.textContent = bio;
        favtitle.textContent = `${firstName} ${lastName}`;

        profileEditForm.style.display = 'none';

        document.getElementById('firstNameInput').value = '';
        document.getElementById('lastNameInput').value = '';
        document.getElementById('bioInput').value = '';
    });
</script>
</body>
</html>