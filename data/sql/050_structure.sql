/*==============================================================*/
/* Nom de SGBD :  AT - PG9                                      */
/* Date de création :  24/07/2014 17:36:02                      */
/*==============================================================*/


/*==============================================================*/
/* Domaine : d_bool                                             */
/*==============================================================*/
create domain d_bool as BOOL;

comment on domain d_bool is
'Domaine des booleens';

/*==============================================================*/
/* Domaine : d_couleur                                          */
/*==============================================================*/
create domain d_couleur as CHAR(6);

comment on domain d_couleur is
'Couleur RVB sur 6 caractères (hexadécimaux)';

/*==============================================================*/
/* Domaine : d_des                                              */
/*==============================================================*/
create domain d_des as TEXT;

comment on domain d_des is
'Description des entités';

/*==============================================================*/
/* Domaine : d_entier_court                                     */
/*==============================================================*/
create domain d_entier_court as smallint;

comment on domain d_entier_court is
'Entier court';

/*==============================================================*/
/* Domaine : d_ip                                               */
/*==============================================================*/
create domain d_ip as INT8;

comment on domain d_ip is
'Entier long pouvant être négatif et stockant les adresses IP';

/*==============================================================*/
/* Domaine : d_lib                                              */
/*==============================================================*/
create domain d_lib as VARCHAR(32);

comment on domain d_lib is
'Libellé des entités';

/*==============================================================*/
/* Domaine : d_masque                                           */
/*==============================================================*/
create domain d_masque as smallint;

comment on domain d_masque is
'Masque d''une adresse IP';

/*==============================================================*/
/* Table : te_ip_ip                                             */
/*==============================================================*/
create table te_ip_ip (
   ip_id                SERIAL not null,
   res_id               INT4                 not null,
   ip_adresse           d_ip                 not null,
   ip_lib               d_lib                not null,
   ip_des               d_des                null
);

comment on table te_ip_ip is
'Table entité des adresses IP';

comment on column te_ip_ip.ip_id is
'Identifiant de l''entité IP';

comment on column te_ip_ip.res_id is
'Identifiant du réseau';

comment on column te_ip_ip.ip_adresse is
'Adresse IP';

comment on column te_ip_ip.ip_lib is
'Libellée de l''adresse IP';

comment on column te_ip_ip.ip_des is
'Description de l''adresse IP';

-- Définit la propriété de la table
alter table te_ip_ip owner to usr_iptrevise_proprietaire
;
/*==============================================================*/
/* Table : te_machine_mac                                       */
/*==============================================================*/
create table te_machine_mac (
   mac_id               SERIAL not null,
   mac_lib              d_lib                not null,
   mac_des              d_des                null,
   mac_interface        d_entier_court       null,
   mac_type             d_lib                null
);

comment on table te_machine_mac is
'Table entité des machines';

comment on column te_machine_mac.mac_id is
'Identifiant de la machine';

comment on column te_machine_mac.mac_lib is
'Libellé de la machine';

comment on column te_machine_mac.mac_des is
'Description de la machine';

comment on column te_machine_mac.mac_interface is
'Nombre d interface reseau de la machine';

comment on column te_machine_mac.mac_type is
'Type de la machine';

-- Définit la propriété de la table
alter table te_machine_mac owner to usr_iptrevise_proprietaire
;
/*==============================================================*/
/* Table : te_reseau_res                                        */
/*==============================================================*/
create table te_reseau_res (
   res_id               SERIAL not null,
   res_lib              d_lib                not null,
   res_des              d_des                null,
   res_ip               d_ip                 not null,
   res_masque           d_masque             not null,
   res_couleur          d_couleur            not null,
   res_passerelle       d_ip                 null
);

comment on table te_reseau_res is
'Table entité des réseaux';

comment on column te_reseau_res.res_id is
'Identifiant du réseau';

comment on column te_reseau_res.res_lib is
'Libellé du réseau';

comment on column te_reseau_res.res_des is
'Description du réseau';

comment on column te_reseau_res.res_ip is
'Adresse du réseau';

comment on column te_reseau_res.res_masque is
'Masque du réseau';

comment on column te_reseau_res.res_couleur is
'Couleur ergonomique du réseau';

comment on column te_reseau_res.res_passerelle is
'Passerelle par défaut du réseau';

-- Définit la propriété de la table
alter table te_reseau_res owner to usr_iptrevise_proprietaire
;
/*==============================================================*/
/* Table : tj_machine_ip                                        */
/*==============================================================*/
create table tj_machine_ip (
   ip_id                INT4                 not null,
   mac_id               INT4                 not null,
   interface            d_entier_court       null,
   nat                  d_bool               null
);

comment on table tj_machine_ip is
'Table de jointure entre les machines et les ips. Cette table liste donc l''ensemble des adresses ip de chaque machine';

comment on column tj_machine_ip.ip_id is
'Identifiant de l''entité IP';

comment on column tj_machine_ip.mac_id is
'Identifiant de la machine';

comment on column tj_machine_ip.interface is
'Numéro de l''interface (optionnel)';

comment on column tj_machine_ip.nat is
'Est à vrai si cette adresse ip est naté';

-- Définit la propriété de la table
alter table tj_machine_ip owner to usr_iptrevise_proprietaire
;
