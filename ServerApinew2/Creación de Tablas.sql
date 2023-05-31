CREATE DATABASE WS_API_M07;

use WS_API_M07;


DROP TABLE IF EXISTS _sap_cart
CREATE TABLE _sap_cart
(
	product_id nvarchar(255) not null primary key
)
DROP TABLE IF EXISTS _sap_users;

create table _sap_users
(
    user_id   nvarchar(255) not null
        primary key,
    nickname  nvarchar(255),
    user_name nvarchar(255) not null,
    surname   nvarchar(255),
    pwd       varbinary(64) not null,
    phone     nvarchar(255),
    _mndt     int              default 0,
    _created  datetime         default getdate(),
    _updated  datetime,
    _deleted  datetime,
    _row_guid uniqueidentifier default newid()
)
go


use WS_API_M07

DROP TABLE IF EXISTS _sap_conn;


 create table _sap_conn
(
    conn_guid uniqueidentifier default newid() not null,
    user_id   nvarchar(255) primary key constraint _sap_conn__sap_users_user_id_fk references _sap_users,
    cTime     datetime,
    last_batch datetime,
    _mndt     int              default 0,
    _created  datetime         default getdate(),
    _updated  datetime,
    _deleted  datetime,
    _row_guid uniqueidentifier default newid()
)
go

SELECT * from _sap_conn;


