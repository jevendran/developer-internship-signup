$(function () {
  if (App.token()) {
    window.location.href = "profile.html";
    return;
  }

  $("#loginForm").on("submit", function (event) {
    event.preventDefault();
    App.hideAlert();

    const form = $(this);
    const payload = {
      email: $("#email").val().trim(),
      password: $("#password").val()
    };

    if (!payload.email || !payload.password) {
      App.showAlert("Enter your email and password.");
      return;
    }

    App.setLoading(form, true);
    App.post("login.php", payload)
      .done(function (response) {
        App.setToken(response.token);
        window.location.href = "profile.html";
      })
      .fail(function (xhr) {
        App.showAlert(xhr.responseJSON?.message || "Login failed.");
      })
      .always(function () {
        App.setLoading(form, false);
      });
  });
});
