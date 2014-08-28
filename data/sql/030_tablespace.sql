/*==============================================================*/
/* Nom de SGBD :  AT - PG9                                      */
/* Date de cr�ation :  24/07/2014 16:09:21                      */
/*==============================================================*/


create tablespace tbl_iptrevise_data
   owner usr_iptrevise_proprietaire
   location '/var/lib/postgres/tablespace/tbl_iptrevise_data';

create tablespace tbl_iptrevise_index
   owner usr_iptrevise_proprietaire
   location '/var/lib/postgres/tablespace/tbl_iptrevise_data';

