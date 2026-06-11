$(function () {
  App.requireLogin();

  function fillProfile(profile) {
    $("#profileName").text(`${profile.first_name} ${profile.last_name}`);
    $("#profileEmail").text(profile.email);
    $("#age").val(profile.age || "");
    $("#dob").val(profile.dob || "");
    $("#contact").val(profile.contact || "");
    $("#city").val(profile.city || "");
    $("#bio").val(profile.bio || "");
  }

  function loadProfile() {
    App.authPost("profile.php")
      .done(function (response) {
        fillProfile(response.profile);
      })
      .fail(function () {
        App.clearToken();
        window.location.href = "login.html";
      });
  }

  $("#profileForm").on("submit", function (event) {
    event.preventDefault();
    App.hideAlert();

    const form = $(this);
    const payload = {
      age: $("#age").val(),
      dob: $("#dob").val(),
      contact: $("#contact").val().trim(),
      city: $("#city").val().trim(),
      bio: $("#bio").val().trim()
    };

    if (!payload.age || !payload.dob || !payload.contact) {
      App.showAlert("Age, date of birth, and contact are required.");
      return;
    }

    App.setLoading(form, true);
    App.authPost("update-profile.php", payload)
      .done(function (response) {
        App.showAlert(response.message || "Profile updated.", "success");
        fillProfile(response.profile);
      })
      .fail(function (xhr) {
        App.showAlert(xhr.responseJSON?.message || "Could not update profile.");
      })
      .always(function () {
        App.setLoading(form, false);
      });
  });

  $("#logoutBtn").on("click", function () {
    App.authPost("logout.php").always(function () {
      App.clearToken();
      window.location.href = "login.html";
    });
  });

  loadProfile();
});
