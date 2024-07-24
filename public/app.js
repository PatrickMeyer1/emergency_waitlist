document.addEventListener('DOMContentLoaded', () => {
    const adminSignInButton = document.getElementById('admin-sign-in');
    const userSignInButton = document.getElementById('user-sign-in');
    const adminSignInForm = document.getElementById('admin-sign-in-form');
    const userSignInForm = document.getElementById('user-sign-in-form');
    const addPatientForm = document.getElementById('add-patient-form');
    const checkWaitTimeForm = document.getElementById('check-wait-time-form');
    const waitTimeResult = document.getElementById('wait-time-result');

    adminSignInButton.addEventListener('click', () => {
        adminSignInForm.style.display = 'flex';
        userSignInForm.style.display = 'none';
    });

    userSignInButton.addEventListener('click', () => {
        userSignInForm.style.display = 'flex';
        adminSignInForm.style.display = 'none';
    });

    adminSignInForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const admin_username = document.getElementById('admin-username').value.trim();
        const admin_password = document.getElementById('admin-password').value.trim();
        

        if (admin_username && admin_password) {
            try {
                const response = await fetch('admin_signin.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ admin_username, admin_password })
                });

                if (response.ok) {
                    console.log(response);
                    const result = await response.json();
                    if (result.status === 'success') {
                        document.getElementById('sign-in-section').classList.add('hidden');
                        document.getElementById('admin-section').classList.remove('hidden');
                        fetchPatientList();
                    } else {
                        console.log(result.status);
                        alert(result.message);
                    }
                } else {
                    throw new Error('Failed to sign in');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        }
    });

    userSignInForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const name = document.getElementById('user-name').value.trim();
        const code = document.getElementById('user-code').value.trim();

        if (name && code) {
            try {
                const response = await fetch('check_wait_time.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ name, code })
                });

                if (response.ok) {
                    const result = await response.json();
                    waitTimeResult.textContent = `Approximate Wait Time: ${result.waitTime}`;
                    waitTimeResult.style.display = 'block';
                    document.getElementById('sign-in-section').classList.add('hidden');
                    document.getElementById('user-section').classList.remove('hidden');
                } else {
                    throw new Error('Failed to fetch wait time');
                }
            } catch (error) {
                console.error('Error:', error);
                waitTimeResult.textContent = 'An error occurred. Please try again.';
                waitTimeResult.style.display = 'block';
            }
        }
    });

    addPatientForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const name = document.getElementById('patient-name').value.trim();
        const code = document.getElementById('patient-code').value.trim();
        const severity = document.getElementById('patient-severity').value;

        try {
            const response = await fetch('add_patient.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ name, code, severity })
            });

            if (response.ok) {
                alert('Patient added successfully!');
                fetchPatientList();
            } else {
                throw new Error('Failed to add patient');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    });

    const fetchPatientList = async () => {
        try {
            const response = await fetch('fetch_patients.php');
            if (response.ok) {
                const patients = await response.json();
                const patientListTableBody = document.querySelector('#patient-list tbody');
                patientListTableBody.innerHTML = '';
                patients.forEach(patient => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${patient.name}</td>
                        <td>${patient.severity}</td>
                        <td>${patient.time_in_queue}</td>
                        <td>${patient.status}</td>
                    `;
                    patientListTableBody.appendChild(row);
                });
            } else {
                throw new Error('Failed to fetch patient list');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    };

    fetchPatientList();
});
