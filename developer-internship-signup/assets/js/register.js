$(function () {
  $("#registerForm").on("submit", function (event) {
    event.preventDefault();
    App.hideAlert();

    const form = $(this);
    const payload = {
      first_name: $("#firstName").val().trim(),
      last_name: $("#lastName").val().trim(),
      email: $("#email").val().trim(),
      password: $("#password").val(),
      confirm_password: $("#confirmPassword").val()
    };

    if (!payload.first_name || !payload.last_name || !payload.email || !payload.password) {
      App.showAlert("Please fill all required fields.");
      return;
    }

    if (payload.password.length < 8) {
      App.showAlert("Password must be at least 8 characters.");
      return;
    }

    if (payload.password !== payload.confirm_password) {
      App.showAlert("Passwords do not match.");
      return;
    }

    App.setLoading(form, true);
    App.post("register.php", payload)
      .done(function (response) {
        App.showAlert(response.message || "Registration successful. Please login.", "success");
        form[0].reset();
        setTimeout(function () {
          window.location.href = "login.html";
        }, 900);
      })
      .fail(function (xhr) {
        App.showAlert(xhr.responseJSON?.message || "Registration failed.");
      })
      .always(function () {
        App.setLoading(form, false);
      });
  });
});
