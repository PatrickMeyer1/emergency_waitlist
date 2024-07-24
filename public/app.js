document.addEventListener('DOMContentLoaded', () => {
    const adminSignInButton = document.getElementById('admin-sign-in');
    const userSignInButton = document.getElementById('user-sign-in');
    const adminSignInForm = document.getElementById('admin-sign-in-form');
    const userSignInForm = document.getElementById('user-sign-in-form');
    const addPatientForm = document.getElementById('add-patient-form');
    const checkWaitTimeForm = document.getElementById('check-wait-time-form');
    const waitTimeResult = document.getElementById('wait-time-result');
    const statusFilter = document.getElementById('status-filter');

    statusFilter.addEventListener('change', () => {
        const selectedFilter = statusFilter.value;
        fetchPatientList(selectedFilter);
    });

    adminSignInButton.addEventListener('click', () => {
        adminSignInForm.style.display = 'flex';
        userSignInForm.style.display = 'none';
    });

    userSignInButton.addEventListener('click', () => {
        userSignInForm.style.display = 'flex';
        adminSignInForm.style.display = 'none';
    });

    document.getElementById('admin-sign-in-form').addEventListener('submit', async (e) => {
        e.preventDefault();
    
        const admin_username = document.getElementById('admin-username').value.trim();
        const admin_password = document.getElementById('admin-password').value.trim();
    
        if (admin_username && admin_password) {
            try {
                $.ajax({
                    url: 'admin_signin.php',
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify({ admin_username, admin_password, action: "adminSignIn" }),
                    success: function(response) {
                        if (response.status === 'success') {
                            document.getElementById('sign-in-section').classList.add('hidden');
                            document.getElementById('admin-section').classList.remove('hidden');
                            fetchPatientList();
                        } else {
                            alert(response.message || 'An error occurred. Please try again.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error during sign-in:', error);
                        alert('An error occurred. Please try again.');
                    }
                });
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        }
    });

    userSignInForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        console.log("hi")

        const name = document.getElementById('user-name').value.trim();
        const code = document.getElementById('user-code').value.trim();

        if (name && code) {
            try {
                $.ajax({
                    url: 'wait_time.php',
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify({ name, code, action: 'checkWaitTime' }),
                    success: function(response) {
                        if (response.waitTime) {
                            waitTimeResult.textContent = `Approximate Wait Time: ${response.waitTime}`;
                            waitTimeResult.style.display = 'block';
                            document.getElementById('sign-in-section').classList.add('hidden');
                            document.getElementById('user-section').classList.remove('hidden');
                        } else {
                            waitTimeResult.textContent = response.waitTime || 'An error occurred. Please try again.';
                            waitTimeResult.style.display = 'block';
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error check wait time:', error);
                        waitTimeResult.textContent = 'An error occurred. Please try again.';
                        waitTimeResult.style.display = 'block';
                    }
                });
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
            const response = await fetch('create_patient.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ name, code, severity , action: 'addPatient' })
            });

            if (response.ok) {
                alert('Patient added successfully!');
                addPatientForm.reset(); 
            } else {
                throw new Error('Failed to add patient');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    });


    const fetchPatientList = (filter = 'all') => {
        $.ajax({
            url: 'fetch_patients.php',
            type: 'GET',
            dataType: 'json',
            data: { filter: filter },
            success: function(response) {
                const patientListTableBody = document.querySelector('#patient-list tbody');
                patientListTableBody.innerHTML = '';
                
                if (response.patients && response.patients.length > 0) {
                    response.patients.forEach(patient => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${patient.name}</td>
                            <td>${patient.severity}</td>
                            <td>${patient.status === 'treated' ? '' : patient.time_in_queue}</td>
                            <td>${patient.status}</td>
                            <td>${patient.status === 'waiting' ? '<button class="treated-btn" data-id="' + patient.id + '">Treated</button>' : ''}</td>
                        `;
                        patientListTableBody.appendChild(row);
                    });
                } else {
                    const row = document.createElement('tr');
                    row.innerHTML = '<td colspan="5">No patients found</td>';
                    patientListTableBody.appendChild(row);
                }

                document.querySelectorAll('.treated-btn').forEach(button => {
                    button.addEventListener('click', () => {
                        const patientId = button.getAttribute('data-id');
                        $.ajax({
                            url: 'update_status.php',
                            type: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify({ id: patientId, status: 'treated' }),
                            success: function(response) {
                                if (response.status === 'success') {
                                    fetchPatientList(statusFilter.value);
                                } else {
                                    alert(response.message || 'Failed to update status');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', error);
                                alert('An error occurred. Please try again.');
                            }
                        });
                    });
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching patient list:', error);
            }
        });
    };
    

    addPatientForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const name = document.getElementById('patient-name').value.trim();
        const code = document.getElementById('patient-code').value.trim();
        const severity = document.getElementById('patient-severity').value;

        if (name && code && severity) {
            try {
                const response = await fetch('create_patient.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ name, code, severity })
                });

                if (response.ok) {
                    alert('Patient added successfully!');
                    addPatientForm.reset();
                    fetchPatientList();
                } else {
                    throw new Error('Failed to add patient');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        } else {
            alert('Please fill in all fields.');
        }
    });
});
