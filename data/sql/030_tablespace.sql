/*==============================================================*/
/* Nom de SGBD :  AT - PG9                                      */
/* Date de création :  02/09/2014 14:53:17                      */
/*==============================================================*/


create tablespace tbl_iptrevise_data
   owner usr_iptrevise_proprietaire
   location '/var/lib/postgresql/tablespace/tbl_iptrevise_data';

create tablespace tbl_iptrevise_index
   owner usr_iptrevise_proprietaire
   location '/var/lib/postgresql/tablespace/tbl_iptrevise_data';

