USE myTest;

ALTER PROCEDURE UsersInsert @idUser INT, @username NVARCHAR(50), @pwd NVARCHAR(50), @cid VARCHAR(100)
AS
	
BEGIN
	INSERT INTO usersCid(id, email, pwd,Cid)
	VALUES(@idUser, @username, @pwd,@cid);

	SELECT * FROM usersCid WHERE id = @idUser
	FOR XML PATH, root('users');
	-- la linea de arriba especifica el nombre de la etiqueta root en el xml que se mostrará
END


ALTER PROCEDURE	ShowUsers @username NVARCHAR(50)
AS	
BEGIN
	SELECT * FROM usersCid WHERE email = @username
	FOR XML PATH, root('users');

	-- la linea de arriba especifica el nombre de la etiqueta root en el xml que se mostrará
END


EXEC ShowUsers @username= 'anthony@gmail.com'

select * from usersCid

INSERT INTO Users(idUser,username,pwd) VALUES(2,'Maria','maria12345') 

CREATE TABLE usersCid (
    id INT PRIMARY KEY,
    email VARCHAR(50),
	pwd VARCHAR(100),
    Cid VARCHAR(100),
);