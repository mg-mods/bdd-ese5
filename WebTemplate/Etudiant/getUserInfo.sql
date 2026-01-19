SELECT cas.ID AS StudentID, 
        students.Hash AS Token,
        cas.UnivID AS StudentUnivID, 
        cas.Surname AS StudentLastName, 
        cas.Name AS StudentFirstName, 
        cas.Gender AS StudentGender, 
        cas.Ine AS StudentIneCode, 
        cas.Email AS StudentEmail, 
        cas.Phone AS StudentPhone, 
        cas.PhoneIndicator AS StudentPhoneIndicator, 
        cas.Course AS StudentCourse, 
        cas.StartYear AS StudentStartYear
FROM Master.FakeStudents cas
LEFT JOIN Master.Students students ON cas.ID = students.AuthID
WHERE cas.Hash = {{ hashInput.value }}
/* Hash is SHA-256 format */
