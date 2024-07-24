## 1. Patients

- **ID**: Unique identifier (INT, Primary Key)
- **Name**: Full name of the patient (VARCHAR)
- **Code**: 3-letter code for patient identification (CHAR(3))
- **Severity**: Severity of the condition (INT, e.g., rating from 1 to 5)
- **Status**: Current status (ENUM: 'waiting', 'treated')
- **Time in Queue**: Duration since the patient was added (TIMESTAMP DEFAULT CURRENT_TIMESTAMP)

## 2. Admins

- **ID**: Unique identifier (INT, Primary Key)
- **Username**: Admin's username (VARCHAR)
- **Password**: Admin's password (VARCHAR)
