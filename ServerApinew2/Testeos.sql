

Iniciar DB 

    /********************************* TEST UNITARIO*********************************

        use WS_API_M07
        SELECT * FROM sysobjects where xtype='p';

    *********************************************************************************/

Registrar usuarios

    /********************************* TEST UNITARIO*********************************

      exec sp_sap_user_register "u1@gmail.com","1","u1"
      exec sp_sap_user_register "u2@gmail.com","1","u2"
      exec sp_sap_user_register "u3@gmail.com","1","u3"

      select * from _sap_users

    *********************************************************************************/
Crear conexiones

    /********************************* TEST UNITARIO*********************************
      DECLARE @RET int;
      BEGIN tran
      exec @ret = sp_sap_conn_create 'u1@gmail.com' 
      print @ret
      rollback


      select * from _sap_conn
    *********************************************************************************/


Logear usuarios

    /********************************* TEST UNITARIO*********************************

         exec sp_sap_user_login "u1@gmail.com","1"
         exec sp_sap_user_login "u2@gmail.com","1"
         exec sp_sap_user_login "u3@gmail.com","1"

    *********************************************************************************/

Ver tabla conexiones

    /********************************* TEST UNITARIO*********************************

       select * from _sap_conn;

    *********************************************************************************/


Validar si hay conexiones

    /********************************* TEST UNITARIO*********************************

       DECLARE @ret int;
       exec @ret = sp_sap_conn_validate 'd15abf30-c5ff-4682-8b6d-070c08f78115';
       print @ret;

       select * from _sap_conn;

    *********************************************************************************/

Deslogear usuarios

    /********************************* TEST UNITARIO*********************************

         exec sp_sap_user_logout '364E7F01-9C64-4E2B-85B8-3CC81218791E'

         select * from _sap_conn;

    *********************************************************************************/

Ver Tiempo de las conexiones

    /********************************* TEST UNITARIO*********************************

        select DATEDIFF(second,last_batch,GETDATE()),* from _sap_conn;

    *********************************************************************************/

Actualizar el last_batch

    /*********************************************TEST UNITARIO**********************

        select DATEDIFF(second,last_batch,GETDATE()) timing_conectado,* from _sap_conn;

        DECLARE @ret int;
        exec @ret =  sp_sap_conn_update_lbatch 'fd3cd9ca-52ae-40cc-9069-9f69dca5f10c' 
        print @ret;
        select DATEDIFF(second,last_batch,GETDATE())timing_conectado,* from _sap_conn;

    *********************************************************************************/

Purgar conexiones

    /*************************************TEST UNITARIO******************************
    select DATEDIFF(second,last_batch,GETDATE()) timing_conectado,* from _sap_conn;

    BEGIN tran
    exec sp_sap_conn_purgue
    rollback

    *********************************************************************************/

Reset DAta


    /**************************************TEST UNITARIO******************************
    DElete from _sap_conn;
    DElete from _sap_users;
    *********************************************************************************/