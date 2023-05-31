-- DROP FUNCTION IF EXISTS sf_sap_user_exists;

CREATE   function sf_sap_user_exists(@user_id nvarchar(255))
RETURNS INT
as

begin
DECLARE @response int;

SET @response = 0;
IF ( (SELECT count(*) from _sap_users where user_id = @user_id) = 1)

SET @response = 1;

RETURN @response

/********************************* TEST UNITARIO*********************************
DECLARE @ret INT;
EXEC @ret = sf_if_exist_user @user_id = 'ely@gmail.com' ;
select @ret;
*********************************************************************************/
END
go


/*DROP FUNCTION IF EXISTS sf_sap_user_validate_pwd;*/

CREATE   function  sf_sap_user_validate_pwd(@user_id nvarchar(255),@pwd nvarchar(255))
RETURNS INT
as
begin
DECLARE @response int;
DECLARE @hashedPassword varbinary(64) = HASHBYTES('SHA2_256', @pwd);
SET @response = 1;

IF ( (SELECT count(*) from _sap_users where user_id = @user_id and pwd = @hashedPassword) = 1)
    SET @response = 0;

RETURN @response

/********************************* TEST UNITARIO*********************************
DECLARE @ret INT;
EXEC @ret = sf_sap_user_validate_pwd @user_id = 'ely@gmail.com' @pwd = 'comeme los kinders' ;
select @ret;
*********************************************************************************/

end
go

/*USE WS_API_M07;*/

/*DROP FUNCTION IF EXISTS sf_conn_exist;*/

CREATE function sf_conn_exist(@conn_guid uniqueidentifier)
RETURNS INT
AS
BEGIN
DECLARE @response int;

SET @response = 0;

if((select count(*) from _sap_conn where conn_guid = @conn_guid) = 1) 
SET @response = 1

RETURN @response;

/********************************* TEST UNITARIO*********************************
DECLARE @ret INT;
EXEC @ret = sf_conn_exist @conn_guid = '211e3628-362b-40da-a07d-1ba438f68ab6' ;
select @ret;

SELECT * from _sap_conn;

ANOTACIÓN : si la length guid no es convencional devuelve NULL;;;;;;;
*********************************************************************************/

end 
go


USE WS_API_M07

CREATE function sf_conn_exist_user_id(@user_id NVARCHAR(255))
RETURNS INT
AS
BEGIN
DECLARE @response int;

SET @response = 0;

if((select count(*) from _sap_conn where user_id = @user_id) = 1) 
SET @response = 1

RETURN @response;

/********************************* TEST UNITARIO*********************************
DECLARE @ret INT;
EXEC @ret = sf_conn_exist_user_id @user_id = 'khaldro@gmail.com' ;
select @ret;

SELECT * from _sap_conn;

ANOTACIÓN : si la length guid no es convencional devuelve NULL;;;;;;;
*********************************************************************************/

end 
go