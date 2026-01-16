SELECT cas.UnivID AS UnivID, cas.Pass AS Pass, cas.Hash AS Token
FROM Master.FakeStudents cas
WHERE cas.UnivID = {{ loginInput.value }} AND cas.Pass = {{ passwordInput.value }}
/* Hash is SHA-256 format */