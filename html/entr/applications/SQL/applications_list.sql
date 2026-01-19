SELECT
    A.ApplicationID,
    A.ApplicationStatus,
    O.OfferName,
    S.StudentFirstName,
    S.StudentLastName
FROM Applications A
JOIN Offers O ON O.OfferID = A.ApplicationOfferID
JOIN Companies C ON C.CompanyID = O.OfferCompanyID
JOIN Students S ON S.StudentID = A.ApplicationStudentID
WHERE C.CompanyHash = {{CompanyHash}}
