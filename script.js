// Fetch cases from the backend
function fetchCases() {
    fetch("http://localhost/cybercrime_project/cases/get_cases.php")
    .then(response => response.json())
    .then(data => {
        let table = document.getElementById("casesTable");
        table.innerHTML = "<tr><th>Case ID</th><th>Title</th><th>Status</th><th>Actions</th></tr>";
        
        data.forEach(caseItem => {
            let row = table.insertRow();
            row.insertCell(0).innerText = caseItem.case_id;
            row.insertCell(1).innerText = caseItem.title;
            row.insertCell(2).innerText = caseItem.status;
            
            let actionsCell = row.insertCell(3);
            actionsCell.innerHTML = `
                <button onclick="editCase(${caseItem.case_id})">Edit</button>
                <button onclick="deleteCase(${caseItem.case_id})">Delete</button>
            `;
        });
    })
    .catch(error => console.error("Error fetching cases:", error));
}

// Add a new case
document.getElementById("caseForm").addEventListener("submit", function (event) {
    event.preventDefault();

    let title = document.getElementById("caseTitle").value;
    let status = document.getElementById("caseStatus").value;

    fetch("http://localhost/cybercrime_project/cases/add_case.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `title=${title}&status=${status}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            fetchCases();
        } else {
            alert("Error adding case.");
        }
    });
});

// Edit a case
function editCase(caseId) {
    let newTitle = prompt("Enter new case title:");
    let newStatus = prompt("Enter new case status:");

    if (newTitle && newStatus) {
        fetch("http://localhost/cybercrime_project/cases/edit_case.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `case_id=${caseId}&title=${newTitle}&status=${newStatus}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchCases();
            } else {
                alert("Error updating case.");
            }
        });
    }
}

// Delete a case
function deleteCase(caseId) {
    if (confirm("Are you sure you want to delete this case?")) {
        fetch("http://localhost/cybercrime_project/cases/delete_case.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `case_id=${caseId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchCases();
            } else {
                alert("Error deleting case.");
            }
        });
    }
}

// Fetch all users from the backend
function fetchUsers() {
    fetch("http://localhost/cybercrime_project/admin/get_users.php")
    .then(response => response.json())
    .then(data => {
        let table = document.getElementById("usersTable");
        table.innerHTML = "<tr><th>User ID</th><th>Username</th><th>Role</th><th>Actions</th></tr>";
        
        data.forEach(user => {
            let row = table.insertRow();
            row.insertCell(0).innerText = user.user_id;
            row.insertCell(1).innerText = user.username;
            row.insertCell(2).innerText = user.role;
            
            let actionsCell = row.insertCell(3);
            actionsCell.innerHTML = `
                <button onclick="editUser(${user.user_id})">Edit</button>
                <button onclick="deleteUser(${user.user_id})">Delete</button>
            `;
        });
    })
    .catch(error => console.error("Error fetching users:", error));
}

// Add a new user
document.getElementById("userForm").addEventListener("submit", function (event) {
    event.preventDefault();

    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;
    let role = document.getElementById("role").value;

    fetch("http://localhost/cybercrime_project/admin/add_user.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `username=${username}&password=${password}&role=${role}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            fetchUsers();
        } else {
            alert("Error adding user.");
        }
    });
});

// Edit a user
function editUser(userId) {
    let newUsername = prompt("Enter new username:");
    let newRole = prompt("Enter new role (admin/investigator):");

    if (newUsername && newRole) {
        fetch("http://localhost/cybercrime_project/admin/edit_user.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `user_id=${userId}&username=${newUsername}&role=${newRole}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchUsers();
            } else {
                alert("Error updating user.");
            }
        });
    }
}

// Delete a user
function deleteUser(userId) {
    if (confirm("Are you sure you want to delete this user?")) {
        fetch("http://localhost/cybercrime_project/admin/delete_user.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `user_id=${userId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchUsers();
            } else {
                alert("Error deleting user.");
            }
        });
    }
}

// Logout function
function logout() {
    fetch("http://localhost/cybercrime_project/logout.php")
    .then(() => {
        window.location.href = "index.html";
    });
}

// Load cases and users on page load
window.onload = function() {
    fetchCases();
    fetchUsers();
};
