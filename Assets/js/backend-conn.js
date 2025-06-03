document.addEventListener("DOMContentLoaded", () => {
    setupForm("#registerForm", "register");
    setupForm("#loginForm", "login");
});

const API_BASE_URL = "";

async function setupForm(formSelector, action) {
    const form = document.querySelector(formSelector);
    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = getFormData(action);
        if (!formData) return;

        try {
            const response = await fetch(action === 'register' ? 'register-user-back.php' : 'Login-user-back.php', {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(formData),
            });

            const result = await response.json();
            console.log(`${action} response:`, result);

            if (!response.ok) {
                showNotification(result.message || `Error: ${response.status}`, "error");
                return;
            }
            handleSuccess(action, result);
        } catch (err) {
            console.error(`${action} error:`, err);
            showNotification("A server error occurred.", "error");
        }
    });
}

function getFormData(action) {
    if (action === "register") {
        const fullName = document.getElementById("username")?.value.trim();
        const email = document.getElementById("email")?.value.trim();
        const phoneNumber = document.getElementById("Phone")?.value.trim();
        const password = document.getElementById("password")?.value;
        const confirmPassword = document.getElementById("confirm_password")?.value;

        if (!fullName || !email || !phoneNumber || !password || !confirmPassword) {
            showNotification("All fields are required.", "error");
            return null;
        }

        return {
            fullName,
            email,
            phoneNumber,
            password,
            confirmPassword
        };
    } else if (action === "login") {
        const email = document.getElementById("login-email")?.value.trim();
        const password = document.getElementById("login-password")?.value;

        if (!email || !password) {
            showNotification("Email and password are required.", "error");
            return null;
        }

        return {
            email,
            password
        };
    }
    return null;
}

function handleSuccess(action, result) {
    if (action === "register") {
        showNotification("Registration successful!", "success");
        setTimeout(() => {
            window.location.href = "../index.php";
        }, 2000);
    } else if (action === "login") {
        showNotification(`Welcome back, ${result.user.fullName}!`, "success");
        setTimeout(() => {
            window.location.href = "./public/HomePage.html";
        }, 1500);
    }
}