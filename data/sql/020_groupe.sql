/*==============================================================*/
/* Nom de SGBD :  AT - PG9                                      */
/* Date de cr�ation :  24/07/2014 15:57:10                      */
/*==============================================================*/


/*==============================================================*/
/* Groupe : grp_iptrevise                                       */
/*==============================================================*/
create group grp_iptrevise;

alter group grp_iptrevise add user usr_iptrevise_proprietaire;

alter group grp_iptrevise add user usr_iptrevise_application;

alter group grp_iptrevise add user usr_iptrevise_lecteur;

