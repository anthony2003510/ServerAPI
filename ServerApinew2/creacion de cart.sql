use WS_API_M07;

CREATE or alter procedure sp_sap_add_product
    @product_id nvarchar(255)
AS
BEGIN
    DECLARE @ret INT;
    DECLARE @exists INT;
    set @ret=1;

    EXEC @exists = sf_sap_product_exists @product_id = @product_id ;

    if(@exists = 0) 
    BEGIN
        insert into _sap_cart (product_id)
        values (@product_id);
        set @ret=0;
        EXEC sp_sap_utils_XMlresponse_card @product_id,@ret,@message = 'producto regitrado satisfactoriamente';
    end
    else
        EXEC sp_sap_utils_XMlresponse_card @product_id,@ret,@message = 'el producto ya esta en la base de datos';
		

   /********************************* TEST UNITARIO*********************************
      exec sp_sap_user_register "u1@gmail.com","1","u1"
      exec sp_sap_user_register "u2@gmail.com","1","u2"
      exec sp_sap_user_register "u3@gmail.com","1","u3"
      exec sp_sap_user_register "u4@gmail.com","1","u4"
    *********************************************************************************/
end
go


exec sp_sap_add_product "12"


CREATE or alter  function sf_sap_product_exists(@product_id nvarchar(255))
RETURNS INT
as

begin
DECLARE @response int;

SET @response = 0;
IF ( (SELECT count(*) from _sap_cart where product_id = @product_id) = 1)

SET @response = 1;

RETURN @response

/********************************* TEST UNITARIO*********************************
DECLARE @ret INT;
EXEC @ret = sf_if_exist_user @user_id = 'ely@gmail.com' ;
select @ret;
*********************************************************************************/
END
go







create or alter  procedure sp_sap_utils_XMlresponse_card
    @product_id nvarchar(255),@error nvarchar(255),@message nvarchar(255) 
AS
BEGIN
    SELECT @product_id AS 'id_product',@error AS 'error', @message AS 'message' FOR XML PATH(''), ROOT('XMLresponse')

    /********************************* TEST UNITARIO*********************************
       exec sp_sap_utils_XMLresponse hola;
    *********************************************************************************/
end
go