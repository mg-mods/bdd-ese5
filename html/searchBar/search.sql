SELECT TOP 50 contracts.ID AS ContractID,
                contracts.Title AS ContractTitle,
                contracts.FileName AS ContractFileName,
                contracts.FilePath AS ContractFilePath,
                contracts.StartDate AS ContractStartDate,
                contracts.EndDate AS ContractEndDate,
                contracts.CreationDate AS ContractCreationDate,
                CONCAT(cas.Surname, ' ', cas.Name) AS StudentName,
                cas.Course AS StudentCourse,
                cas.StartYear AS StudentStartYear,
                CONCAT(teachers.Surname, ' ', teachers.Name) AS TeacherName,
                CONCAT(contacts.Surname, ' ', contacts.Name) AS TutorName,
                contacts.Position AS TutorPosition,
                companies.Name AS CompanyName,
                companies.Domain AS CompanyDomain,
                companies.City AS CompanyCity,
                companies.Postal AS CompanyPostal,
                companies.Address AS CompanyAddress              

FROM Master.Contracts contracts
LEFT JOIN Master.Students students ON students.ID = contracts.StudentID
LEFT JOIN Master.FakeStudents cas ON cas.ID = students.AuthID
LEFT JOIN Master.Companies companies ON companies.ID = contracts.CompanyID
LEFT JOIN Master.Teachers teachers ON teachers.ID = contracts.TeacherID
LEFT JOIN Master.Contacts contacts ON contacts.ID = contracts.TutorID

WHERE (
    CONCAT(cas.Surname, ' ', cas.Name) LIKE '%{{ searchValue.value }}%'
    OR companies.Name LIKE '%{{ searchValue.value }}%'
    OR companies.Domain LIKE '%{{ searchValue.value }}%'
    OR contracts.City LIKE '%{{ searchValue.value }}%'
    OR contracts.Postal LIKE '%{{ searchValue.value }}%'
    OR contracts.Address LIKE '%{{ searchValue.value }}%'
    OR CONCAT(contacts.Surname, ' ', contacts.Name) LIKE '%{{ searchValue.value }}%'
    OR contacts.Phone LIKE '%{{ searchValue.value }}%'
    OR contacts.Email LIKE '%{{ searchValue.value }}%'
    OR contracts.Title LIKE '%{{ searchValue.value }}%'
)
ORDER BY contracts.StartDate DESC, contracts.CreationDate DESC, companies.Name
