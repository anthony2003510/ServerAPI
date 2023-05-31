/*create procedure sp_get_cart
create procedure sp_add_cart
create procedure sp_update_cart
create procedure sp_drop_cart
create procedure sp_get_products*/
   
USE WS_API_M07

/********************************************************************************************************************/
/*sp_sap_conn_validate 
  Desccription : Valida si existe una conexión usando la función sf_conn_exist 
  @conn_guid: IN-> UNIQUEIDENTIFIER    
  Return: @ret Bool                                                                                                 */
/********************************************************************************************************************/


create or alter procedure sp_sap_conn_validate
    @conn_guid UNIQUEIDENTIFIER
AS
BEGIN

    DECLARE @ret integer = -1;
    DECLARE @response integer;

    EXEC @response =  dbo.sf_conn_exist  @conn_guid = @conn_guid ;

    if(@response = 1) set @ret = 0;

    return @ret;
   
    /********************************* TEST UNITARIO*********************************

       DECLARE @RET int;
       exec @ret = sp_sap_conn_validate 'fd3cd9ca-52ae-40cc-9069-9f69dca5f10c' ;
       print @ret;
       
       select * from _sap_conn;
    *********************************************************************************/
END
go

create or alter procedure sp_sap_conn_validate_user_id
    @user_id NVARCHAR(255)
AS
BEGIN

    DECLARE @ret integer = 1;
    DECLARE @response integer;

    EXEC @response =  dbo.sf_conn_exist_user_id  @user_id = @user_id ;

    if(@response = 1) set @ret = 0;

    return @ret;
   
    /********************************* TEST UNITARIO*********************************

       DECLARE @RET int;
       exec @ret = sp_sap_conn_validate '' ;
       print @ret;
       
       select * from _sap_conn;
    *********************************************************************************/
END
go


/********************************************************************************************************************/
/*sp_sap_utils_XMLresponse
  Description: genera respuesta XML                                                                                                                      
                                                                                                                    */
/********************************************************************************************************************/

create or alter  procedure sp_sap_utils_XMlresponse
    @error nvarchar(255),@message nvarchar(255) 
AS
BEGIN
    SELECT @error AS 'error', @message AS 'message' FOR XML PATH(''), ROOT('XMLresponse')

    /********************************* TEST UNITARIO*********************************
       exec sp_sap_utils_XMLresponse hola;
    *********************************************************************************/
end
go


/********************************************************************************************************************/
/* sp_sap_session_XMLresponse
   Description: DEvuelve información del usuario logeado.
   @user_id: IN -> NVARCHAR(255) - PK - UniqueKey,
*/
/********************************************************************************************************************/

CREATE or alter PROCEDURE sp_sap_session_XMLresponse
    @user_id nvarchar(255)
AS
BEGIN
   DECLARE @ret int = 1;
   exec @ret = sp_sap_conn_validate_user_id @user_id 
   
   IF(@ret = 0)
   BEGIN
	   SELECT [user].user_name, conn.conn_guid, CONVERT(int, 0) AS [error]
	   FROM _sap_users [user] 
	   JOIN _sap_conn conn 
	   ON [user].user_id = conn.user_id
	   where [user].user_id = @user_id 
	   FOR XML PATH(''), ELEMENTS, ROOT('XMLresponse')
   END
   ELSE
   BEGIN
   SET @ret = 1;	
		EXEC sp_sap_utils_XMlresponse @ret,@message = 'sesión no existente en la base de datos';
		RETURN @ret;
   END
END
   /********************************* TEST UNITARIO*********************************
         exec sp_sap_session_XMLresponse "u3@gmail.com"
    *********************************************************************************/
go


/********************************************************************************************************************/
/* sp_sap_conn_create 
   Description:  Registra la fecha de conexión del usuario.
   @user_id: IN -> PK - UniqueKey
   Return: @ret bool 
*/
/********************************************************************************************************************/
CREATE or alter  procedure sp_sap_conn_create
    @user_id nvarchar(255)
AS
BEGIN
    DECLARE @ret int = 1;
    DECLARE @time datetime;

	exec @ret = sp_sap_conn_validate_user_id @user_id 
	
	IF(@ret = 1)
	BEGIN
		SET @time = GETDATE()
		insert into _sap_conn (user_id, cTime,last_batch)
		values (@user_id, @time,@time)
		if (@@ROWCOUNT = 1) set @ret = 0;
		return  @ret;
	END
	ELSE
	SET @ret = 1;
		BEGIN
		 return @ret ;
		END
    
    /********************************* TEST UNITARIO*********************************
      DECLARE @RET int;

      exec @ret = sp_sap_conn_create 'u1@gmail.com' 
      print @ret
      rollback


      select * from _sap_conn
    *********************************************************************************/
END
go


/********************************************************************************************************************/
/* sp_sap_user_register 
   Description:  Registra un nuevo usuario.
   @user_id: IN -> NVARCHAR(255) - PK - UniqueKey,
   @pwd: IN -> NVARCHAR(255),
   @name: IN -> NVARCHAR(255)  
*/
/********************************************************************************************************************/

CREATE or alter procedure sp_sap_user_register
    @user_id nvarchar(255),@pwd nvarchar(255),@name nvarchar(255)
