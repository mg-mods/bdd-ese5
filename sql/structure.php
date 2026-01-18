<?php

// Infos des étudiants (from Master.Students)
$row['StudentID'] // Identifiant unique de l'étudiant (valeur interne à notre BDD)
$row['StudentLastName'] // Nom de l'étudiant
$row['StudentFirstName'] // Prénom de l'étudiant
$row['StudentGender'] // Genre de l'étudiant
$row['StudentIneCode'] // Code INE de l'étudiant
$row['StudentUnivID'] // Code universitaire de l'étu -> permet une jointure avec FakeStudents
$row['StudentEmail'] // E-mail perso de l'étudiant (pas besoin de récupérer son mail univ car on peut le générer à partir de son nom)
$row['StudentPhone'] // Téléphone de l'étudiant
$row['StudentPhoneIndicator'] // Indicateur tel (ex: +33) de l'étudiant
$row['StudentCourse'] // Diplôme étudié par l'étudiant
$row['StudentStartYear'] // Année de début du diplôme en cours (permet de calculer en quelle année l'étudiant est)
$row['StudentHash'] // Hash unique de l'étudiant (valeur interne à notre BDD, permet de gérer les logins de manière anonyme)

// Infos des étudiants depuis CAS (from Master.FakeStudents)
$row['FakeStudentID'] // Code universitaire de l'étudiant
$row['FakeStudentPwd'] // Mot de passe de l'étudiant
$row['FakeStudentEmail'] // E-mail universitaire de l'étudiant
$row['FakeStudentPhone'] // Téléphone de l'étudiant

// Infos des entreprises (from Master.Companies)
$row['CompanyID'] // Identifiant unique de l'entreprise
$row['CompanyName'] // Nom de l'entreprise
$row['CompanyDomain'] // Domaine de l'entreprise
$row['CompanyCreationDate'] // Date de création de l'entreprise
$row['CompanyContactID'] // Identifiant unique du contact de l'entreprise -> permet une jointure avec Contacts
$row['CompanyHash'] // Hash unique de l'entreprise (valeur interne à notre BDD, permet de gérer les logins de manière anonyme)

// Infos des offres (from Master.Offers)
$row['OfferID'] // Identifiant unique de l'offre
$row['OfferName'] // Titre de l'offre
$row['OfferType'] // Type de l'offre (stage, alternance)
$row['OfferCity'] // Ville de l'offre
$row['OfferPostal'] // Code postal de l'offre
$row['OfferAddress'] // Adresse de l'offre
$row['OfferCompanyID'] // Identifiant unique de l'entreprise signataire de la convention -> permet une jointure avec Companies
$row['OfferCoordsLng'] // Coordonnées Longitude de l'offre
$row['OfferCoordsLat'] // Coordonnées Latitude de l'offre
$row['OfferCreationDate'] // Date de création de l'offre
$row['OfferStartDate'] // Date de début de l'offre
$row['OfferEndDate'] // Date de fin de l'offre
$row['OfferFileName'] // Nom de fichier de l'offre (utilisé pour load le contenu de l'offre)
$row['OfferFilePath'] // Chemin de fichier de l'offre (utilisé pour load le contenu de l'offre)

// Infos des contacts (from Master.Contacts)
$row['ContactID'] // Identifiant unique du contact
$row['ContactLastName'] // Nom du contact
$row['ContactFirstName'] // Prénom du contact
$row['ContactGender'] // Genre du contact
$row['ContactEmail'] // E-mail du contact
$row['ContactPhone'] // Téléphone du contact
$row['ContactPosition'] // Métier du contact

// Infos des candidatures (from Master.Applications)
$row['ApplicationID'] // Identifiant unique de la candidature
$row['ApplicationCreationDate'] // Date de création de la candidature
$row['ApplicationStudentID'] // Identifiant unique de l'étudiant créateur de la candidature -> permet une jointure avec Students
$row['ApplicationOfferID'] // Identifiant unique de l'offre cible de la candidature -> permet une jointure avec Offers
$row['ApplicationResumeID'] // Identifiant unique du CV joint à la candidature -> permet une jointure avec Resumes
$row['ApplicationStatus'] // Statut de la candidature (valeur numérique)
$row['ApplicationFileName'] // Nom de fichier de la candidature (utilisé pour load le contenu de la candidature)
$row['ApplicationFilePath'] // Chemin de fichier de la candidature (utilisé pour load le contenu de la candidature)

// Infos des CV (from Master.Resumes)
$row['ResumeID'] // Identifiant unique du CV
$row['ResumeCreationDate'] // Date de création du CV
$row['ResumeStudentID'] // Identifiant unique de l'étudiant créateur du CV -> permet une jointure avec Students
$row['ResumeFileName'] // Nom de fichier de du CV (utilisé pour télécharger le CV)
$row['ResumeFilePath'] // Chemin de fichier du CV (utilisé pour télécharger le CV)

// Infos des conventions (from Master.Contracts)
$row['ContractID'] // Identifiant unique de la convention sur notre serveur
$row['ContractCreationDate'] // Date de création de la convention (fournie par le CFAI)
$row['ContractStudentID'] // Identifiant unique de l'étudiant signataire de la convention -> permet une jointure avec Students
$row['ContractCompanyID'] // Identifiant unique de l'entreprise signataire de la convention -> permet une jointure avec Companies
$row['ContractOfferID'] // Identifiant unique de l'offre d'origine de la convention (si existante) -> permet une jointure avec Offers
$row['ContractApplicationID'] // Identifiant unique de la candidature d'origine de la convention (si existante) -> permet une jointure avec Applications
$row['ContractTeacherID'] // Identifiant unique du professeur responsable signataire de la convention -> permet une jointure avec Teachers
$row['ContractURL'] // URL de la convention, fourni par le CFAI (stockée sur serveur externe)

// Infos des professeurs (from Master.Teachers)
$row['TeacherID'] // Identifiant unique du professeur
$row['TeacherLastName'] // Nom du professeur
$row['TeacherFirstName'] // Prénom du professeur
$row['TeacherGender'] // Genre du professeur
$row['TeacherEmail'] // E-mail perso du professeur (pas besoin de récupérer son mail univ car on peut le générer à partir de son nom)
$row['TeacherDept'] // Département de rattachement (principal) du professeur (optionnel)
$row['TeacherHash'] // Hash unique du professeur (valeur interne à notre BDD, permet de gérer les logins de manière anonyme)

?>