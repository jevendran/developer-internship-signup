const App = {
  apiBase: "api/",
  tokenKey: "internship_auth_token",

  token() {
    return localStorage.getItem(this.tokenKey);
  },

  setToken(token) {
    localStorage.setItem(this.tokenKey, token);
  },

  clearToken() {
    localStorage.removeItem(this.tokenKey);
  },

  showAlert(message, type = "danger") {
    $("#alertBox")
      .removeClass("d-none alert-success alert-danger alert-warning")
      .addClass(`alert-${type}`)
      .text(message);
  },

  hideAlert() {
    $("#alertBox").addClass("d-none").text("");
  },

  setLoading(form, isLoading) {
    const button = form.find("button[type='submit']");
    button.prop("disabled", isLoading);
    button.find(".btn-label").toggleClass("d-none", isLoading);
    button.find(".spinner-border").toggleClass("d-none", !isLoading);
  },

  post(endpoint, payload) {
    return $.ajax({
      url: `${this.apiBase}${endpoint}`,
      method: "POST",
      data: JSON.stringify(payload),
      contentType: "application/json",
      dataType: "json"
    });
  },

  authPost(endpoint, payload = {}) {
    return this.post(endpoint, {
      ...payload,
      token: this.token()
    });
  },

  requireLogin() {
    if (!this.token()) {
      window.location.href = "login.html";
    }
  }
};