AS
BEGIN
    DECLARE @ret INT;
    DECLARE @exists INT;
    DECLARE @encryptedPWD varbinary(64);
    set @ret=1;

    EXEC @exists = sf_sap_user_exists @user_id = @user_id ;

    if(@exists = 0) 
    BEGIN
        SET @encryptedPWD = HASHBYTES('SHA2_256', @pwd)
        insert into _sap_users (user_id,pwd,user_name)
        values (@user_id,@encryptedPWD,@name);
        set @ret=0;
        EXEC sp_sap_utils_XMlresponse @ret,@message = 'usuario registrado';
    end
    else
        EXEC sp_sap_utils_XMlresponse @ret,@message = 'usuario ya registrado';
		

   /********************************* TEST UNITARIO*********************************
      exec sp_sap_user_register "u1@gmail.com","1","u1"
      exec sp_sap_user_register "u2@gmail.com","1","u2"
      exec sp_sap_user_register "u3@gmail.com","1","u3"
      exec sp_sap_user_register "u4@gmail.com","1","u4"
    *********************************************************************************/
end
go




/********************************************************************************************************************/
/* sp_sap_conn_update_lbatch
   Description: Actualiza el last_batch del usuario (la última vez que ha interactuado (control de conexión))
   @conn_guid: IN-> UNIQUEIDENTIFIER,
   Return: @ret Bool
*/
/********************************************************************************************************************/

CREATE or alter PROCEDURE sp_sap_conn_update_lbatch
     @conn_guid NVARCHAR(255)
AS
BEGIN
DECLARE @ret integer = 1;
UPDATE _sap_conn  SET last_batch = GETDATE() where conn_guid = @conn_guid;
if(@@rowcount = 1) SET @ret = 0

return @ret;
/*********************************************TEST UNITARIO*************************************************************
    select * from _sap_conn
    exec  sp_sap_conn_update_lbatch 'fdb7b93b-d47c-456f-b885-a626458fc0bf' 
***********************************************************************************************************************/
END
go





/***********************************************************************************************************************/
/*    sp_sap_conn_purgue
                                                                                                                       */               
/***********************************************************************************************************************/

CREATE OR ALTER PROCEDURE sp_sap_conn_purgue
AS
BEGIN
DELETE FROM _sap_conn where  DATEDIFF(second,last_batch,GETDATE()) > 5*60; 
/************************************************TEST UNITARIO***********************************************/
/*
select DATEDIFF(minute,last_batch,GETDATE()),* from _sap_conn

BEGIN tran
exec sp_sap_conn_purgue
rollback
                                                                                                                           */
/****************************************************************************************************************************/
END
go



/********************************************************************************************************************/
/* sp_sap_user_login
    Description: Autentica al usuario
   @user_id: IN -> NVARCHAR(255) - PK - UniqueKey,
   @pwd: IN -> NVARCHAR(255),
*/
/********************************************************************************************************************/

CREATE or alter procedure sp_sap_user_login
    @user_id nvarchar(255),@pwd nvarchar(255)
AS

BEGIN
    DECLARE @ret INT;
    DECLARE @valid INT;
    set @ret=1;

    exec @valid = dbo.sf_sap_user_validate_pwd @user_id = @user_id,@pwd = @pwd;

    if(@valid=0)
    BEGIN
        set @ret=0;
        exec @ret=sp_sap_conn_create @user_id;
		if(@ret = 1)
			BEGIN
			EXEC sp_sap_session_XMLresponse @user_id;
			END
		ELSE
			BEGIN
			EXEC sp_sap_session_XMLresponse @user_id;
			END
    END

   ELSE
        BEGIN 
             EXEC sp_sap_utils_XMlresponse @ret,@message = 'usuario no encontrado';
        END
   /********************************* TEST UNITARIO*********************************
         exec sp_sap_user_login "u1@gmail.com","1"
         exec sp_sap_user_login "u2@gmail.com","1"
         exec sp_sap_user_login "u3@gmail.com","1"
    *********************************************************************************/
end
go




/********************************************************************************************************************/
/* sp_sap_user_logout
   Description: Elimina la conexión del usuario.
   @user_id: IN -> NVARCHAR(255) - PK - UniqueKey,
   @pwd: IN -> NVARCHAR(255),
*/
/********************************************************************************************************************/

create or alter procedure sp_sap_user_logout
    @conn_guid UNIQUEIDENTIFIER
AS
BEGIN
DECLARE @ret integer = 1
	exec @ret = sp_sap_conn_validate @conn_guid;
	if(@ret = 0)
		BEGIN
		DELETE FROM _sap_conn where conn_guid = @conn_guid; 
		if(@@rowcount = 1) SET @ret = 0;
		if(@ret = 0) EXEC sp_sap_utils_XMlresponse @ret,@message = 'usuario desconectado';
		END
	ELSE
	BEGIN
	EXEC sp_sap_utils_XMlresponse @ret,@message = 'sesion inexistente';
	END
END
go  


/********************************************************************************************************************/
/* sp_sap_utils_who
   Description: Crea una vista con las conexiones.
*/
/********************************************************************************************************************/

create OR ALTER procedure sp_sap_utils_who
AS
BEGIN
  SELECT [user].user_name AS [name], [user].user_id AS [email], conn.last_batch AS [tiempo_de_conexion], DATEDIFF(second,last_batch,GETDATE()) AS [duracion_conexion]
   FROM _sap_users [user] 
   JOIN _sap_conn conn 
   ON [user].user_id = conn.user_id
   where 0 = 0
   ORDER BY last_batch ASC

END
go 