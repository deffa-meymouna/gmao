/*==============================================================*/
/* Nom de SGBD :  AT - PG9                                      */
/* Date de création :  05/09/2014 14:04:25                      */
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
/* Table : friends                                              */
/*==============================================================*/
create table friends (
   user_id              int4                 not null,
   friend_id            int4                 not null
)
without oids;

-- Définit la propriété de la table
alter table friends owner to usr_iptrevise_proprietaire
;
/*==============================================================*/
/* Table : language                                             */
/*==============================================================*/
create table language (
   id                   SERIAL not null,
   name                 varchar(15)          not null,
   abbreviation         varchar(10)          not null
)
without oids;

-- Définit la propriété de la table
alter table language owner to usr_iptrevise_proprietaire
;
/*==============================================================*/
/* Table : privilege                                            */
/*==============================================================*/
create table privilege (
   id                   SERIAL not null,
   role_id              int4                 null,
   is_allowed           bool                 not null,
   resource             varchar(100)         not null,
   privilege            varchar(100)         not null
)
without oids;

-- Définit la propriété de la table
alter table privilege owner to usr_iptrevise_proprietaire
;
/*==============================================================*/
/* Table : question                                             */
/*==============================================================*/
create table question (
   id                   SERIAL not null,
   question             varchar(50)          not null
)
without oids;

-- Définit la propriété de la table
alter table question owner to usr_iptrevise_proprietaire
;
/*==============================================================*/
/* Table : resource                                             */
/*==============================================================*/
create table resource (
   id                   SERIAL not null,
   name                 varchar(100)         not null
)
without oids;

-- Définit la propriété de la table
alter table resource owner to usr_iptrevise_proprietaire
;
/*==============================================================*/
/* Table : role                                                 */
/*==============================================================*/
create table role (
   id                   SERIAL not null,
   name                 varchar(30)          not null,
   description          varchar(100)         not null
)
without oids;

-- Définit la propriété de la table
alter table role owner to usr_iptrevise_proprietaire
;
/*==============================================================*/
/* Table : roles_parents                                        */
/*==============================================================*/
create table roles_parents (
   role_id              int4                 not null,
   parent_id            int4                 not null
)
without oids;

-- Définit la propriété de la table
alter table roles_parents owner to usr_iptrevise_proprietaire
;
/*==============================================================*/
/* Table : state                                                */
/*==============================================================*/
create table state (
   id                   SERIAL not null,
   state                VARCHAR(50)          not null
)
without oids;

-- Définit la propriété de la table
alter table state owner to usr_iptrevise_proprietaire
;
/*==============================================================*/
/* Table : te_ip_ip                                             */
/*==============================================================*/
create table te_ip_ip (
   ip_id                SERIAL not null,
   res_id               INT4                 not null,
   mac_id               INT4                 null,
   usr_id               int4                 not null,
   ip_adresse           d_ip                 not null,
   ip_lib               d_lib                not null,
   ip_des               d_des                null,
   ip_interface         d_lib                null,
   ip_nat               d_bool               not null default false
);

comment on table te_ip_ip is
'Table entité des adresses IP';

comment on column te_ip_ip.ip_id is
'Identifiant de l''entité IP';

comment on column te_ip_ip.res_id is
'Identifiant du réseau';

comment on column te_ip_ip.mac_id is
'Identifiant de la machine';

comment on column te_ip_ip.ip_adresse is
'Adresse IP';

comment on column te_ip_ip.ip_lib is
'Libellée de l''adresse IP';

comment on column te_ip_ip.ip_des is
'Description de l''adresse IP';

comment on column te_ip_ip.ip_interface is
'Nom de l''interface';

comment on column te_ip_ip.ip_nat is
'Interface natée';

-- Définit la propriété de la table
alter table te_ip_ip owner to usr_iptrevise_proprietaire
;
/*==============================================================*/
/* Table : te_machine_mac                                       */
/*==============================================================*/
create table te_machine_mac (
   mac_id               SERIAL not null,
   ip_id                int4                 null,
   usr_id               int4                 not null,
   mac_lib              d_lib                not null,
   mac_des              d_des                null,
   mac_interface        d_entier_court       null,
   mac_type             d_lib                null
);

comment on table te_machine_mac is
'Table entité des machines';

comment on column te_machine_mac.mac_id is
'Identifiant de la machine';

comment on column te_machine_mac.ip_id is
'Identifiant de l''entité IP';

comment on column te_machine_mac.usr_id is
'Créateur de la machine';

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
   usr_id               int4                 not null,
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

comment on column te_reseau_res.usr_id is
'Identifiant du créateur';

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
/* Table : "user"                                               */
/*==============================================================*/
create table "user" (
   id                   SERIAL not null,
   role_id              int4                 not null,
   language_id          int4                 not null,
   state_id             int4                 not null,
   question_id          int4                 not null,
   username             varchar(30)          not null,
   first_name           varchar(40)          null,
   last_name            varchar(40)          null,
   email                varchar(60)          not null,
   password             varchar(60)          not null,
   answer               varchar(100)         not null,
   picture              varchar(255)         null,
   registration_date    timestamp            null,
   registration_token   varchar(32)          null,
   email_confirmed      bool                 not null
)
without oids;

-- Définit la propriété de la table
alter table "user" owner to usr_iptrevise_proprietaire
;
/*==============================================================*/
/* Vue : ve_ip_ip                                               */
/*==============================================================*/
create or replace view ve_ip_ip as
SELECT ip.ip_id, res_id, ip.usr_id, ip_adresse, ip_lib, ip_des, ip_interface, ip_nat, mac.mac_id, mac.mac_lib, mac.mac_des, mac.mac_interface, mac.mac_type
   FROM te_ip_ip as ip
   LEFT JOIN te_machine_mac as mac ON mac.mac_id = ip.mac_id
  ORDER BY ip.ip_adresse;

comment on view ve_ip_ip is
'Vue des IPs avec jointure externe vers les machines associées s''il y en a';

-- Définition du propriétaire de la vue
alter table ve_ip_ip owner to usr_iptrevise_proprietaire
;
/*==============================================================*/
/* Vue : ve_reseau_res                                          */
/*==============================================================*/
create or replace view ve_reseau_res as
SELECT  res.res_id, res.res_lib, res.res_des, res.res_ip, res.res_masque, res.res_couleur, res.res_passerelle, 
        res.usr_id, usr.username, usr.email, usr.first_name,usr.last_name,
        count(DISTINCT ip.ip_id) AS ip_quantite, count(DISTINCT ip.mac_id) AS mac_quantite
   FROM te_reseau_res res
   inner join "user" as usr on usr.id = res.usr_id
   LEFT JOIN te_ip_ip ip ON ip.res_id = res.res_id   
  GROUP BY res.res_id, res.res_lib, res.res_des, res.res_ip, res.res_masque, res.res_couleur, res.res_passerelle, res.usr_id, usr.username, usr.email, usr.first_name,usr.last_name
  order by res.res_ip;

comment on view ve_reseau_res is
'Vue permettant de lister les réseaux et possédant des informations importantes';

-- Définition du propriétaire de la vue
alter table ve_reseau_res owner to usr_iptrevise_proprietaire
;
