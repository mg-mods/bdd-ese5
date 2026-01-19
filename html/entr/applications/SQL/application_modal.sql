SELECT
    O.OfferName,
    A.ApplicationStatus,
    A.ApplicationFilePath AS ApplicationText,
    R.ResumeFilePath AS ResumePath,
    S.StudentFirstName,
    S.StudentLastName,
    S.StudentEmail
FROM Applications A
JOIN Offers O ON O.OfferID = A.ApplicationOfferID
JOIN Students S ON S.StudentID = A.ApplicationStudentID
LEFT JOIN Resumes R ON R.ResumeID = A.ApplicationResumeID
WHERE A.ApplicationID = {{ApplicationID}}
